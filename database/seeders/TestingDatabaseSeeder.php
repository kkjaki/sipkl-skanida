<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Orchestrator untuk semua testing seeders.
     *
     * Prerequisite seeders (Role, Department, EvaluationIndicator) dipanggil
     * terlebih dahulu menggunakan firstOrCreate agar aman baik di fresh DB
     * maupun setelah DatabaseSeeder normal.
     *
     * Jalankan dengan:
     *   php artisan db:seed --class=TestingDatabaseSeeder
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🧪 ═══════════════════════════════════════');
        $this->command->info('   TESTING SEEDER SUITE');
        $this->command->info('   4 State Siswa × 8 + 2 State Guru × 4');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('');

        $this->call([
            // --- Prerequisites (idempotent) ---
            RoleSeeder::class,
            DepartmentSeeder::class,
            EvaluationIndicatorSeeder::class,

            // --- Testing Data ---
            TestingAcademicYearSeeder::class,
            TestingStudentSeeder::class,
            TestingStaffSeeder::class,
            TestingIndustrySeeder::class,
            TestingIndustryPartnershipSeeder::class,
            TestingInternshipSeeder::class,
            TestingDailyJournalSeeder::class,
            TestingAssessmentScoreSeeder::class,
            TestingCertificateSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Testing seeder selesai!');
        $this->command->info('');
        $this->command->info('📋 Akun Login Testing:');
        $this->command->info('   ┌─────────────────────────────────────────────────────────┐');
        $this->command->info('   │ ADMIN & KURIKULUM                                       │');
        $this->command->info('   │ Admin      : admin@smkn2magelang.sch.id                  │');
        $this->command->info('   │ Kurikulum  : curriculum@smkn2magelang.sch.id              │');
        $this->command->info('   ├─────────────────────────────────────────────────────────┤');
        $this->command->info('   │ SISWA                                                   │');
        $this->command->info('   │ STATE 1 (Aktif PKL)     : 90101@smkn2magelang.sch.id    │');
        $this->command->info('   │ STATE 2 (Belum Di-plot) : 90201@smkn2magelang.sch.id    │');
        $this->command->info('   │ STATE 3 (Siap Validasi) : 90301@smkn2magelang.sch.id    │');
        $this->command->info('   │ STATE 4 (Baru)          : 90401@smkn2magelang.sch.id    │');
        $this->command->info('   ├─────────────────────────────────────────────────────────┤');
        $this->command->info('   │ KEPALA PROGRAM (KAPROG)                                 │');
        $this->command->info('   │ PPLG  : pplg@smkn2magelang.sch.id                       │');
        $this->command->info('   │ AKL   : akl@smkn2magelang.sch.id                        │');
        $this->command->info('   │ MPLB  : mplb@smkn2magelang.sch.id                       │');
        $this->command->info('   │ PM    : pm@smkn2magelang.sch.id                         │');
        $this->command->info('   ├─────────────────────────────────────────────────────────┤');
        $this->command->info('   │ GURU PEMBIMBING                                         │');
        $this->command->info('   │ STATE 1 (Jurnal Pending)  : 9901@smkn2magelang.sch.id   │');
        $this->command->info('   │ STATE 2 (Belum Dinilai)   : 9905@smkn2magelang.sch.id   │');
        $this->command->info('   ├─────────────────────────────────────────────────────────┤');
        $this->command->info('   │ Password semua akun: password                            │');
        $this->command->info('   └─────────────────────────────────────────────────────────┘');
        $this->command->info('');
    }
}
