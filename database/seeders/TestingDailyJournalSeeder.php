<?php

namespace Database\Seeders;

use App\Models\DailyJournal;
use App\Models\Industry;
use App\Models\IndustryPartnership;
use App\Models\Internship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestingDailyJournalSeeder extends Seeder
{
    /**
     * 14 jurnal per siswa STATE 1 (8 siswa × 14 = 112 total).
     *
     * Distribusi per siswa:
     * - 5× verified (present, verified_at terisi)
     * - 4× pending (present)
     * - 3× rejected (present, rejection_note terisi)
     * - 1× sick (verified)
     * - 1× excused (verified)
     *
     * Tanggal: dari hari ke-3 setelah MoU start, weekdays only.
     */
    public function run(): void
    {
        // Get MoU start date from first testing industry
        $firstIndustry = Industry::where('email', 'testing.intern@telkom.co.id')->first();
        $mou = IndustryPartnership::where('industry_id', $firstIndustry->id)->first();
        $mouStartDate = Carbon::parse($mou->start_date);

        // Journal activities pool
        $activities = [
            'Mempelajari alur kerja sistem administrasi perusahaan.',
            'Membantu menginput data pelanggan ke dalam sistem database.',
            'Mengikuti briefing pagi bersama tim HRD tentang SOP kerja.',
            'Merapikan arsip dokumen fisik dan digital di ruang administrasi.',
            'Melakukan observasi proses bisnis di bagian operasional.',
            'Membantu menyusun laporan harian kegiatan tim produksi.',
            'Mengikuti pelatihan singkat penggunaan software ERP internal.',
            'Membantu verifikasi data stok barang di gudang.',
            'Mengerjakan tugas desain grafis untuk media sosial perusahaan.',
            'Menyusun notulensi rapat koordinasi dengan pihak supplier.',
            'Membantu proses input faktur pajak ke aplikasi e-Faktur.',
            'Mengikuti workshop keselamatan kerja (K3) di area produksi.',
            'Membuat presentasi ringkasan kegiatan PKL mingguan.',
            'Melakukan pengecekan dan maintenance komputer kantor.',
        ];

        // Resolve all STATE 1 student NIS
        $state1NisList = [];
        for ($seq = 1; $seq <= 8; $seq++) {
            $state1NisList[] = sprintf('9%02d%02d', 1, $seq);
        }

        foreach ($state1NisList as $nis) {
            $studentUser = User::where('email', $nis . '@smkn2magelang.sch.id')->first();
            if (!$studentUser) {
                continue;
            }

            $internship = Internship::where('student_id', $studentUser->id)->first();
            if (!$internship) {
                continue;
            }

            // Generate 14 weekday dates starting from MoU start + 2 days
            $dates = $this->generateWeekdayDates($mouStartDate->copy()->addDays(2), 14);

            // Build journal entries with specific distribution
            $entries = [];

            // 5× verified (present)
            for ($i = 0; $i < 5; $i++) {
                $entries[] = [
                    'date' => $dates[$i],
                    'activity' => $activities[$i],
                    'status_attendance' => 'present',
                    'verification_status' => 'verified',
                    'verified_at' => $dates[$i]->copy()->addDay()->setHour(10)->setMinute(rand(0, 59)),
                    'rejection_note' => null,
                ];
            }

            // 4× pending (present)
            for ($i = 5; $i < 9; $i++) {
                $entries[] = [
                    'date' => $dates[$i],
                    'activity' => $activities[$i],
                    'status_attendance' => 'present',
                    'verification_status' => 'pending',
                    'verified_at' => null,
                    'rejection_note' => null,
                ];
            }

            // 3× rejected (present)
            for ($i = 9; $i < 12; $i++) {
                $entries[] = [
                    'date' => $dates[$i],
                    'activity' => $activities[$i],
                    'status_attendance' => 'present',
                    'verification_status' => 'rejected',
                    'verified_at' => null,
                    'rejection_note' => 'Deskripsi kegiatan terlalu singkat, mohon diperjelas.',
                ];
            }

            // 1× sick (verified)
            $entries[] = [
                'date' => $dates[12],
                'activity' => null,
                'status_attendance' => 'sick',
                'verification_status' => 'verified',
                'verified_at' => $dates[12]->copy()->addDay()->setHour(9)->setMinute(30),
                'rejection_note' => null,
            ];

            // 1× excused (verified)
            $entries[] = [
                'date' => $dates[13],
                'activity' => null,
                'status_attendance' => 'excused',
                'verification_status' => 'verified',
                'verified_at' => $dates[13]->copy()->addDay()->setHour(9)->setMinute(45),
                'rejection_note' => null,
            ];

            // Insert all entries
            foreach ($entries as $entry) {
                DailyJournal::firstOrCreate(
                    [
                        'internship_id' => $internship->id,
                        'date' => $entry['date']->format('Y-m-d'),
                    ],
                    [
                        'activity' => $entry['activity'],
                        'status_attendance' => $entry['status_attendance'],
                        'verification_status' => $entry['verification_status'],
                        'attachment_path' => null,
                        'rejection_note' => $entry['rejection_note'],
                        'verified_at' => $entry['verified_at'],
                    ]
                );
            }
        }

        $this->command->info('✅ TestingDailyJournalSeeder: 112 jurnal (8 siswa × 14) seeded.');
    }

    /**
     * Generate N weekday-only dates starting from a given date.
     */
    private function generateWeekdayDates(Carbon $start, int $count): array
    {
        $dates = [];
        $current = $start->copy();

        while (count($dates) < $count) {
            if ($current->isWeekday()) {
                $dates[] = $current->copy();
            }
            $current->addDay();
        }

        return $dates;
    }
}
