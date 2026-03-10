<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipWithdrawalController extends Controller
{
    /**
     * Get the department_id of the authenticated Kaprog.
     * Returns null for admin (no restriction).
     */
    private function departmentScope(): ?int
    {
        if (Auth::user()->hasRole('admin')) {
            return null;
        }

        $supervisor = Auth::user()->supervisor;

        if (! $supervisor) {
            abort(403, 'Akses ditolak. Profil tidak ditemukan.');
        }

        return $supervisor->department_id;
    }

    /**
     * Display list of active (ongoing) internships.
     * Admin: all departments. Kaprog: own department only.
     */
    public function index(Request $request)
    {
        $departmentId = $this->departmentScope();

        $query = Internship::where('status', 'ongoing')
            ->with(['student.user', 'student.department', 'industry', 'supervisor.user']);

        if ($departmentId !== null) {
            $query->whereHas('student', fn ($q) => $q->where('department_id', $departmentId));
        }

        // Load all active internships within scope
        $internships = $query->latest()->get()->map(function ($internship) {
            $internship->formatted_start_date = $internship->start_date ? $internship->start_date->format('d M Y') : '-';
            return $internship;
        });

        // For filters
        $departments = \App\Models\Department::orderBy('name')->get();
        
        $industriesQuery = \App\Models\Industry::where('is_synced', true);
        if ($departmentId !== null) {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            if ($activeYear) {
                $industriesQuery->whereHas('allocations', function ($q) use ($departmentId, $activeYear) {
                    $q->where('department_id', $departmentId)
                      ->where('academic_year_id', $activeYear->id)
                      ->where('quota', '>', 0);
                });
            }
        }
        $industries = $industriesQuery->orderBy('name')->get();

        return view('internships.index', compact('internships', 'departments', 'industries', 'departmentId'));
    }

    /**
     * Mark an internship as withdrawn (Pindahkan Lokasi Siswa).
     * Sets status = 'withdrawn' and actual_end_date = today.
     */
    public function withdraw(Internship $internship)
    {
        $departmentId = $this->departmentScope();

        // Ensure the internship is in the right scope
        if ($departmentId !== null) {
            $internship->loadMissing('student');

            if (! $internship->student || $internship->student->department_id !== $departmentId) {
                abort(403, 'Anda tidak memiliki akses untuk memindahkan siswa ini.');
            }
        }

        if ($internship->status !== 'ongoing') {
            return back()->with('error', 'Hanya siswa dengan status aktif yang dapat dipindahkan.');
        }

        $internship->update([
            'status'          => 'withdrawn',
            'actual_end_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Status siswa berhasil diubah. Siswa dapat di-plot kembali ke lokasi baru.');
    }
}
