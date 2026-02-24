<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Internship;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorPlacementController extends Controller
{
    /**
     * Get the department_id of the authenticated Kaprog.
     */
    private function kaprogDepartmentId(): int
    {
        $supervisor = Auth::user()->supervisor;

        if (! $supervisor) {
            abort(403, 'Akses ditolak. Profil Kaprog tidak ditemukan.');
        }

        return $supervisor->department_id;
    }

    /**
     * Display available supervisors and candidates for supervisor assignment.
     */
    public function index()
    {
        $activeYear   = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();

        // Supervisors: same department + has allocation with quota > 0 in active year
        $supervisors = Supervisor::where('department_id', $departmentId)
            ->whereHas('allocations', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id)
                  ->where('quota', '>', 0);
            })
            ->with(['user', 'allocations' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            }])
            ->get()
            ->map(function ($supervisor) use ($activeYear, $departmentId) {
                $allocation = $supervisor->allocations->first();
                $supervisor->quota = $allocation->quota ?? 0;

                // Count interns assigned to this supervisor in active year + same department
                $internsCount = Internship::where('supervisor_id', $supervisor->user_id)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                $supervisor->interns_count   = $internsCount;
                $supervisor->remaining_quota = $supervisor->quota - $internsCount;

                // Load current interns with student, user, and industry info
                $supervisor->current_interns = Internship::where('supervisor_id', $supervisor->user_id)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->with(['student.user', 'industry'])
                    ->get();

                return $supervisor;
            });

        // Candidates: internships in active year, supervisor_id = null, industry_id NOT null,
        // student belongs to same department
        $candidates = Internship::where('academic_year_id', $activeYear->id)
            ->whereNull('supervisor_id')
            ->whereNotNull('industry_id')
            ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
            ->with(['student.user', 'industry'])
            ->get();

        return view('supervisor-placements.index', compact('supervisors', 'candidates', 'activeYear'));
    }

    /**
     * Bulk assign a supervisor to selected internships.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supervisor_id'    => 'required|exists:supervisors,user_id',
            'internship_ids'   => 'required|array|min:1',
            'internship_ids.*' => 'exists:internships,id',
        ]);

        $activeYear   = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();
        $supervisorId = $request->supervisor_id;
        $internshipIds = $request->internship_ids;

        try {
            return DB::transaction(function () use ($supervisorId, $internshipIds, $activeYear, $departmentId) {
                // Verify supervisor belongs to same department
                $supervisor = Supervisor::where('user_id', $supervisorId)
                    ->where('department_id', $departmentId)
                    ->firstOrFail();

                // Get allocation for this supervisor in active year
                $allocation = $supervisor->allocations()
                    ->where('academic_year_id', $activeYear->id)
                    ->where('quota', '>', 0)
                    ->firstOrFail();

                // Calculate remaining quota
                $currentCount = Internship::where('supervisor_id', $supervisorId)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                $remainingQuota = $allocation->quota - $currentCount;

                if (count($internshipIds) > $remainingQuota) {
                    throw new \Exception('Jumlah siswa melebihi sisa kuota guru pembimbing.');
                }

                // Verify all internships are valid candidates
                $validInternships = Internship::whereIn('id', $internshipIds)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereNull('supervisor_id')
                    ->whereNotNull('industry_id')
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                if ($validInternships !== count($internshipIds)) {
                    throw new \Exception('Beberapa siswa tidak valid atau sudah memiliki guru pembimbing.');
                }

                // Bulk update
                Internship::whereIn('id', $internshipIds)->update([
                    'supervisor_id' => $supervisorId,
                ]);

                return redirect()->route('supervisor-placements.index')
                    ->with('success', 'Guru pembimbing berhasil di-plot ke siswa.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan penempatan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk un-assign supervisor from selected internships.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'internship_ids'   => 'required|array|min:1',
            'internship_ids.*' => 'exists:internships,id',
        ]);

        $departmentId = $this->kaprogDepartmentId();

        try {
            $count = DB::transaction(function () use ($request, $departmentId) {
                $internships = Internship::whereIn('id', $request->internship_ids)
                    ->whereNotNull('supervisor_id')
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->get();

                if ($internships->count() !== count($request->internship_ids)) {
                    throw new \Exception('Beberapa penempatan tidak ditemukan atau bukan milik jurusan Anda.');
                }

                Internship::whereIn('id', $internships->pluck('id'))->update([
                    'supervisor_id' => null,
                ]);

                return $internships->count();
            });

            return back()->with('success', "{$count} penempatan guru pembimbing berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penempatan: ' . $e->getMessage());
        }
    }
}
