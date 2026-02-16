<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with cached statistics.
     */
    public function index()
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

        return view('dashboard', compact('stats', 'activeYear'));
    }
}
