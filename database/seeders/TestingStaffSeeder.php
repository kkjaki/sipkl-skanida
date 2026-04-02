<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Supervisor;
use App\Models\SupervisorAllocation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingStaffSeeder extends Seeder
{
    /**
     * 4 Kepala Program + 8 Guru Pembimbing testing.
     *
     * KAPROG (4 akun):
     *   Email: {kode_departemen}@smkn2magelang.sch.id (pplg@, akl@, mplb@, pm@)
     *   Role: department_head
     *
     * GURU PEMBIMBING (8 akun, 2 state × 4 program keahlian):
     *   NIP scheme: 19701{99XX}
     *   Email: 99XX@smkn2magelang.sch.id
     *   STATE 1 (seq 01-04): Guru dengan jurnal pending → membimbing siswa STATE 1
     *   STATE 2 (seq 05-08): Guru dengan penilaian belum lengkap → membimbing siswa STATE 3
     */
    public function run(): void
    {
        $passwordHash = Hash::make('password');
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $departments = Department::all()->keyBy('code');

        // =================================================================
        // ADMIN & KURIKULUM
        // =================================================================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@smkn2magelang.sch.id'],
            [
                'name' => 'Admin Humas',
                'password' => $passwordHash,
                'role' => 'admin',
            ]
        );
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        $curriculumUser = User::firstOrCreate(
            ['email' => 'curriculum@smkn2magelang.sch.id'],
            [
                'name' => 'WKS Kurikulum',
                'password' => $passwordHash,
                'role' => 'curriculum',
            ]
        );
        if (!$curriculumUser->hasRole('curriculum')) {
            $curriculumUser->assignRole('curriculum');
        }

        $this->command->info('✅ TestingStaffSeeder: Admin + Kurikulum seeded.');

        // =================================================================
        // KEPALA PROGRAM (4 akun — 1 per program keahlian)
        // Login: {kode_departemen}@smkn2magelang.sch.id / password
        // =================================================================
        $kaprogConfig = [
            ['dept' => 'PPLG', 'name' => 'Drs. Bambang Sudarsono, M.Kom', 'nip' => '197019801'],
            ['dept' => 'AKL',  'name' => 'Sutrisno, S.E, Akt',            'nip' => '197019802'],
            ['dept' => 'MPLB', 'name' => 'Dra. Endang Purwanti, M.M',     'nip' => '197019803'],
            ['dept' => 'PM',   'name' => 'Hj. Sri Wahyuni, S.Pd',         'nip' => '197019804'],
        ];

        foreach ($kaprogConfig as $config) {
            $department = $departments[$config['dept']] ?? null;
            if (!$department) {
                continue;
            }

            $email = strtolower($config['dept']) . '@smkn2magelang.sch.id';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $config['name'],
                    'password' => $passwordHash,
                    'role' => 'department_head',
                ]
            );

            if (!$user->hasRole('department_head')) {
                $user->assignRole('department_head');
            }

            Supervisor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $department->id,
                    'nip' => $config['nip'],
                ]
            );
        }

        $this->command->info('✅ TestingStaffSeeder: 4 Kaprog seeded.');

        // =================================================================
        // GURU PEMBIMBING (8 akun — 2 state × 4 program keahlian)
        // Login: 99XX@smkn2magelang.sch.id / password
        // =================================================================
        $supervisorConfig = [
            // STATE 1: Guru dengan Jurnal Pending (seq 01-04)
            ['seq' => '01', 'dept' => 'PPLG', 'name' => 'Drs. Agus Widodo, M.Pd'],
            ['seq' => '02', 'dept' => 'AKL',  'name' => 'Hj. Ratna Dewi, S.Pd'],
            ['seq' => '03', 'dept' => 'MPLB', 'name' => 'Ir. Hendra Saputra, M.T'],
            ['seq' => '04', 'dept' => 'PM',   'name' => 'Dra. Siti Aminah, M.M'],

            // STATE 2: Guru dengan Penilaian Belum Lengkap (seq 05-08)
            ['seq' => '05', 'dept' => 'PPLG', 'name' => 'Wahyu Prasetyo, S.Kom'],
            ['seq' => '06', 'dept' => 'AKL',  'name' => 'Nur Hidayati, S.E'],
            ['seq' => '07', 'dept' => 'MPLB', 'name' => 'Bambang Kurniawan, S.Pd'],
            ['seq' => '08', 'dept' => 'PM',   'name' => 'Fitri Handayani, S.Pd'],
        ];

        foreach ($supervisorConfig as $config) {
            $emailShort = '99' . $config['seq'];
            $nip = '19701' . $emailShort;
            $email = $emailShort . '@smkn2magelang.sch.id';

            $department = $departments[$config['dept']] ?? null;
            if (!$department) {
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $config['name'],
                    'password' => $passwordHash,
                    'role' => 'supervisor',
                ]
            );

            if (!$user->hasRole('supervisor')) {
                $user->assignRole('supervisor');
            }

            Supervisor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $department->id,
                    'nip' => $nip,
                ]
            );

            SupervisorAllocation::firstOrCreate(
                [
                    'supervisor_id' => $user->id,
                    'academic_year_id' => $activeYear->id,
                ],
                ['quota' => 10]
            );
        }

        $this->command->info('✅ TestingStaffSeeder: 8 guru (2 state × 4 program keahlian) seeded.');
    }
}
