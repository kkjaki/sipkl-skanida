<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Industry;
use App\Models\IndustryAllocation;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $verifiedIndustries = [
            [
                'name' => 'PT Telkom Indonesia Tbk',
                'address' => 'Jl. Japaris No.1, Kota Bandung',
                'city' => 'Bandung',
                'contact_person' => 'Rudi Hartono',
                'email' => 'intern@telkom.co.id',
                'phone' => '0227123456',
                'pic_name' => 'Ika Permatasari',
                'pic_position' => 'HR Manager',
            ],
            [
                'name' => 'Bank BRI Cabang Magelang',
                'address' => 'Jl. Pemuda No.20, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Sari Wulandari',
                'email' => 'magang@bri.co.id',
                'phone' => '0293321456',
                'pic_name' => 'Agung Prabowo',
                'pic_position' => 'Pimpinan Cabang',
            ],
            [
                'name' => 'CV Nusantara Digital',
                'address' => 'Jl. Tidar No.55, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Dian Permana',
                'email' => 'hr@nusantaradigital.id',
                'phone' => '0293365789',
                'pic_name' => 'Faisal Rahman',
                'pic_position' => 'Direktur Operasional',
            ],
        ];

        foreach ($verifiedIndustries as $data) {
            Industry::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'student_submitter_id' => null,
                    'is_synced' => true,
                    'status' => 'open',
                ])
            );
        }

        $state2NisList = [];
        for ($seq = 1; $seq <= 16; $seq++) {
            $state2NisList[] = sprintf('9%02d%02d', 2, $seq);
        }

        foreach ($state2NisList as $index => $nis) {
            $submitter = \App\Models\User::where('email', $nis.'@smkn2magelang.sch.id')->first();
            if (! $submitter) {
                continue;
            }

            $company = $faker->unique()->company;
            $companySlug = strtolower(preg_replace('/[^a-z0-9]+/i', '', $company));
            if ($companySlug === '') {
                $companySlug = 'perusahaan';
            }
            $address = $faker->streetAddress;
            $city = $faker->city;
            $contactPerson = $faker->name;
            $phone = $faker->numerify('08#########');
            $email = 'kontak.'.$nis.'@'.$companySlug.'.co.id';

            Industry::firstOrCreate(
                ['email' => $email],
                [
                    'student_submitter_id' => $submitter->id,
                    'name' => $company,
                    'address' => $address,
                    'city' => $city,
                    'contact_person' => $contactPerson,
                    'email' => $email,
                    'phone' => $phone,
                    'pic_name' => null,
                    'pic_position' => null,
                    'is_synced' => false,
                    'status' => 'open',
                ]
            );
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $departments = Department::orderBy('code')->get();

        if (! $activeYear || $departments->isEmpty()) {
            return;
        }

        $quotaByCode = [
            'PPLG' => 5,
            'AKL' => 5,
            'MPLB' => 5,
            'PM' => 3,
        ];

        $verifiedIndustries = Industry::where('is_synced', true)->get();
        foreach ($verifiedIndustries as $industry) {
            foreach ($departments as $department) {
                $quota = $quotaByCode[$department->code] ?? 0;

                if ($quota <= 0) {
                    continue;
                }

                IndustryAllocation::updateOrCreate(
                    [
                        'industry_id' => $industry->id,
                        'department_id' => $department->id,
                        'academic_year_id' => $activeYear->id,
                    ],
                    ['quota' => $quota]
                );
            }
        }
    }
}
