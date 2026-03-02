<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\IndustryAllocation;
use App\Models\Internship;
use App\Models\Supervisor;
use App\Models\EvaluationIndicator;
use App\Models\SupervisorAllocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the role-appropriate dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // ── Admin Dashboard ──
        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        // ── Student Dashboard ──
        if ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        }

        // ── Kaprog (Department Head) Dashboard ──
        if ($user->hasRole('department_head')) {
            return $this->kaprogDashboard($user);
        }

        // ── Curriculum (WKS Kurikulum) Dashboard ──
        if ($user->hasRole('curriculum')) {
            return $this->curriculumDashboard();
        }

        // ── Supervisor Dashboard ──
        if ($user->hasRole('supervisor')) {
            return $this->supervisorDashboard($user);
        }

        // Fallback
        return $this->adminDashboard();
    }

    /**
     * Admin: school-wide statistics.
     */
    private function adminDashboard()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        $stats = Cache::remember('dashboard_admin_stats', 60 * 60, function () use ($activeYear) {
            if (!$activeYear) {
                return [
                    'active_year'             => null,
                    'total_students'          => 0,
                    'students_per_dept'       => collect(),
                    'total_synced_industries' => 0,
                    'students_placed'         => 0,
                    'students_unplaced'       => 0,
                    'active_supervisors'      => 0,
                ];
            }

            $totalStudents = Student::where('academic_year_id', $activeYear->id)->count();

            $studentsPerDept = Student::where('academic_year_id', $activeYear->id)
                ->join('departments', 'students.department_id', '=', 'departments.id')
                ->selectRaw('departments.id, departments.name, departments.code, COUNT(*) as total')
                ->groupBy('departments.id', 'departments.name', 'departments.code')
                ->orderBy('departments.name')
                ->get();

            $totalSyncedIndustries = Industry::where('is_synced', true)->count();

            $studentsPlaced = Internship::where('academic_year_id', $activeYear->id)
                ->distinct('student_id')
                ->count('student_id');

            $activeSupervisors = User::role('supervisor')->count();

            return [
                'active_year'             => $activeYear->name,
                'total_students'          => $totalStudents,
                'students_per_dept'       => $studentsPerDept,
                'total_synced_industries' => $totalSyncedIndustries,
                'students_placed'         => $studentsPlaced,
                'students_unplaced'       => max(0, $totalStudents - $studentsPlaced),
                'active_supervisors'      => $activeSupervisors,
            ];
        });

        return view('dashboard.admin', compact('stats', 'activeYear'));
    }

    /**
     * Student: personal PKL status & journal summary.
     */
    private function studentDashboard($user)
    {
        $student = Student::where('user_id', $user->id)
            ->with(['internship.industry', 'internship.dailyJournals'])
            ->first();

        $internship       = $student?->internship;
        $totalJournals    = $internship?->dailyJournals->count() ?? 0;
        $verifiedJournals = $internship?->dailyJournals->where('verification_status', 'approved')->count() ?? 0;
        $pendingJournals  = $internship?->dailyJournals->where('verification_status', 'pending')->count() ?? 0;

        return view('dashboard.student', [
            'user'             => $user,
            'student'          => $student,
            'internship'       => $internship,
            'totalJournals'    => $totalJournals,
            'verifiedJournals' => $verifiedJournals,
            'pendingJournals'  => $pendingJournals,
        ]);
    }

    /**
     * Kaprog (Department Head): department-focused statistics.
     */
    private function kaprogDashboard($user)
    {
        $deptId     = $user->supervisor->department_id;
        $department = $user->supervisor->department;
        $activeYear = AcademicYear::where('is_active', true)->first();

        // 1. Waiting Approval: proposals from students in this department, not yet synced
        $waitingApproval = Industry::where('is_synced', false)
            ->where('status', '!=', 'blacklisted')
            ->whereNotNull('student_submitter_id')
            ->whereHas('studentSubmitter', function ($q) use ($deptId) {
                $q->whereHas('student', fn($s) => $s->where('department_id', $deptId));
            })
            ->count();

        // 2. Siswa Belum PKL dan Sudah PKL
        $siswaBelumPkl = 0;
        $siswaPlaced   = 0;
        if ($activeYear) {
            $siswaBelumPkl = Student::where('department_id', $deptId)
                ->where('academic_year_id', $activeYear->id)
                ->whereDoesntHave('internship')
                ->count();

            $siswaPlaced = Student::where('department_id', $deptId)
                ->where('academic_year_id', $activeYear->id)
                ->whereHas('internship')
                ->count();
        }

        // 3. Mitra Aktif: industries with quota > 0 for this department (active year)
        $mitraAktif = 0;
        if ($activeYear) {
            $mitraAktif = IndustryAllocation::where('department_id', $deptId)
                ->where('academic_year_id', $activeYear->id)
                ->where('quota', '>', 0)
                ->distinct('industry_id')
                ->count('industry_id');
        }

        // 4. 5 Pengajuan Terbaru for quick action
        $recentProposals = Industry::where('is_synced', false)
            ->where('status', '!=', 'blacklisted')
            ->whereNotNull('student_submitter_id')
            ->whereHas('studentSubmitter', function ($q) use ($deptId) {
                $q->whereHas('student', fn($s) => $s->where('department_id', $deptId));
            })
            ->with('studentSubmitter')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.kaprog', compact(
            'department',
            'activeYear',
            'waitingApproval',
            'siswaBelumPkl',
            'siswaPlaced',
            'mitraAktif',
            'recentProposals'
        ));
    }

    /**
     * Curriculum (WKS Kurikulum): overall monitoring.
     */
    private function curriculumDashboard()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        $stats = Cache::remember('dashboard_curriculum_stats', 30 * 60, function () use ($activeYear) {
            // 1. Total Guru Pembimbing
            $totalSupervisors = User::role('supervisor')->count();

            // 2. Total guru pembimbing yang sudah dialokasikan
            $totalSupervisorsAllocated = $activeYear
                ? SupervisorAllocation::where('academic_year_id', $activeYear->id)
                    ->where('quota', '>', 0)
                    ->distinct('supervisor_id')
                    ->count('supervisor_id')
                : 0;

            // 3. Total Indikator Penilaian
            $totalIndicators = EvaluationIndicator::count();

            // 4. Quota vs Students (progress)
            $totalAllocatedQuota = 0;
            $totalStudents       = 0;
            if ($activeYear) {
                $totalAllocatedQuota = SupervisorAllocation::where('academic_year_id', $activeYear->id)->sum('quota');
                $totalStudents       = Student::where('academic_year_id', $activeYear->id)->count();
            }

            // 5. Siswa yang belum ada nilai PKL
            $studentsWithoutScores = 0;
            if ($activeYear) {
                $studentsWithoutScores = Internship::where('academic_year_id', $activeYear->id)
                    ->whereDoesntHave('assessmentScores')
                    ->count();
            }

            return compact(
                'totalSupervisors',
                'totalSupervisorsAllocated',
                'totalIndicators',
                'totalAllocatedQuota',
                'totalStudents',
                'studentsWithoutScores'
            );
        });

        return view('dashboard.curriculum', array_merge(
            $stats,
            ['activeYear' => $activeYear]
        ));
    }

    /**
     * Supervisor: personal bimbingan statistics.
     */
    private function supervisorDashboard($user)
    {
        $data = Cache::remember('dashboard_supervisor_' . $user->id, 5 * 60, function () use ($user) {
            $supervisor = Supervisor::where('user_id', $user->id)
                ->with([
                    'internships.dailyJournals',
                    'internships.assessmentScores',
                    'internships.student.user',
                    'internships.industry',
                ])
                ->first();

            $internships = $supervisor?->internships ?? collect();

            $totalStudents = $internships->count();

            $pendingJournals = $internships->sum(
                fn($i) => $i->dailyJournals->where('verification_status', 'pending')->count()
            );

            $studentsUnassessed = $internships->filter(
                fn($i) => $i->assessmentScores->isEmpty()
            )->count();

            return [
                'totalStudents'      => $totalStudents,
                'pendingJournals'    => $pendingJournals,
                'studentsUnassessed' => $studentsUnassessed,
                'internships'        => $internships,
            ];
        });

        return view('dashboard.supervisor', array_merge(
            $data,
            ['user' => $user]
        ));
    }
}
