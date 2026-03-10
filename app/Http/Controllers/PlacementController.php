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
        $activeYear   = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();

        // Industries: synced, open, with allocation for this department in active year
        $industries = Industry::where('is_synced', true)
            ->where('status', 'open')
            ->whereHas('allocations', function ($q) use ($departmentId, $activeYear) {
                $q->where('department_id', $departmentId)
                  ->where('academic_year_id', $activeYear->id)
                  ->where('quota', '>', 0);
            })
            ->with(['allocations' => function ($q) use ($departmentId, $activeYear) {
                $q->where('department_id', $departmentId)
                  ->where('academic_year_id', $activeYear->id);
            }, 'partnerships', 'internships' => function ($q) use ($departmentId, $activeYear) {
                $q->where('academic_year_id', $activeYear->id)
                  ->whereIn('status', ['ongoing', 'finished'])
                  ->whereHas('student', fn ($q2) => $q2->where('department_id', $departmentId))
                  ->with('student.user');
            }])
            ->get()
            ->map(function ($industry) {
                $allocation = $industry->allocations->first();
                $industry->quota = $allocation->quota ?? 0;

                // Hitung dari relasi yang sudah di-eager load (no N+1 query)
                $internsCount = $industry->internships->count();

                $industry->interns_count   = $internsCount;
                $industry->remaining_quota = $industry->quota - $internsCount;

                // Flag: apakah industri ini memiliki MoU aktif
                $industry->has_active_mou = $industry->active_partnership !== null;

                // Assign data intern langsung dari relasi (no N+1 query)
                $industry->current_interns = $industry->internships;

                return $industry;
            });

        // Candidates: siswa yang TIDAK punya internship ongoing/finished di tahun ajaran ini.
        // Siswa yang hanya punya internship withdrawn tetap masuk sebagai kandidat (is_transfer = true).
        $candidates = Student::where('department_id', $departmentId)
            ->whereDoesntHave('internships', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id)
                  ->whereIn('status', ['ongoing', 'finished']);
            })
            ->with('user')
            ->get()
            ->map(function ($student) use ($activeYear) {
                $student->is_transfer = $student->internships()
                    ->where('academic_year_id', $activeYear->id)
                    ->where('status', 'withdrawn')
                    ->exists();

                return $student;
            });

        return view('placements.index', compact('industries', 'candidates', 'activeYear'));
    }

    /**
     * Bulk store student placements.
     * - start_date      : dari MoU industri (reguler), atau transfer_start_date/now() (pindahan)
     * - actual_end_date : dari end_date MoU industri (semua siswa)
     */
    public function store(Request $request)
    {
        $request->validate([
            'industry_id'         => 'required|exists:industries,id',
            'student_ids'         => 'required|array|min:1',
            'student_ids.*'       => 'exists:students,user_id',
            'transfer_start_date' => 'nullable|date|before_or_equal:today',
        ]);

        $activeYear   = AcademicYear::where('is_active', true)->firstOrFail();
        $departmentId = $this->kaprogDepartmentId();
        $industryId   = $request->industry_id;
        $studentIds   = $request->student_ids;

        try {
            return DB::transaction(function () use ($industryId, $studentIds, $activeYear, $departmentId, $request) {
                // Verify industry is still available
                $industry = Industry::where('id', $industryId)
                    ->where('is_synced', true)
                    ->where('status', 'open')
                    ->firstOrFail();

                // Validasi: industri harus memiliki MoU aktif
                $mou = $industry->active_partnership;
                if (! $mou) {
                    throw new \Exception('Industri ini tidak memiliki MoU aktif. Plotting tidak dapat dilakukan.');
                }

                // Get allocation for this department
                $allocation = $industry->allocations()
                    ->where('department_id', $departmentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->firstOrFail();

                // Calculate remaining quota (exclude withdrawn)
                $currentCount = Internship::where('industry_id', $industryId)
                    ->where('academic_year_id', $activeYear->id)
                    ->whereIn('status', ['ongoing', 'finished'])
                    ->whereHas('student', fn ($q) => $q->where('department_id', $departmentId))
                    ->count();

                $remainingQuota = $allocation->quota - $currentCount;

                if (count($studentIds) > $remainingQuota) {
                    throw new \Exception('Jumlah siswa melebihi sisa kuota industri.');
                }

                // Verify all students belong to this department & have no active/finished internship
                $validStudents = Student::whereIn('user_id', $studentIds)
                    ->where('department_id', $departmentId)
                    ->whereDoesntHave('internships', function ($q) use ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id)
                          ->whereIn('status', ['ongoing', 'finished']);
                    })
                    ->get();

                if ($validStudents->count() !== count($studentIds)) {
                    throw new \Exception('Beberapa siswa tidak valid atau sudah memiliki penempatan aktif.');
                }

                // Tanggal mulai untuk siswa pindahan (fallback: now jika dikosongkan)
                $transferStartDate = $request->transfer_start_date ?? now()->toDateString();

                // Bulk insert
                $placements = [];
                foreach ($validStudents as $student) {
                    // Siswa pindahan: pernah withdrawn di tahun ajaran yang sama
                    $isTransfer = $student->internships()
                        ->where('academic_year_id', $activeYear->id)
                        ->where('status', 'withdrawn')
                        ->exists();

                    $startDate = $isTransfer
                        ? $transferStartDate
                        : $mou->start_date->toDateString();

                    $placements[] = [
                        'student_id'       => $student->user_id,
                        'industry_id'      => $industryId,
                        'academic_year_id' => $activeYear->id,
                        'start_date'       => $startDate,
                        'actual_end_date'  => $mou->end_date->toDateString(),
                        'status'           => 'ongoing',
                        'created_at'       => now(),
                        'updated_at'       => now(),
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
