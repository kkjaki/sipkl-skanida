<?php

namespace App\Http\Controllers;

use App\Exports\GradeRecapExport;
use App\Models\Internship;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class GradeRecapController extends Controller
{
    /**
     * Display the grade recap dashboard.
     * Data cached for 30 minutes. Filtered client-side via Alpine.js.
     */
    public function index()
    {
        $internships = Cache::remember('grade_recap', 1800, function () {
            return Internship::with(['student.user', 'industry'])
                ->withAvg('assessmentScores as avg_industry', 'score_industry')
                ->withAvg('assessmentScores as avg_school', 'score_school')
                ->orderBy(
                    Student::select('class_name')
                        ->whereColumn('students.user_id', 'internships.student_id')
                        ->limit(1)
                )
                ->orderBy(
                    Student::select('users.name')
                        ->join('users', 'students.user_id', '=', 'users.id')
                        ->whereColumn('students.user_id', 'internships.student_id')
                        ->limit(1)
                )
                ->get();
        });

        $availableClasses = Student::AVAILABLE_CLASSES;

        return view('grade-recap.index', compact('internships', 'availableClasses'));
    }

    /**
     * Export grade recap to Excel with multi-sheet (per class).
     */
    public function export()
    {
        return Excel::download(new GradeRecapExport, 'rekap-nilai-pkl.xlsx');
    }
}
