<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Certificate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CertificateGenerationController extends Controller
{
    /**
     * Display the certificate management page with optional class filter.
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        $selectedClass = $request->query('class');

        $certificates = collect();

        if ($activeYear && $selectedClass) {
            $certificates = Certificate::with(['internship.student.user', 'internship.industry'])
                ->whereHas('internship', function ($query) use ($activeYear, $selectedClass) {
                    $query->where('academic_year_id', $activeYear->id)
                        ->whereHas('student', function ($q) use ($selectedClass) {
                            $q->where('class_name', $selectedClass);
                        });
                })
                ->get();
        }

        $availableClasses = Student::AVAILABLE_CLASSES;

        return view('certificates.index', compact('certificates', 'availableClasses', 'selectedClass'));
    }

    /**
     * Generate bulk PDF certificates for the selected students.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'certificate_ids' => ['required', 'array', 'min:1'],
            'certificate_ids.*' => ['integer', 'exists:certificates,id'],
            'middle_number' => ['required', 'string', 'max:50'],
            'issued_date' => ['required', 'date'],
        ]);

        $year = Carbon::parse($request->issued_date)->format('Y');
        $certificateNumber = '422.6 / ' . $request->middle_number . ' / ' . $year;

        // Bulk update the selected certificates
        Certificate::whereIn('id', $request->certificate_ids)->update([
            'certificate_number' => $certificateNumber,
            'issued_date' => $request->issued_date,
            'status' => 'generated',
        ]);

        // Fetch fresh data with eager loading for PDF render
        $certificates = Certificate::with(['internship.student.user', 'internship.industry'])
            ->whereIn('id', $request->certificate_ids)
            ->get();

        // Determine class name for the PDF filename
        $className = optional($certificates->first()?->internship?->student)->class_name ?? 'Semua';

        $pdf = Pdf::loadView('certificates.pdf-template', compact('certificates'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_PKL_(' . $className . ').pdf');
    }
}
