<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingStudentSeeder extends Seeder
{
    /**
     * 32 siswa testing: 4 state × 4 program keahlian × 2 siswa per program keahlian.
     *
     * NIS scheme: 9SSDD
     *   SS = state (01-04)
     *   DD = urutan (01-08)
     *
     * Login: {NIS}@smkn2magelang.sch.id / password
     */
    public function run(): void
    {
        $passwordHash = Hash::make('password');
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $departments = Department::all()->keyBy('code');

        $faker = \Faker\Factory::create('id_ID');

        // State config: state_number => class mapping per department
        $states = [
            1 => ['label' => 'Aktif PKL',           'classes' => ['PPLG' => 'XII PPLG 1', 'AKL' => 'XII AKL 1', 'MPLB' => 'XII MPLB 1', 'PM' => 'XII PM 1']],
            2 => ['label' => 'Belum Di-plot',        'classes' => ['PPLG' => 'XII PPLG 2', 'AKL' => 'XII AKL 2', 'MPLB' => 'XII MPLB 2', 'PM' => 'XII PM 2']],
            3 => ['label' => 'Siap Validasi Sertif', 'classes' => ['PPLG' => 'XII PPLG 3', 'AKL' => 'XII AKL 3', 'MPLB' => 'XII MPLB 3', 'PM' => 'XII PM 1']],
            4 => ['label' => 'Baru',                 'classes' => ['PPLG' => 'XII PPLG 1', 'AKL' => 'XII AKL 1', 'MPLB' => 'XII MPLB 1', 'PM' => 'XII PM 1']],
        ];

        $deptCodes = ['PPLG', 'AKL', 'MPLB', 'PM'];

        foreach ($states as $stateNum => $stateConfig) {
            $seq = 1;

            foreach ($deptCodes as $deptCode) {
                $department = $departments[$deptCode] ?? null;
                if (!$department) {
                    continue;
                }

                // 2 siswa per program keahlian per state
                for ($i = 0; $i < 2; $i++) {
                    $nis = sprintf('9%02d%02d', $stateNum, $seq);
                    $email = $nis . '@smkn2magelang.sch.id';

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $faker->firstName . ' ' . $faker->lastName,
                            'password' => $passwordHash,
                            'role' => 'student',
                        ]
                    );

                    if (!$user->hasRole('student')) {
                        $user->assignRole('student');
                    }

                    Student::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'department_id' => $department->id,
                            'academic_year_id' => $activeYear->id,
                            'nis' => $nis,
                            'place_of_birth' => $faker->city,
                            'date_of_birth' => $faker->dateTimeBetween('-18 years', '-16 years')->format('Y-m-d'),
                            'class_name' => $stateConfig['classes'][$deptCode],
                            'address' => $faker->address,
                            'phone' => $faker->phoneNumber,
                        ]
                    );

                    $seq++;
                }
            }
        }

        $this->command->info('✅ TestingStudentSeeder: 32 siswa (4 state × 8) seeded.');
    }
}
