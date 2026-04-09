<?php

namespace Database\Seeders;

use App\Models\DailyJournal;
use App\Models\Industry;
use App\Models\IndustryPartnership;
use App\Models\Internship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyJournalSeeder extends Seeder
{
    /**
     * STATE 1: 14 jurnal per siswa (mix verified/pending/rejected + sick + excused)
     * STATE 3: 10 jurnal per siswa (semua verified: 8 present + 1 sick + 1 excused)
     */
    public function run(): void
    {
        $firstIndustry = Industry::where('email', 'intern@telkom.co.id')->first();
        $mou = $firstIndustry
            ? IndustryPartnership::where('industry_id', $firstIndustry->id)->first()
            : null;

        $mouStartDate = $mou
            ? Carbon::parse($mou->start_date)
            : Carbon::now()->subMonths(4);

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

        $state1NisList = [];
        for ($seq = 1; $seq <= 16; $seq++) {
            $state1NisList[] = sprintf('9%02d%02d', 1, $seq);
        }

        foreach ($state1NisList as $nis) {
            $studentUser = User::where('email', $nis.'@smkn2magelang.sch.id')->first();
            if (! $studentUser) {
                continue;
            }

            $internship = Internship::where('student_id', $studentUser->id)->first();
            if (! $internship) {
                continue;
            }

            $dates = $this->generateWeekdayDates($mouStartDate->copy()->addDays(2), 14);
            $entries = [];

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

            $entries[] = [
                'date' => $dates[12],
                'activity' => null,
                'status_attendance' => 'sick',
                'verification_status' => 'verified',
                'verified_at' => $dates[12]->copy()->addDay()->setHour(9)->setMinute(30),
                'rejection_note' => null,
            ];

            $entries[] = [
                'date' => $dates[13],
                'activity' => null,
                'status_attendance' => 'excused',
                'verification_status' => 'verified',
                'verified_at' => $dates[13]->copy()->addDay()->setHour(9)->setMinute(45),
                'rejection_note' => null,
            ];

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

        $state3NisList = [];
        for ($seq = 1; $seq <= 16; $seq++) {
            $state3NisList[] = sprintf('9%02d%02d', 3, $seq);
        }

        foreach ($state3NisList as $nis) {
            $studentUser = User::where('email', $nis.'@smkn2magelang.sch.id')->first();
            if (! $studentUser) {
                continue;
            }

            $internship = Internship::where('student_id', $studentUser->id)->first();
            if (! $internship) {
                continue;
            }

            $dates = $this->generateWeekdayDates($mouStartDate->copy()->addDays(2), 10);
            $entries = [];

            for ($i = 0; $i < 8; $i++) {
                $entries[] = [
                    'date' => $dates[$i],
                    'activity' => $activities[$i],
                    'status_attendance' => 'present',
                    'verification_status' => 'verified',
                    'verified_at' => $dates[$i]->copy()->addDay()->setHour(9)->setMinute(rand(0, 59)),
                    'rejection_note' => null,
                ];
            }

            $entries[] = [
                'date' => $dates[8],
                'activity' => null,
                'status_attendance' => 'sick',
                'verification_status' => 'verified',
                'verified_at' => $dates[8]->copy()->addDay()->setHour(9)->setMinute(15),
                'rejection_note' => null,
            ];

            $entries[] = [
                'date' => $dates[9],
                'activity' => null,
                'status_attendance' => 'excused',
                'verification_status' => 'verified',
                'verified_at' => $dates[9]->copy()->addDay()->setHour(9)->setMinute(25),
                'rejection_note' => null,
            ];

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
