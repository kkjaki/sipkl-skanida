<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Internship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestingCertificateSeeder extends Seeder
{
    /**
     * Update sertifikat STATE 3: 30% → validated, 70% → tetap draft.
     *
     * Certificate record sudah dibuat di TestingInternshipSeeder (semua draft).
     * Seeder ini meng-update 3 sertifikat pertama menjadi 'validated'.
     */
    public function run(): void
    {
        // Resolve STATE 3 students (NIS 903XX)
        $state3NisList = [];
        for ($seq = 1; $seq <= 8; $seq++) {
            $state3NisList[] = sprintf('9%02d%02d', 3, $seq);
        }

        $validatedCount = 0;

        foreach ($state3NisList as $idx => $nis) {
            $studentUser = User::where('email', $nis . '@smkn2magelang.sch.id')->first();
            if (!$studentUser) {
                continue;
            }

            $internship = Internship::where('student_id', $studentUser->id)->first();
            if (!$internship) {
                continue;
            }

            $certificate = Certificate::where('internship_id', $internship->id)->first();
            if (!$certificate) {
                continue;
            }

            // First 3 (index 0, 1, 2) → validated (≈30% of 8)
            if ($idx < 3) {
                $certificate->update([
                    'status' => 'validated',
                    'certificate_number' => sprintf('SERT/PKL/TESTING/%d/%03d', date('Y'), $idx + 1),
                    'issued_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                ]);
                $validatedCount++;
            }
            // Rest stay as 'draft' (no update needed)
        }

        $draftCount = 8 - $validatedCount;
        $this->command->info("✅ TestingCertificateSeeder: {$validatedCount} validated + {$draftCount} draft certificates.");
    }
}
