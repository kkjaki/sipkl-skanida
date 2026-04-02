<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Industry;
use App\Models\IndustryAllocation;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestingIndustrySeeder extends Seeder
{
    /**
     * 4 industri testing:
     * - 3 industri verified + synced (untuk STATE 1 & STATE 3)
     * - 1 industri pengajuan pending dari siswa STATE 2
     */
    public function run(): void
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $departments = Department::all()->keyBy('code');

        // ---------------------------------------------------------------
        // 3 Industri Verified
        // ---------------------------------------------------------------
        $verifiedIndustries = [
            [
                'name' => 'PT Telkom Indonesia Tbk (Testing)',
                'address' => 'Jl. Japaris No.1, Kota Bandung',
                'city' => 'Bandung',
                'contact_person' => 'Rudi Hartono',
                'email' => 'testing.intern@telkom.co.id',
                'phone' => '0227123456',
                'pic_name' => 'Ika Permatasari',
                'pic_position' => 'HR Manager',
            ],
            [
                'name' => 'Bank BRI Cabang Magelang (Testing)',
                'address' => 'Jl. Pemuda No.20, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Sari Wulandari',
                'email' => 'testing.magang@bri.co.id',
                'phone' => '0293321456',
                'pic_name' => 'Agung Prabowo',
                'pic_position' => 'Pimpinan Cabang',
            ],
            [
                'name' => 'CV Nusantara Digital (Testing)',
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
            $industry = Industry::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'student_submitter_id' => null,
                    'is_synced' => true,
                    'status' => 'open',
                ])
            );

            // Allocations: quota 5 per program keahlian
            foreach ($departments as $deptCode => $department) {
                IndustryAllocation::firstOrCreate(
                    [
                        'industry_id' => $industry->id,
                        'department_id' => $department->id,
                        'academic_year_id' => $activeYear->id,
                    ],
                    ['quota' => 5]
                );
            }
        }

        // ---------------------------------------------------------------
        // 1 Industri Pengajuan Pending (dari siswa STATE 2)
        // ---------------------------------------------------------------
        // Ambil siswa STATE 2 pertama (NIS 90201) sebagai pengaju
        $submitter = User::where('email', '90201@smkn2magelang.sch.id')->first();

        Industry::firstOrCreate(
            ['email' => 'testing.kontak@tokojayamakmur.com'],
            [
                'student_submitter_id' => $submitter?->id,
                'name' => 'Toko Jaya Makmur (Testing - Pending)',
                'address' => 'Jl. Soekarno-Hatta No.123, Magelang',
                'city' => 'Magelang',
                'contact_person' => 'Andi Susanto',
                'email' => 'testing.kontak@tokojayamakmur.com',
                'phone' => '0293367890',
                'pic_name' => 'Andi Susanto',
                'pic_position' => 'Pemilik',
                'is_synced' => false,
                'status' => 'open',
            ]
        );

        $this->command->info('✅ TestingIndustrySeeder: 3 verified + 1 pending industry seeded.');
    }
}
