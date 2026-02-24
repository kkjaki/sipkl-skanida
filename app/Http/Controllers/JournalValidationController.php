<?php

namespace App\Http\Controllers;

use App\Models\DailyJournal;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalValidationController extends Controller
{
    /**
     * Display list of students under the logged-in supervisor.
     */
    public function index()
    {
        $supervisorId = Auth::id();

        $internships = Internship::where('supervisor_id', $supervisorId)
            ->with(['student.user', 'industry'])
            ->withCount(['dailyJournals as pending_count' => function ($q) {
                $q->where('verification_status', 'pending');
            }])
            ->get();

        return view('journal-validations.index', compact('internships'));
    }

    /**
     * Display journal entries for a specific internship.
     */
    public function show(Request $request, Internship $internship)
    {
        // Security: pastikan internship milik supervisor yang login
        if ($internship->supervisor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $internship->load(['student.user', 'industry']);

        // Filter & Sort
        $status = $request->input('status', 'all');
        $sort   = $request->input('sort', 'date_desc');

        $query = DailyJournal::where('internship_id', $internship->id);

        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        $query->orderBy('date', $sort === 'date_asc' ? 'asc' : 'desc');

        $journals = $query->paginate(15)->withQueryString();

        return view('journal-validations.show', compact('internship', 'journals', 'status', 'sort'));
    }

    /**
     * Bulk verify or reject journals.
     */
    public function bulkUpdate(Request $request, Internship $internship)
    {
        // Security: pastikan internship milik supervisor yang login
        if ($internship->supervisor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $request->validate([
            'journal_ids'    => 'required|array|min:1',
            'journal_ids.*'  => 'exists:daily_journals,id',
            'action'         => 'required|in:verify,reject',
            'rejection_note' => 'required_if:action,reject|nullable|string|max:1000',
        ]);

        try {
            return DB::transaction(function () use ($request, $internship) {
                // Pastikan semua journal_ids milik internship ini
                $validCount = DailyJournal::where('internship_id', $internship->id)
                    ->whereIn('id', $request->journal_ids)
                    ->count();

                if ($validCount !== count($request->journal_ids)) {
                    throw new \Exception('Beberapa jurnal tidak valid atau bukan milik siswa ini.');
                }

                $journals = DailyJournal::where('internship_id', $internship->id)
                    ->whereIn('id', $request->journal_ids);

                if ($request->action === 'verify') {
                    $journals->update([
                        'verification_status' => 'verified',
                        'verified_at'         => now(),
                        'rejection_note'      => null,
                    ]);
                    $message = count($request->journal_ids) . ' jurnal berhasil divalidasi.';
                } else {
                    $journals->update([
                        'verification_status' => 'rejected',
                        'rejection_note'      => $request->rejection_note,
                    ]);
                    $message = count($request->journal_ids) . ' jurnal ditolak.';
                }

                return back()->with('success', $message);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
