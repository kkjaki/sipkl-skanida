<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Certificate;
use App\Models\Industry;
use App\Models\IndustryPartnership;
use App\Models\Internship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $deptCodes = ['PPLG', 'AKL', 'MPLB', 'PM'];

        $industryEmails = [
            'intern@telkom.co.id',
            'magang@bri.co.id',
            'hr@nusantaradigital.id',
        ];
        $industries = Industry::whereIn('email', $industryEmails)->get();
        if ($industries->isEmpty()) {
            return;
        }

        $mouStartDate = IndustryPartnership::whereIn('industry_id', $industries->pluck('id'))
            ->orderBy('start_date')
            ->value('start_date');

        $internshipStartDate = $mouStartDate
            ? Carbon::parse($mouStartDate)->addDays(2)
            : Carbon::now()->subMonths(2);

        $guruState1Map = [
            'PPLG' => '9901@smkn2magelang.sch.id',
            'AKL' => '9902@smkn2magelang.sch.id',
            'MPLB' => '9903@smkn2magelang.sch.id',
            'PM' => '9904@smkn2magelang.sch.id',
        ];

        $guruState3Map = [
            'PPLG' => '9905@smkn2magelang.sch.id',
            'AKL' => '9906@smkn2magelang.sch.id',
            'MPLB' => '9907@smkn2magelang.sch.id',
            'PM' => '9908@smkn2magelang.sch.id',
        ];

        $industryList = $industries->values();
        $validatedQuota = 5;
        $validatedCount = 0;

        $states = [
            1 => [
                'status' => 'ongoing',
                'supervisorMap' => $guruState1Map,
                'end_date' => null,
            ],
            3 => [
                'status' => 'finished',
                'supervisorMap' => $guruState3Map,
                'end_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
            ],
        ];

        foreach ($states as $stateNum => $stateConfig) {
            foreach ($deptCodes as $deptIndex => $deptCode) {
                $supervisorUser = User::where('email', $stateConfig['supervisorMap'][$deptCode])->first();
                if (! $supervisorUser) {
                    continue;
                }

                $orderStart = ($deptIndex * 4) + 1;

                for ($i = 0; $i < 4; $i++) {
                    $order = $orderStart + $i;
                    $nis = sprintf('9%02d%02d', $stateNum, $order);
                    $studentUser = User::where('email', $nis.'@smkn2magelang.sch.id')->first();
                    if (! $studentUser) {
                        continue;
                    }

                    $industry = $industryList[($deptIndex + $i) % $industryList->count()];

                    $internship = Internship::firstOrCreate(
                        [
                            'student_id' => $studentUser->id,
                            'academic_year_id' => $activeYear->id,
                        ],
                        [
                            'industry_id' => $industry->id,
                            'supervisor_id' => $supervisorUser->id,
                            'start_date' => $internshipStartDate->format('Y-m-d'),
                            'actual_end_date' => $stateConfig['end_date'],
                            'status' => $stateConfig['status'],
                        ]
                    );

                    $certificateStatus = 'draft';
                    $certificateData = ['status' => $certificateStatus];

                    if ($stateNum === 3 && $validatedCount < $validatedQuota) {
                        $validatedCount++;
                        $certificateData = [
                            'status' => 'validated',
                            'certificate_number' => sprintf('SERT/PKL/%d/%03d', date('Y'), $validatedCount),
                            'issued_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                        ];
                    }

                    Certificate::updateOrCreate(
                        ['internship_id' => $internship->id],
                        $certificateData
                    );
                }
            }
        }
    }
}
