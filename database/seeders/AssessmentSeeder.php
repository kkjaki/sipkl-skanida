<?php

namespace Database\Seeders;

use App\Models\AssessmentScore;
use App\Models\EvaluationIndicator;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = EvaluationIndicator::all();
        if ($indicators->isEmpty()) {
            return;
        }

        $state3StudentIds = User::where('email', 'like', '903%')->pluck('id');
        if ($state3StudentIds->isEmpty()) {
            return;
        }

        $internships = Internship::whereIn('student_id', $state3StudentIds)->get();
        if ($internships->isEmpty()) {
            return;
        }

        foreach ($internships as $internship) {
            foreach ($indicators as $indicator) {
                $scoreIndustry = rand(80, 95);
                $diff = rand(-5, 5);
                $scoreSchool = min(100, max(75, $scoreIndustry + $diff));

                AssessmentScore::firstOrCreate(
                    [
                        'internship_id' => $internship->id,
                        'indicator_id' => $indicator->id,
                    ],
                    [
                        'score_industry' => $scoreIndustry,
                        'score_school' => $scoreSchool,
                    ]
                );
            }
        }
    }
}
