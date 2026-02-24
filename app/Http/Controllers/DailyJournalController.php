<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyJournalRequest;
use App\Http\Requests\UpdateDailyJournalRequest;
use App\Models\DailyJournal;
use App\Models\Internship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyJournalController extends Controller
{
    /**
     * Display the journal form and history for the logged-in student.
     */
    public function index()
    {
        $userId = Auth::id();

        $internship = Internship::where('student_id', $userId)
            ->where('status', 'ongoing')
            ->first();

        $journals = collect();

        if ($internship) {
            $journals = DailyJournal::where('internship_id', $internship->id)
                ->orderByDesc('date')
                ->paginate(10);
        }

        return view('journals.index', compact('internship', 'journals'));
    }

    /**
     * Store a new daily journal entry.
     */
    public function store(StoreDailyJournalRequest $request)
    {
        $userId = Auth::id();

        $internship = Internship::where('student_id', $userId)
            ->where('status', 'ongoing')
            ->firstOrFail();

        $validated = $request->validated();

        // Duplicate check: same date + same internship
        $exists = DailyJournal::where('internship_id', $internship->id)
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['date' => 'Anda sudah mengisi jurnal untuk tanggal ini. Silakan pilih tanggal lain.']);
        }

        // Handle file upload — simpan per folder siswa
        $attachmentPath = null;
        if ($request->hasFile('attachment_path')) {
            $attachmentPath = $request->file('attachment_path')
                ->store("journals/{$userId}", 'public');
        }

        DailyJournal::create([
            'internship_id'       => $internship->id,
            'date'                => $validated['date'],
            'status_attendance'   => $validated['status_attendance'],
            'activity'            => $validated['activity'] ?? null,
            'attachment_path'     => $attachmentPath,
            'verification_status' => 'pending',
        ]);

        return redirect()->route('student.journals.index')
            ->with('success', 'Jurnal harian berhasil disimpan.');
    }

    /**
     * Show the form for editing a journal entry.
     * Allowed only when verification_status is 'pending' or 'rejected'.
     */
    public function edit(DailyJournal $journal)
    {
        $userId = Auth::id();

        // Ensure ownership: journal belongs to the student's internship
        $internship = Internship::where('student_id', $userId)
            ->where('id', $journal->internship_id)
            ->firstOrFail();

        // Only allow edit if pending or rejected
        if (!in_array($journal->verification_status, ['pending', 'rejected'])) {
            return redirect()->route('student.journals.index')
                ->with('error', 'Jurnal yang sudah divalidasi tidak dapat diedit.');
        }

        return view('journals.edit', compact('journal', 'internship'));
    }

    /**
     * Update a journal entry.
     * Resets verification_status to 'pending' on revision.
     */
    public function update(UpdateDailyJournalRequest $request, DailyJournal $journal)
    {
        $userId = Auth::id();

        // Ensure ownership
        $internship = Internship::where('student_id', $userId)
            ->where('id', $journal->internship_id)
            ->firstOrFail();

        // Only allow update if pending or rejected
        if (!in_array($journal->verification_status, ['pending', 'rejected'])) {
            return redirect()->route('student.journals.index')
                ->with('error', 'Jurnal yang sudah divalidasi tidak dapat diedit.');
        }

        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('attachment_path')) {
            // Delete old file if exists
            if ($journal->attachment_path) {
                Storage::disk('public')->delete($journal->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment_path')
                ->store("journals/{$userId}", 'public');
        } else {
            unset($validated['attachment_path']);
        }

        $journal->update(array_merge($validated, [
            'verification_status' => 'pending',
            'rejection_note'     => null,
        ]));

        return redirect()->route('student.journals.index')
            ->with('success', 'Jurnal harian berhasil diperbarui.');
    }
}
