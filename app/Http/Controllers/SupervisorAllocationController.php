<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\AcademicYear;
use App\Models\SupervisorAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Handles the allocation of quota to supervisors by the Curriculum role.
 */
class SupervisorAllocationController extends Controller
{
    /**
     * Display a listing of supervisor allocations for the active academic year.
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Ensure we handle case where no active year is set
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Cache the supervisors data for 60 minutes
        // Key includes active year ID to invalidate when year changes
        $supervisors = Cache::remember('supervisor_allocations_' . $activeYear->id, 60 * 60, function () use ($activeYear) {
            return Supervisor::whereHas('user', function ($query) {
                $query->role('supervisor');
            })
            ->with(['user', 'department', 'allocations' => function ($query) use ($activeYear) {
                $query->where('academic_year_id', $activeYear->id);
            }])
            ->get()
            ->sortBy(fn($supervisor) => $supervisor->user->department_id); // Sort by department_id
        });

        return view('supervisors.allocate', compact('supervisors', 'activeYear'));
    }

    /**
     * Update supervisor allocations in bulk.
     */
    public function updateBulk(Request $request)
    {
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*' => 'integer|min:0', // key is supervisor_id, value is quota
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        DB::transaction(function () use ($request, $activeYear) {
            foreach ($request->allocations as $supervisorId => $quota) {
                SupervisorAllocation::updateOrCreate(
                    [
                        'supervisor_id' => $supervisorId,
                        'academic_year_id' => $activeYear->id,
                    ],
                    [
                        'quota' => $quota
                    ]
                );
            }
        });

        // Clear the cache for this academic year so updates are reflected immediately
        Cache::forget('supervisor_allocations_' . $activeYear->id);
        
        // Also clear dashboard stats as total quota changed
        Cache::forget('dashboard_stats');

        return redirect()->route('supervisors.allocate')
            ->with('success', 'Kuota pembimbing berhasil diperbarui.');
    }
}
