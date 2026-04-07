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
        $verifiedIndustries = [
            [
                'name' => 'PT Telkom Indonesia Tbk',
                'address' => 'Jl. Japaris No.1, Kota Bandung',
                'city' => 'Bandung',
                'contact_person' => 'Rudi Hartono',
                'email' => 'testing.intern@telkom.co.id',
                'phone' => '0227123456',
                'pic_name' => 'Ika Permatasari',
                'pic_position' => 'HR Manager',
            ],
            [
                'name' => 'Bank BRI Cabang Magelang',
                'address' => 'Jl. Pemuda No.20, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Sari Wulandari',
                'email' => 'testing.magang@bri.co.id',
                'phone' => '0293321456',
                'pic_name' => 'Agung Prabowo',
                'pic_position' => 'Pimpinan Cabang',
            ],
            [
                'name' => 'CV Nusantara Digital',
                'address' => 'Jl. Tidar No.55, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Dian Permana',
                'email' => 'testing.hr@nusantaradigital.id',
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

        // Industri pending: pengajuan dari siswa NIS 90201 tanpa data PIC dan tanpa alokasi
        $submitter = \App\Models\User::where('email', '90201@smkn2magelang.sch.id')->first();

        Industry::firstOrCreate(
            ['email' => 'testing.kontak@tokojayamakmur.com'],
            [
                'student_submitter_id' => $submitter?->id,
                'name' => 'Toko Jaya Makmur (Pending)',
                'address' => 'Jl. Soekarno-Hatta No.123, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Andi Susanto',
                'email' => 'testing.kontak@tokojayamakmur.com',
                'phone' => '0293367890',
                'pic_name' => null,
                'pic_position' => null,
                'is_synced' => false,
                'status' => 'open',
            ]
        );

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
