<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\IndustryPartnership;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IndustryPartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = Industry::where('is_synced', true)->get();

        $startDate = Carbon::now()->subMonths(4)->startOfDay();
        $endDate = Carbon::now()->addMonths(2)->startOfDay();

        $seq = 1;
        foreach ($industries as $industry) {
            IndustryPartnership::firstOrCreate(
                [
                    'industry_id' => $industry->id,
                    'document_number' => 'MoU/SKANIDA/'.date('Y').'/'.str_pad($seq, 4, '0', STR_PAD_LEFT),
                ],
                [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'mou_file_path' => null,
                    'agreement_notes' => 'MoU aktif untuk kegiatan PKL (durasi 6 bulan).',
                ]
            );

            $seq++;
        }
    }
}
