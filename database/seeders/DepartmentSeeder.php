<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'code' => 'PPLG',
                'name' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'code' => 'AKL',
                'name' => 'Akuntansi dan Keuangan Lembaga',
            ],
            [
                'code' => 'MPLB',
                'name' => 'Manajemen Perkantoran dan Layanan Bisnis',
            ],
            [
                'code' => 'PM',
                'name' => 'Pemasaran',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['code' => $department['code']], $department);
        }
    }
}
