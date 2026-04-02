<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class TestingAcademicYearSeeder extends Seeder
{
    /**
     * Seed tahun akademik aktif (idempotent).
     */
    public function run(): void
    {
        AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );
    }
}
