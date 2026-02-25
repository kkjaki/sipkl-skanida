<?php

namespace App\Exports;

use App\Models\Internship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradeRecapPerClassSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private string $className;
    private int $rowNumber = 0;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * Sheet tab title.
     */
    public function title(): string
    {
        return $this->className;
    }

    /**
     * Query internships for this specific class.
     */
    public function collection()
    {
        return Internship::with(['student.user', 'industry'])
            ->withAvg('assessmentScores as avg_industry', 'score_industry')
            ->withAvg('assessmentScores as avg_school', 'score_school')
            ->whereHas('student', function ($query) {
                $query->where('class_name', $this->className);
            })
            ->orderBy(
                \App\Models\Student::select('users.name')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->whereColumn('students.user_id', 'internships.student_id')
                    ->limit(1)
            )
            ->get();
    }

    /**
     * Multi-row headings: title, class, spacer, then column headers.
     */
    public function headings(): array
    {
        return [
            ['REKAPITULASI NILAI PRAKTIK KERJA LAPANGAN (PKL)'],
            ['Kelas: ' . $this->className],
            [''],
            ['No', 'NIS', 'Nama Siswa', 'Lokasi Praktik', 'Rerata Nilai Industri', 'Rerata Nilai Sekolah', 'NILAI AKHIR'],
        ];
    }

    /**
     * Map each internship row to match headings order.
     */
    public function map($row): array
    {
        $this->rowNumber++;

        $avgIndustry = $row->avg_industry;
        $avgSchool = $row->avg_school;

        // Calculate final score only if both averages exist
        $finalScore = ($avgIndustry !== null && $avgSchool !== null)
            ? round(($avgIndustry + $avgSchool) / 2, 2)
            : null;

        return [
            $this->rowNumber,
            $row->student->nis ?? '-',
            $row->student->user->name ?? '-',
            $row->industry->name ?? '-',
            $avgIndustry !== null ? round($avgIndustry, 2) : '-',
            $avgSchool !== null ? round($avgSchool, 2) : '-',
            $finalScore !== null ? $finalScore : '-',
        ];
    }

    /**
     * Style the sheet: merge title, bold headers.
     */
    public function styles(Worksheet $sheet): array
    {
        // Merge title row across all columns
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');

        return [
            // Row 1: Title — bold, centered
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Row 2: Class subtitle — bold, centered
            2 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Row 4: Column headers — bold
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
