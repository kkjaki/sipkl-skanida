<?php

namespace Database\Seeders;

use App\Models\AssessmentScore;
use App\Models\EvaluationIndicator;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestingAssessmentScoreSeeder extends Seeder
{
    /**
     * Assessment scores untuk STATE 3 (8 siswa, internship finished).
     * Setiap siswa mendapat skor untuk SEMUA evaluation indicators.
     *
     * score_school: random 75–95
     * score_industry: random 78–98
     */
    public function run(): void
    {
        $indicators = EvaluationIndicator::all();

        if ($indicators->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada EvaluationIndicator. Jalankan EvaluationIndicatorSeeder terlebih dahulu.');
            return;
        }

        // Resolve STATE 3 students (NIS 903XX)
        $state3NisList = [];
        for ($seq = 1; $seq <= 8; $seq++) {
            $state3NisList[] = sprintf('9%02d%02d', 3, $seq);
        }

        $seededCount = 0;

        foreach ($state3NisList as $nis) {
            $studentUser = User::where('email', $nis . '@smkn2magelang.sch.id')->first();
            if (!$studentUser) {
                continue;
            }

            $internship = Internship::where('student_id', $studentUser->id)->first();
            if (!$internship) {
                continue;
            }

            foreach ($indicators as $indicator) {
                AssessmentScore::firstOrCreate(
                    [
                        'internship_id' => $internship->id,
                        'indicator_id' => $indicator->id,
                    ],
                    [
                        'score_school' => rand(75, 95),
                        'score_industry' => rand(78, 98),
                    ]
                );

                $seededCount++;
            }
        }

        $this->command->info("✅ TestingAssessmentScoreSeeder: {$seededCount} skor (8 siswa × " . $indicators->count() . " indikator) seeded.");
    }
}
