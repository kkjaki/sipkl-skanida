<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
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
            IndustrySeeder::class,
            IndustryPartnershipSeeder::class,
            EvaluationIndicatorSeeder::class,
            InternshipSeeder::class,
            DailyJournalSeeder::class,
            AssessmentSeeder::class,
        ]);

        $this->summarizeUsers();
    }

    private function summarizeUsers(): void
    {
        if (! $this->command) {
            return;
        }

        $roleCounts = [
            'admin' => User::where('role', 'admin')->count(),
            'curriculum' => User::where('role', 'curriculum')->count(),
            'department_head' => User::where('role', 'department_head')->count(),
            'supervisor' => User::where('role', 'supervisor')->count(),
            'student' => User::where('role', 'student')->count(),
        ];

        $stateCounts = [
            'STATE 1' => Student::where('nis', 'like', '901%')->count(),
            'STATE 2' => Student::where('nis', 'like', '902%')->count(),
            'STATE 3' => Student::where('nis', 'like', '903%')->count(),
            'STATE 4' => Student::where('nis', 'like', '904%')->count(),
        ];

        $this->command->info('=== Rangkuman Akun Seed ===');
        $this->command->info('Admin: '.$roleCounts['admin'].' (admin@smkn2magelang.sch.id)');
        $this->command->info('Kurikulum: '.$roleCounts['curriculum'].' (curriculum@smkn2magelang.sch.id)');
        $this->command->info('Kaprog: '.$roleCounts['department_head'].' (pplg@, akl@, mplb@, pm@)');
        $this->command->info('Guru Pembimbing: '.$roleCounts['supervisor'].' (9901-9908)');
        $this->command->info('  - STATE 1: 9901-9904 membimbing siswa STATE 1 per jurusan');
        $this->command->info('  - STATE 3: 9905-9908 membimbing siswa STATE 3 per jurusan');
        $this->command->info('Siswa: '.$roleCounts['student'].' (NIS 9SSDD, email {NIS}@smkn2magelang.sch.id)');

        $this->command->info('');
        $this->command->info('=== Rangkuman State Siswa ===');
        $this->command->info('STATE 1 ('.$stateCounts['STATE 1'].' siswa): aktif PKL, ongoing, jurnal campuran, sertifikat draft.');
        $this->command->info('STATE 2 ('.$stateCounts['STATE 2'].' siswa): belum di-plot, tanpa data PKL, ada pengajuan industri pending oleh 90201.');
        $this->command->info('STATE 3 ('.$stateCounts['STATE 3'].' siswa): selesai PKL, jurnal verified, nilai lengkap, sebagian sertifikat validated.');
        $this->command->info('STATE 4 ('.$stateCounts['STATE 4'].' siswa): siswa baru tanpa data PKL.');
    }
}
