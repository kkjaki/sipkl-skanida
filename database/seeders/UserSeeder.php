<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\SupervisorAllocation;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passwordHash = Hash::make('password');
        $activeAcademicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $departments = Department::all()->keyBy('code');
        $faker = Factory::create('id_ID');

        // =================================================================
        // ADMIN & KURIKULUM
        // =================================================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkn2magelang.sch.id'],
            [
                'name' => 'Admin Humas',
                'password' => $passwordHash,
                'role' => 'admin',
            ]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $curriculum = User::firstOrCreate(
            ['email' => 'curriculum@smkn2magelang.sch.id'],
            [
                'name' => 'WKS Kurikulum',
                'password' => $passwordHash,
                'role' => 'curriculum',
            ]
        );
        if (! $curriculum->hasRole('curriculum')) {
            $curriculum->assignRole('curriculum');
        }

        // =================================================================
        // KEPALA PROGRAM (4 akun — 1 per program keahlian)
        // =================================================================
        $kaprogConfig = [
            ['dept' => 'PPLG', 'name' => 'Drs. Bambang Sudarsono, M.Kom', 'nip' => '197019801'],
            ['dept' => 'AKL',  'name' => 'Sutrisno, S.E, Akt',            'nip' => '197019802'],
            ['dept' => 'MPLB', 'name' => 'Dra. Endang Purwanti, M.M',     'nip' => '197019803'],
            ['dept' => 'PM',   'name' => 'Hj. Sri Wahyuni, S.Pd',         'nip' => '197019804'],
        ];

        foreach ($kaprogConfig as $config) {
            $department = $departments[$config['dept']] ?? null;
            if (! $department) {
                continue;
            }

            $email = strtolower($config['dept']).'@smkn2magelang.sch.id';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $config['name'],
                    'password' => $passwordHash,
                    'role' => 'department_head',
                ]
            );

            if (! $user->hasRole('department_head')) {
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

        // =================================================================
        // GURU PEMBIMBING (8 akun — 2 state × 4 program keahlian)
        // =================================================================
        $supervisorConfig = [
            // STATE 1: membimbing siswa STATE 1 (seq 01-04)
            ['seq' => '01', 'dept' => 'PPLG', 'name' => 'Drs. Agus Widodo, M.Pd'],
            ['seq' => '02', 'dept' => 'AKL',  'name' => 'Hj. Ratna Dewi, S.Pd'],
            ['seq' => '03', 'dept' => 'MPLB', 'name' => 'Ir. Hendra Saputra, M.T'],
            ['seq' => '04', 'dept' => 'PM',   'name' => 'Dra. Siti Aminah, M.M'],

            // STATE 2: membimbing siswa STATE 3 (seq 05-08)
            ['seq' => '05', 'dept' => 'PPLG', 'name' => 'Wahyu Prasetyo, S.Kom'],
            ['seq' => '06', 'dept' => 'AKL',  'name' => 'Nur Hidayati, S.E'],
            ['seq' => '07', 'dept' => 'MPLB', 'name' => 'Bambang Kurniawan, S.Pd'],
            ['seq' => '08', 'dept' => 'PM',   'name' => 'Fitri Handayani, S.Pd'],
        ];

        foreach ($supervisorConfig as $config) {
            $department = $departments[$config['dept']] ?? null;
            if (! $department) {
                continue;
            }

            $emailShort = '99'.$config['seq'];
            $email = $emailShort.'@smkn2magelang.sch.id';
            $nip = '19701'.$emailShort;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $config['name'],
                    'password' => $passwordHash,
                    'role' => 'supervisor',
                ]
            );

            if (! $user->hasRole('supervisor')) {
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
                    'academic_year_id' => $activeAcademicYear->id,
                ],
                ['quota' => 16]
            );
        }

        // =================================================================
        // STUDENTS (64 siswa): 4 state × 4 jurusan × 4 siswa
        // NIS scheme: 9SSDD (SS=state, DD=urutan global 01-16 per state)
        // =================================================================
        $deptOrder = ['PPLG', 'AKL', 'MPLB', 'PM'];
        $stateClasses = [
            1 => ['PPLG' => 'XII PPLG 1', 'AKL' => 'XII AKL 1', 'MPLB' => 'XII MPLB 1', 'PM' => 'XII PM 1'],
            2 => ['PPLG' => 'XII PPLG 2', 'AKL' => 'XII AKL 2', 'MPLB' => 'XII MPLB 2', 'PM' => 'XII PM 2'],
            3 => ['PPLG' => 'XII PPLG 3', 'AKL' => 'XII AKL 3', 'MPLB' => 'XII MPLB 3', 'PM' => 'XII PM 1'],
            4 => ['PPLG' => 'XII PPLG 1', 'AKL' => 'XII AKL 1', 'MPLB' => 'XII MPLB 1', 'PM' => 'XII PM 1'],
        ];

        for ($state = 1; $state <= 4; $state++) {
            foreach ($deptOrder as $deptIndex => $deptCode) {
                $department = $departments[$deptCode] ?? null;
                if (! $department) {
                    continue;
                }

                $orderStart = ($deptIndex * 4) + 1;

                for ($i = 0; $i < 4; $i++) {
                    $order = $orderStart + $i;
                    $nis = sprintf('9%02d%02d', $state, $order);
                    $email = $nis.'@smkn2magelang.sch.id';

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $faker->firstName.' '.$faker->lastName,
                            'password' => $passwordHash,
                            'role' => 'student',
                        ]
                    );

                    if (! $user->hasRole('student')) {
                        $user->assignRole('student');
                    }

                    Student::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'department_id' => $department->id,
                            'academic_year_id' => $activeAcademicYear->id,
                            'nis' => $nis,
                            'place_of_birth' => $faker->city,
                            'date_of_birth' => $faker->dateTimeBetween('-18 years', '-16 years')->format('Y-m-d'),
                            'class_name' => $stateClasses[$state][$deptCode],
                            'address' => $faker->address,
                            'phone' => $faker->phoneNumber,
                        ]
                    );
                }
            }
        }
    }
}
