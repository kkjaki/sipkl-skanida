<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\IndustryPartnership;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestingIndustryPartnershipSeeder extends Seeder
{
    /**
     * MoU untuk 3 industri verified.
     * start_date: 7 bulan lalu → end_date: 5 bulan kedepan (total 12 bulan).
     * Industri pending TIDAK mendapat MoU.
     */
    public function run(): void
    {
        $verifiedEmails = [
            'testing.intern@telkom.co.id',
            'testing.magang@bri.co.id',
            'testing.hr@nusantaradigital.id',
        ];

        $startDate = Carbon::now()->subMonths(7)->startOfDay();
        $endDate = Carbon::now()->addMonths(5)->startOfDay();

        $seq = 1;
        foreach ($verifiedEmails as $email) {
            $industry = Industry::where('email', $email)->first();
            if (!$industry) {
                continue;
            }

            IndustryPartnership::firstOrCreate(
                [
                    'industry_id' => $industry->id,
                    'document_number' => 'MoU/TESTING/' . date('Y') . '/' . str_pad($seq, 4, '0', STR_PAD_LEFT),
                ],
                [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'mou_file_path' => null,
                    'agreement_notes' => 'MoU testing untuk pengujian sistem.',
                ]
            );

            $seq++;
        }

        $this->command->info('✅ TestingIndustryPartnershipSeeder: 3 MoU aktif seeded.');
    }
}
