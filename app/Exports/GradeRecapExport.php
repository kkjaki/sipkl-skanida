<?php

namespace App\Exports;

use App\Models\Internship;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GradeRecapExport implements WithMultipleSheets
{
    /**
     * Return an array of sheets — one per class that has internship data.
     */
    public function sheets(): array
    {
        // Get distinct class names from students who have internships
        $classes = Internship::join('students', 'internships.student_id', '=', 'students.user_id')
            ->distinct()
            ->orderBy('students.class_name')
            ->pluck('students.class_name')
            ->toArray();

        $sheets = [];

        foreach ($classes as $className) {
            $sheets[] = new GradeRecapPerClassSheet($className);
        }

        return $sheets;
    }
}
