<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Industry;
use App\Models\Internship;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlacementController extends Controller
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
     * Display available industries and candidates for placement.
     */
    public function index()
    {
        $activeYear  = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();

        // Industries: synced, open, with allocation for this department in active year
        $industries = Industry::where('is_synced', true)
            ->where('status', 'open')
            ->whereHas('allocations', function ($q) use ($departmentId, $activeYear) {
                $q->where('department_id', $departmentId)
                  ->where('academic_year_id', $activeYear->id);
            })
            ->with(['allocations' => function ($q) use ($departmentId, $activeYear) {
                $q->where('department_id', $departmentId)
                  ->where('academic_year_id', $activeYear->id);
            }])
            ->get()
            ->map(function ($industry) use ($activeYear, $departmentId) {
                $allocation = $industry->allocations->first();
                $industry->quota = $allocation->quota ?? 0;

                // Count interns for this industry+department in active year
                $internsCount = Internship::where('industry_id', $industry->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                $industry->interns_count    = $internsCount;
                $industry->remaining_quota  = $industry->quota - $internsCount;

                // Load current interns with student & user info
                $industry->current_interns = Internship::where('industry_id', $industry->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->with('student.user')
                    ->get();

                return $industry;
            });

        // Candidates: students in same department without any internship in active year
        $candidates = Student::where('department_id', $departmentId)
            ->whereDoesntHave('internships', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->with('user')
            ->get();

        return view('placements.index', compact('industries', 'candidates', 'activeYear'));
    }

    /**
     * Bulk store student placements.
     */
    public function store(Request $request)
    {
        $request->validate([
            'industry_id'   => 'required|exists:industries,id',
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,user_id',
        ]);

        $activeYear   = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();
        $industryId   = $request->industry_id;
        $studentIds   = $request->student_ids;

        try {
            return DB::transaction(function () use ($industryId, $studentIds, $activeYear, $departmentId) {
                // Verify industry is still available
                $industry = Industry::where('id', $industryId)
                    ->where('is_synced', true)
                    ->where('status', 'open')
                    ->firstOrFail();

                // Get allocation for this department
                $allocation = $industry->allocations()
                    ->where('department_id', $departmentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->firstOrFail();

                // Calculate remaining quota
                $currentCount = Internship::where('industry_id', $industryId)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                $remainingQuota = $allocation->quota - $currentCount;

                if (count($studentIds) > $remainingQuota) {
                    throw new \Exception('Jumlah siswa melebihi sisa kuota industri.');
                }

                // Verify all students belong to this department & have no active internship
                $validStudents = Student::whereIn('user_id', $studentIds)
                    ->where('department_id', $departmentId)
                    ->whereDoesntHave('internships', function ($q) use ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id);
                    })
                    ->get();

                if ($validStudents->count() !== count($studentIds)) {
                    throw new \Exception('Beberapa siswa tidak valid atau sudah memiliki penempatan.');
                }

                // Bulk insert
                $placements = [];
                foreach ($studentIds as $studentId) {
                    $placements[] = [
                        'student_id'      => $studentId,
                        'industry_id'     => $industryId,
                        'academic_year_id' => $activeYear->id,
                        'start_date'      => now(),
                        'status'          => 'ongoing',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }

                Internship::insert($placements);

                return redirect()->route('placements.index')
                    ->with('success', 'Siswa berhasil di-plot ke industri.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan penempatan: ' . $e->getMessage());
        }
    }

    /**
     * Remove a student placement (hard delete with confirmation).
     */
    public function destroy(Internship $internship)
    {
        $departmentId = $this->kaprogDepartmentId();

        // Ensure the internship belongs to a student in the Kaprog's department
        $internship->loadMissing('student');

        if (! $internship->student || $internship->student->department_id !== $departmentId) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus penempatan ini.');
        }

        try {
            $internship->delete();

            return back()->with('success', 'Penempatan siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penempatan.');
        }
    }

    /**
     * Bulk remove student placements.
     */
    public function destroyBulk(Request $request)
    {
        $request->validate([
            'internship_ids'   => 'required|array|min:1',
            'internship_ids.*' => 'exists:internships,id',
        ]);

        $departmentId = $this->kaprogDepartmentId();

        try {
            $deleted = DB::transaction(function () use ($request, $departmentId) {
                $internships = Internship::whereIn('id', $request->internship_ids)
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->get();

                if ($internships->count() !== count($request->internship_ids)) {
                    throw new \Exception('Beberapa penempatan tidak ditemukan atau bukan milik jurusan Anda.');
                }

                Internship::whereIn('id', $internships->pluck('id'))->delete();

                return $internships->count();
            });

            return back()->with('success', "{$deleted} penempatan siswa berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penempatan: ' . $e->getMessage());
        }
    }
}
