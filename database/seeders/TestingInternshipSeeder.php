<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Certificate;
use App\Models\Department;
use App\Models\Industry;
use App\Models\IndustryPartnership;
use App\Models\Internship;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestingInternshipSeeder extends Seeder
{
    /**
     * Internship untuk STATE 1 (ongoing) dan STATE 3 (finished).
     *
     * STATE 1: 8 siswa → ongoing, dibimbing guru STATE 1 (seq 01-04)
     * STATE 3: 8 siswa → finished, dibimbing guru STATE 2 (seq 05-08)
     *
     * STATE 2 & STATE 4: tidak dibuat internship.
     */
    public function run(): void
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $departments = Department::all()->keyBy('code');

        // Resolve verified industries
        $industries = [
            Industry::where('email', 'testing.intern@telkom.co.id')->first(),
            Industry::where('email', 'testing.magang@bri.co.id')->first(),
            Industry::where('email', 'testing.hr@nusantaradigital.id')->first(),
        ];

        // Get MoU start date for date calculations
        $mouStartDate = IndustryPartnership::where('industry_id', $industries[0]->id)->first()->start_date;
        $internshipStartDate = Carbon::parse($mouStartDate)->addDays(2); // hari ketiga MoU

        $deptCodes = ['PPLG', 'AKL', 'MPLB', 'PM'];

        // ---------------------------------------------------------------
        // STATE 1: ongoing internship (siswa NIS 901XX, guru seq 01-04)
        // ---------------------------------------------------------------
        $guruState1Map = [
            'PPLG' => '9901@smkn2magelang.sch.id',
            'AKL'  => '9902@smkn2magelang.sch.id',
            'MPLB' => '9903@smkn2magelang.sch.id',
            'PM'   => '9904@smkn2magelang.sch.id',
        ];

        $state1Seq = 1;
        foreach ($deptCodes as $idx => $deptCode) {
            $supervisorUser = User::where('email', $guruState1Map[$deptCode])->first();
            if (!$supervisorUser) {
                continue;
            }

            // Alternate between industry 0 and 1 for variety
            $industry = $industries[$idx % 2];

            for ($i = 0; $i < 2; $i++) {
                $nis = sprintf('9%02d%02d', 1, $state1Seq);
                $studentUser = User::where('email', $nis . '@smkn2magelang.sch.id')->first();
                if (!$studentUser) {
                    $state1Seq++;
                    continue;
                }

                $internship = Internship::firstOrCreate(
                    [
                        'student_id' => $studentUser->id,
                        'academic_year_id' => $activeYear->id,
                    ],
                    [
                        'industry_id' => $industry->id,
                        'supervisor_id' => $supervisorUser->id,
                        'start_date' => $internshipStartDate->format('Y-m-d'),
                        'actual_end_date' => null,
                        'status' => 'ongoing',
                    ]
                );

                // Create certificate (draft for ongoing)
                Certificate::firstOrCreate(
                    ['internship_id' => $internship->id],
                    ['status' => 'draft']
                );

                $state1Seq++;
            }
        }

        // ---------------------------------------------------------------
        // STATE 3: finished internship (siswa NIS 903XX, guru seq 05-08)
        // ---------------------------------------------------------------
        $guruState2Map = [
            'PPLG' => '9905@smkn2magelang.sch.id',
            'AKL'  => '9906@smkn2magelang.sch.id',
            'MPLB' => '9907@smkn2magelang.sch.id',
            'PM'   => '9908@smkn2magelang.sch.id',
        ];

        $finishedEndDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $state3Seq = 1;
        foreach ($deptCodes as $idx => $deptCode) {
            $supervisorUser = User::where('email', $guruState2Map[$deptCode])->first();
            if (!$supervisorUser) {
                continue;
            }

            // Alternate between industry 1 and 2 for variety
            $industry = $industries[($idx % 2) + 1];

            for ($i = 0; $i < 2; $i++) {
                $nis = sprintf('9%02d%02d', 3, $state3Seq);
                $studentUser = User::where('email', $nis . '@smkn2magelang.sch.id')->first();
                if (!$studentUser) {
                    $state3Seq++;
                    continue;
                }

                $internship = Internship::firstOrCreate(
                    [
                        'student_id' => $studentUser->id,
                        'academic_year_id' => $activeYear->id,
                    ],
                    [
                        'industry_id' => $industry->id,
                        'supervisor_id' => $supervisorUser->id,
                        'start_date' => $internshipStartDate->format('Y-m-d'),
                        'actual_end_date' => $finishedEndDate,
                        'status' => 'finished',
                    ]
                );

                // Create certificate (draft — will be updated by CertificateSeeder)
                Certificate::firstOrCreate(
                    ['internship_id' => $internship->id],
                    ['status' => 'draft']
                );

                $state3Seq++;
            }
        }

        $this->command->info('✅ TestingInternshipSeeder: 16 internships (8 ongoing + 8 finished) seeded.');
    }
}
