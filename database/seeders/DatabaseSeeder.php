<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            AcademicYearSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            IndustrySeeder::class,
            IndustryPartnershipSeeder::class,
            EvaluationIndicatorSeeder::class,
            InternshipSeeder::class,
            AssessmentSeeder::class,
        ]);
    }
}
