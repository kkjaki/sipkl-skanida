<?php

namespace Database\Seeders;

use App\Models\EvaluationIndicator;
use Illuminate\Database\Seeder;

class EvaluationIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            'Menerapkan softskills yang dibutuhkan dalam dunia kerja (tempat PKL)',
            'Menerapkan norma, POS, dan K3LH yang berlaku dalam dunia kerja (tempat PKL)',
            'Penerapan Integritas',
            'Penerapan Etos Kerja',
        ];

        foreach ($indicators as $name) {
            EvaluationIndicator::firstOrCreate(['name' => $name]);
        }
    }
}
