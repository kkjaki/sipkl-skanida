<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateValidationController extends Controller
{
    /**
     * Display the certificate validation page for the logged-in student.
     */
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403, 'Anda belum terdaftar sebagai siswa.');
        }

        // Get the student's active internship with related data
        $internship = Internship::with(['student.user', 'industry', 'certificates'])
            ->where('student_id', $student->user_id)
            ->latest()
            ->first();

        $certificate = null;
        $hasScores = false;

        if ($internship) {
            $hasScores = $internship->assessmentScores()->exists();

            // Get or create the certificate
            $certificate = $internship->certificates()->first();

            if (!$certificate) {
                $certificate = Certificate::create([
                    'internship_id' => $internship->id,
                    'status' => 'draft',
                ]);
            }
        }

        return view('certificate-validations.index', compact('internship', 'certificate', 'hasScores'));
    }

    /**
     * Validate the certificate data — student confirms their data is correct.
     */
    public function validate(Request $request, Certificate $certificate)
    {
        $student = Auth::user()->student;

        // Ensure this certificate belongs to the logged-in student
        $internship = $certificate->internship;

        if (!$internship || $internship->student_id !== $student->user_id) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }

        // Only allow validation if status is 'draft'
        if ($certificate->status !== 'draft') {
            return back()->with('info', 'Sertifikat sudah divalidasi sebelumnya.');
        }

        $certificate->update(['status' => 'validated']);

        return back()->with('success', 'Data sertifikat berhasil divalidasi. Menunggu proses cetak oleh Admin/Humas.');
    }
}
