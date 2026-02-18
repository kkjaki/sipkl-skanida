<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\IndustryAllocation;
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

        $stats = Cache::remember('dashboard_stats', 60 * 60, function () use ($activeYear) {
            if (!$activeYear) {
                return [
                    'active_year'        => null,
                    'total_students'     => 0,
                    'students_per_dept'  => collect(),
                ];
            }

            $totalStudents = Student::where('academic_year_id', $activeYear->id)->count();

            $studentsPerDept = Student::where('academic_year_id', $activeYear->id)
                ->join('departments', 'students.department_id', '=', 'departments.id')
                ->selectRaw('departments.id, departments.name, departments.code, COUNT(*) as total')
                ->groupBy('departments.id', 'departments.name', 'departments.code')
                ->orderBy('departments.name')
                ->get();

            return [
                'active_year'        => $activeYear->name,
                'total_students'     => $totalStudents,
                'students_per_dept'  => $studentsPerDept,
            ];
        });

        return view('dashboard.admin', compact('stats', 'activeYear'));
    }

    /**
     * Student: personal PKL status.
     */
    private function studentDashboard($user)
    {
        $student = Student::where('user_id', $user->id)->first();

        return view('dashboard.student', [
            'user'    => $user,
            'student' => $student,
        ]);
    }

    /**
     * Kaprog (Department Head): department-focused statistics.
     */
    private function kaprogDashboard($user)
    {
        $deptId = $user->supervisor->department_id;
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

        // 2. Siswa Belum PKL: students in this dept (active year) without internship
        $siswaBelumPkl = 0;
        if ($activeYear) {
            $siswaBelumPkl = Student::where('department_id', $deptId)
                ->where('academic_year_id', $activeYear->id)
                ->whereDoesntHave('internship')
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
        
        // 1. Total Guru Pembimbing (tanpa Kepala program): count users with role 'supervisor'
        $totalSupervisors = User::role('supervisor')->count();

        // 2. Total guru pembimbing yang sudah dialokasikan ke siswa (memiliki alokasi di tahun aktif)
        $totalSupervisorsAllocated = SupervisorAllocation::where('academic_year_id', $activeYear->id)
            ->where('quota', '>', 0)
            ->distinct('supervisor_id')
            ->count('supervisor_id');

        // 3. Total Indikator Penilaian
        $totalIndicators = EvaluationIndicator::count();

        // 4. Total Students Allocated (Progress Kuota)
        // We sum up the quota assigned to supervisors for the active year
        $totalAllocatedQuota = 0;
        $totalStudents = 0;
        if ($activeYear) {
            $totalAllocatedQuota = SupervisorAllocation::where('academic_year_id', $activeYear->id)
                ->sum('quota');
                $totalStudents = Student::where('academic_year_id', $activeYear->id)->count();
        }


        return view('dashboard.curriculum', compact(
            'activeYear',
            'totalSupervisors',
            'totalSupervisorsAllocated',
            'totalIndicators',
            'totalAllocatedQuota',
            'totalStudents'
        ));
    }

    /**
     * Supervisor: placeholder.
     */
    private function supervisorDashboard($user)
    {
        return view('dashboard.supervisor', [
            'user' => $user,
        ]);
    }
}
