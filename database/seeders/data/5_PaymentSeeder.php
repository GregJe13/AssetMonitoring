<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Payment seeder untuk semua kontrak di TestContractSeeder.
     *
     * Kolom: contract_id, amendment_id(null), period_number,
     *        due_date, paid_at, amount_due, amount_paid,
     *        payment_status, notes
     *
     * Status logic (per hari ini):
     *   - paid_at terisi → 'paid'
     *   - due_date < today & belum bayar → 'overdue'
     *   - due_date >= today & belum bayar → 'pending'
     */
    public function run(): void
    {
        $now    = Carbon::now();
        $today  = Carbon::today();
        $cutoff = Carbon::parse('2026-02-01'); // Semua sebelum tanggal ini dianggap paid
        $rows   = [];

        // Helper: tentukan status otomatis
        $st = function (string $dueDate, ?string $paidAt) use ($today): string {
            if ($paidAt) return 'paid';
            return Carbon::parse($dueDate)->lt($today) ? 'overdue' : 'pending';
        };

        // =================================================================
        // SEWA — LOKASI 77
        // =================================================================

        // #1 Kepolisian – 100% dimuka, Rp 67.567.568
        $rows[] = [
            'contract_id' => 1, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => null,
            'amount_due' => 67567568, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-01', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #2 PT CITRA – 100% dimuka, Rp 82.620.000
        $rows[] = [
            'contract_id' => 2, 'period_number' => 0,
            'due_date' => '2025-09-01', 'paid_at' => '2025-09-19',
            'amount_due' => 82620000, 'amount_paid' => 82620000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #3 PT Navitas – Per 3 bulan, 2024-09-01 s/d 2029-08-31 = 20 periode
        // Total Rp 26.028.217.200 / 20 = Rp 1.301.410.860/periode
        $navAmount = round(26028217200 / 20);
        $navDate   = Carbon::parse('2024-09-01');
        for ($p = 1; $p <= 20; $p++) {
            $due    = $navDate->toDateString();
            $isPaid = Carbon::parse($due)->lt($today);
            $rows[] = [
                'contract_id' => 3, 'period_number' => $p,
                'due_date' => $due,
                'paid_at' => $isPaid ? $due : null,
                'amount_due' => $navAmount,
                'amount_paid' => $isPaid ? $navAmount : 0,
                'payment_status' => $st($due, $isPaid ? $due : null),
                'notes' => "Per 3 bulan – periode {$p}",
            ];
            $navDate->addMonths(3);
        }

        // #4 YPKRI – 100% dimuka, Rp 2.027.027.027
        $rows[] = [
            'contract_id' => 4, 'period_number' => 0,
            'due_date' => '2025-06-25', 'paid_at' => '2025-06-25',
            'amount_due' => 2027027027, 'amount_paid' => 2027027027,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #5 PT Inti Pindad – 100% dimuka, Rp 156.000.000, expired
        $rows[] = [
            'contract_id' => 5, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-01-10',
            'amount_due' => 156000000, 'amount_paid' => 156000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #6 PT Putra Telkom 1 – 100% dimuka, Rp 37.440.000
        $rows[] = [
            'contract_id' => 6, 'period_number' => 0,
            'due_date' => '2025-04-25', 'paid_at' => '2025-05-27',
            'amount_due' => 37440000, 'amount_paid' => 37440000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #7 PT Putra Telkom 2 – 100% dimuka, Rp 8.970.000
        $rows[] = [
            'contract_id' => 7, 'period_number' => 0,
            'due_date' => '2025-11-01', 'paid_at' => '2025-11-07',
            'amount_due' => 8970000, 'amount_paid' => 8970000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #8 PT MBIP – 100% dimuka, Rp 27.000.000, expired
        $rows[] = [
            'contract_id' => 8, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-11-10',
            'amount_due' => 27000000, 'amount_paid' => 27000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #9 GAFRELLY – 100% diperhitungkan dgn nilai IPK, Rp 23.400.000, expired
        $rows[] = [
            'contract_id' => 9, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-08-01',
            'amount_due' => 23400000, 'amount_paid' => 23400000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // #10 MGIU – 100% dimuka, Rp 47.848.320
        $rows[] = [
            'contract_id' => 10, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => '2026-01-20',
            'amount_due' => 47848320, 'amount_paid' => 47848320,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #11 PT IBP Lt3 – 50% dimuka + 50% balancing, Rp 28.860.000
        $rows[] = [
            'contract_id' => 11, 'period_number' => 1,
            'due_date' => '2024-11-01', 'paid_at' => '2025-02-17',
            'amount_due' => 14430000, 'amount_paid' => 14430000,
            'payment_status' => 'paid',
            'notes' => '50% dibayar dimuka',
        ];
        $rows[] = [
            'contract_id' => 11, 'period_number' => 2,
            'due_date' => '2025-10-31', 'paid_at' => null,
            'amount_due' => 14430000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '50% balancing – Proses BAK',
        ];

        // #12 PT IBP Lt2 – Balancing, Rp 88.164.000
        $rows[] = [
            'contract_id' => 12, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => null,
            'amount_due' => 88164000, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-01', null),
            'notes' => 'Balancing',
        ];

        // #13 PT IKI – Per Tahun, 2025-02-03 s/d 2027-02-02 = 2 periode
        // Total Rp 528.984.000 / 2 = Rp 264.492.000/tahun
        $rows[] = [
            'contract_id' => 13, 'period_number' => 1,
            'due_date' => '2025-02-03', 'paid_at' => '2025-02-10',
            'amount_due' => 264492000, 'amount_paid' => 264492000,
            'payment_status' => 'paid',
            'notes' => 'Per tahun – periode 1',
        ];
        $rows[] = [
            'contract_id' => 13, 'period_number' => 2,
            'due_date' => '2026-02-03', 'paid_at' => null,
            'amount_due' => 264492000, 'amount_paid' => 0,
            'payment_status' => $st('2026-02-03', null),
            'notes' => 'Per tahun – periode 2',
        ];

        // #14 PT Dayamitra – 100% dimuka, Rp 1.000.000.000
        $rows[] = [
            'contract_id' => 14, 'period_number' => 0,
            'due_date' => '2023-01-02', 'paid_at' => '2023-03-13',
            'amount_due' => 1000000000, 'amount_paid' => 1000000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #15 PT EPID – 100% dimuka, Rp 115.133.200
        $rows[] = [
            'contract_id' => 15, 'period_number' => 0,
            'due_date' => '2023-06-05', 'paid_at' => '2022-10-06',
            'amount_due' => 115133200, 'amount_paid' => 115133200,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #16 PT Mega Akses – 100% dimuka, Rp 9.600.000
        $rows[] = [
            'contract_id' => 16, 'period_number' => 0,
            'due_date' => '2025-08-23', 'paid_at' => '2025-10-02',
            'amount_due' => 9600000, 'amount_paid' => 9600000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #17 PT WADMA – 100% dimuka, Rp 1.440.000
        $rows[] = [
            'contract_id' => 17, 'period_number' => 0,
            'due_date' => '2025-05-09', 'paid_at' => '2025-05-19',
            'amount_due' => 1440000, 'amount_paid' => 1440000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #18 Joni Wibowo – 100% diperhitungkan dgn IPK, Rp 17.160.000
        $rows[] = [
            'contract_id' => 18, 'period_number' => 0,
            'due_date' => '2025-04-15', 'paid_at' => '2025-04-14',
            'amount_due' => 17160000, 'amount_paid' => 17160000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // #19 Ayi Dadi – 100% diperhitungkan dgn IPK, Rp 21.060.000
        $rows[] = [
            'contract_id' => 19, 'period_number' => 0,
            'due_date' => '2025-04-15', 'paid_at' => '2025-04-14',
            'amount_due' => 21060000, 'amount_paid' => 21060000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // #20 YAYASAN DKI – GKL AC – Per tahun, 2025-03-01 s/d 2030-02-28 = 5 periode
        // Total Rp 475.000.000 / 5 = Rp 95.000.000/tahun
        $dkiAcDate = Carbon::parse('2025-03-01');
        for ($p = 1; $p <= 5; $p++) {
            $due    = $dkiAcDate->toDateString();
            $isPaid = $p === 1; // periode 1 sudah dibayar
            $rows[] = [
                'contract_id' => 20, 'period_number' => $p,
                'due_date' => $due,
                'paid_at' => $isPaid ? '2025-03-10' : null,
                'amount_due' => 95000000,
                'amount_paid' => $isPaid ? 95000000 : 0,
                'payment_status' => $isPaid ? 'paid' : $st($due, null),
                'notes' => "Per tahun – periode {$p}",
            ];
            $dkiAcDate->addYear();
        }

        // #21 YAYASAN DKI – GKL AD – Per tahun, 2024-11-01 s/d 2029-10-31 = 5 periode
        // Total Rp 1.000.000.000 / 5 = Rp 200.000.000/tahun
        $dkiAdDate = Carbon::parse('2024-11-01');
        for ($p = 1; $p <= 5; $p++) {
            $due    = $dkiAdDate->toDateString();
            $isPaid = $p === 1;
            $rows[] = [
                'contract_id' => 21, 'period_number' => $p,
                'due_date' => $due,
                'paid_at' => $isPaid ? '2024-10-15' : null,
                'amount_due' => 200000000,
                'amount_paid' => $isPaid ? 200000000 : 0,
                'payment_status' => $isPaid ? 'paid' : $st($due, null),
                'notes' => "Per tahun – periode {$p}",
            ];
            $dkiAdDate->addYear();
        }

        // #22 WBI Gd D Poli – 100% dimuka realisasi balancing, Rp 787.158.000
        $rows[] = [
            'contract_id' => 22, 'period_number' => 0,
            'due_date' => '2023-01-01', 'paid_at' => '2023-03-13',
            'amount_due' => 787158000, 'amount_paid' => 787158000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka realisasi balancing',
        ];

        // #23 WBI Gd AA Lab – Balancing per tahun, 2021-12-01 s/d 2026-11-30 = 5 periode
        // Total Rp 262.400.000 / 5 = Rp 52.480.000/tahun
        $wbiAaDate = Carbon::parse('2021-12-01');
        for ($p = 1; $p <= 5; $p++) {
            $due    = $wbiAaDate->toDateString();
            $isPaid = Carbon::parse($due)->lt($today);
            $rows[] = [
                'contract_id' => 23, 'period_number' => $p,
                'due_date' => $due,
                'paid_at' => $isPaid ? $due : null,
                'amount_due' => 52480000,
                'amount_paid' => $isPaid ? 52480000 : 0,
                'payment_status' => $isPaid ? 'paid' : $st($due, null),
                'notes' => "Balancing per tahun – periode {$p}",
            ];
            $wbiAaDate->addYear();
        }

        // #24 WBI Gd D Apotik – Balancing, Rp 30.801.600
        $rows[] = [
            'contract_id' => 24, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => null,
            'amount_due' => 30801600, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-01', null),
            'notes' => 'Balancing',
        ];

        // #25 WBI Gd V Gudang – Balancing, Rp 10.800.000, expired
        $rows[] = [
            'contract_id' => 25, 'period_number' => 0,
            'due_date' => '2025-01-02', 'paid_at' => null,
            'amount_due' => 10800000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => 'Balancing – Konfirmasi Perpanjangan',
        ];

        // #26 PT TARGET MEDIA – Per Tahun, 2025-09-08 s/d 2027-09-07 = 2 periode
        // Total Rp 22.394.880 / 2 = Rp 11.197.440/tahun
        $rows[] = [
            'contract_id' => 26, 'period_number' => 1,
            'due_date' => '2025-09-08', 'paid_at' => null,
            'amount_due' => 11197440, 'amount_paid' => 0,
            'payment_status' => $st('2025-09-08', null),
            'notes' => 'Per tahun – periode 1',
        ];
        $rows[] = [
            'contract_id' => 26, 'period_number' => 2,
            'due_date' => '2026-09-08', 'paid_at' => null,
            'amount_due' => 11197440, 'amount_paid' => 0,
            'payment_status' => $st('2026-09-08', null),
            'notes' => 'Per tahun – periode 2',
        ];

        // #27 BANK CIMB – 100% dimuka, Rp 148.500.000
        $rows[] = [
            'contract_id' => 27, 'period_number' => 0,
            'due_date' => '2023-12-30', 'paid_at' => '2024-01-31',
            'amount_due' => 148500000, 'amount_paid' => 148500000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #28 BANK BNI – 100% dimuka, Rp 96.000.000
        $rows[] = [
            'contract_id' => 28, 'period_number' => 0,
            'due_date' => '2024-07-02', 'paid_at' => '2024-07-11',
            'amount_due' => 96000000, 'amount_paid' => 96000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #29 BANK OCBC – 100% dimuka, Rp 151.200.000
        $rows[] = [
            'contract_id' => 29, 'period_number' => 0,
            'due_date' => '2025-12-31', 'paid_at' => null,
            'amount_due' => 151200000, 'amount_paid' => 0,
            'payment_status' => $st('2025-12-31', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #30 BANK MANDIRI – 100% dimuka, Rp 48.000.000
        $rows[] = [
            'contract_id' => 30, 'period_number' => 0,
            'due_date' => '2025-12-13', 'paid_at' => null,
            'amount_due' => 48000000, 'amount_paid' => 0,
            'payment_status' => $st('2025-12-13', null),
            'notes' => '100% dibayar dimuka – Proses PKS',
        ];

        // =================================================================
        // SEWA — LOKASI 225
        // =================================================================

        // #31 PT IGOC – Termin 25/50/25, Rp 1.866.000.000
        $igoc = 1866000000;
        $rows[] = [
            'contract_id' => 31, 'period_number' => 1,
            'due_date' => '2025-04-13', 'paid_at' => '2025-06-10',
            'amount_due' => $igoc * 0.25, 'amount_paid' => $igoc * 0.25,
            'payment_status' => 'paid',
            'notes' => 'Termin 1 – 25%',
        ];
        $rows[] = [
            'contract_id' => 31, 'period_number' => 2,
            'due_date' => '2025-08-13', 'paid_at' => null,
            'amount_due' => $igoc * 0.50, 'amount_paid' => 0,
            'payment_status' => $st('2025-08-13', null),
            'notes' => 'Termin 2 – 50%',
        ];
        $rows[] = [
            'contract_id' => 31, 'period_number' => 3,
            'due_date' => '2026-04-12', 'paid_at' => null,
            'amount_due' => $igoc * 0.25, 'amount_paid' => 0,
            'payment_status' => $st('2026-04-12', null),
            'notes' => 'Termin 3 – 25%',
        ];

        // #32 PT YIMI (Utama) – 100% dimuka, Rp 414.720.000
        $rows[] = [
            'contract_id' => 32, 'period_number' => 0,
            'due_date' => '2025-11-16', 'paid_at' => '2025-12-03',
            'amount_due' => 414720000, 'amount_paid' => 414720000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #33 PT YIMI (Prafab) – 100% dimuka, Rp 174.900.000
        $rows[] = [
            'contract_id' => 33, 'period_number' => 0,
            'due_date' => '2025-11-10', 'paid_at' => '2025-12-03',
            'amount_due' => 174900000, 'amount_paid' => 174900000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #34 PT YIMI (Loading/Parkir) – 100% dimuka, Rp 29.800.000
        $rows[] = [
            'contract_id' => 34, 'period_number' => 0,
            'due_date' => '2025-12-03', 'paid_at' => null,
            'amount_due' => 29800000, 'amount_paid' => 0,
            'payment_status' => $st('2025-12-03', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #35 CV CIPTA – 100% dimuka, Rp 25.830.000
        $rows[] = [
            'contract_id' => 35, 'period_number' => 0,
            'due_date' => '2025-09-01', 'paid_at' => '2025-08-12',
            'amount_due' => 25830000, 'amount_paid' => 25830000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #36 PT SAGE – 100% dimuka, Rp 16.539.000
        $rows[] = [
            'contract_id' => 36, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => null,
            'amount_due' => 16539000, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-01', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #37 PT BANGUN BERKAT – 100% dimuka, Rp 4.455.000
        $rows[] = [
            'contract_id' => 37, 'period_number' => 0,
            'due_date' => '2025-10-16', 'paid_at' => '2025-10-13',
            'amount_due' => 4455000, 'amount_paid' => 4455000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #38 PT Khaimar – 100% dimuka, Rp 36.000.000
        $rows[] = [
            'contract_id' => 38, 'period_number' => 0,
            'due_date' => '2025-11-02', 'paid_at' => '2025-11-21',
            'amount_due' => 36000000, 'amount_paid' => 36000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #39 PT Rhacindo – 100% dimuka, Rp 4.500.000
        $rows[] = [
            'contract_id' => 39, 'period_number' => 0,
            'due_date' => '2025-12-02', 'paid_at' => null,
            'amount_due' => 4500000, 'amount_paid' => 0,
            'payment_status' => $st('2025-12-02', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #40 PT EDRA – 100% dimuka, Rp 31.500.000
        $rows[] = [
            'contract_id' => 40, 'period_number' => 0,
            'due_date' => '2025-10-21', 'paid_at' => '2025-09-22',
            'amount_due' => 31500000, 'amount_paid' => 31500000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // =================================================================
        // PERPANJANGAN
        // =================================================================

        // #41 PT CITRA (perpanjangan) – 100% dimuka, Rp 82.620.000
        $rows[] = [
            'contract_id' => 41, 'period_number' => 0,
            'due_date' => '2026-03-01', 'paid_at' => null,
            'amount_due' => 82620000, 'amount_paid' => 0,
            'payment_status' => $st('2026-03-01', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #42 PT INTI KRIDA EKAJASA – 100% dimuka, Rp 5.850.000
        $rows[] = [
            'contract_id' => 42, 'period_number' => 0,
            'due_date' => '2026-01-05', 'paid_at' => null,
            'amount_due' => 5850000, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-05', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #43 PT PUTRA TELKOM (perpanjangan) – 100% dimuka, Rp 8.970.000
        $rows[] = [
            'contract_id' => 43, 'period_number' => 0,
            'due_date' => '2026-02-01', 'paid_at' => '2026-02-06',
            'amount_due' => 8970000, 'amount_paid' => 8970000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #44 PT YIMI Prafab (perpanjangan) – 100% dimuka, Rp 58.300.000
        $rows[] = [
            'contract_id' => 44, 'period_number' => 0,
            'due_date' => '2026-02-10', 'paid_at' => '2026-02-06',
            'amount_due' => 58300000, 'amount_paid' => 58300000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #45 CV CIPTA (perpanjangan) – 100% dimuka, Rp 25.830.000
        $rows[] = [
            'contract_id' => 45, 'period_number' => 0,
            'due_date' => '2026-03-01', 'paid_at' => null,
            'amount_due' => 25830000, 'amount_paid' => 0,
            'payment_status' => $st('2026-03-01', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #46 PT BANGUN BERKAT (perpanjangan) – 100% dimuka, Rp 4.455.000
        $rows[] = [
            'contract_id' => 46, 'period_number' => 0,
            'due_date' => '2026-01-16', 'paid_at' => '2026-01-21',
            'amount_due' => 4455000, 'amount_paid' => 4455000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // #47 PT Khaimar (perpanjangan) – 100% dimuka, Rp 36.000.000
        $rows[] = [
            'contract_id' => 47, 'period_number' => 0,
            'due_date' => '2026-02-02', 'paid_at' => null,
            'amount_due' => 36000000, 'amount_paid' => 0,
            'payment_status' => $st('2026-02-02', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #48 PT Rhacindo (perpanjangan) – 100% dimuka, Rp 4.500.000
        $rows[] = [
            'contract_id' => 48, 'period_number' => 0,
            'due_date' => '2026-03-02', 'paid_at' => null,
            'amount_due' => 4500000, 'amount_paid' => 0,
            'payment_status' => $st('2026-03-02', null),
            'notes' => '100% dibayar dimuka',
        ];

        // #49 PT EDRA (perpanjangan) – 100% dimuka, Rp 31.500.000
        $rows[] = [
            'contract_id' => 49, 'period_number' => 0,
            'due_date' => '2026-01-21', 'paid_at' => null,
            'amount_due' => 31500000, 'amount_paid' => 0,
            'payment_status' => $st('2026-01-21', null),
            'notes' => '100% dibayar dimuka',
        ];

        // =================================================================
        // TENANT BARU
        // =================================================================

        // #50 PT KAIROS – Termin 50/50, Rp 1.056.540.000
        $kairos = 1056540000;
        $rows[] = [
            'contract_id' => 50, 'period_number' => 1,
            'due_date' => '2026-05-01', 'paid_at' => null,
            'amount_due' => $kairos * 0.50, 'amount_paid' => 0,
            'payment_status' => $st('2026-05-01', null),
            'notes' => 'Termin 1 – 50%',
        ];
        $rows[] = [
            'contract_id' => 50, 'period_number' => 2,
            'due_date' => '2026-11-01', 'paid_at' => null,
            'amount_due' => $kairos * 0.50, 'amount_paid' => 0,
            'payment_status' => $st('2026-11-01', null),
            'notes' => 'Termin 2 – 50%',
        ];

        // #51 PD MCR JAYA – Termin 50/50, Rp 106.560.000
        $mcr = 106560000;
        $rows[] = [
            'contract_id' => 51, 'period_number' => 1,
            'due_date' => '2026-03-01', 'paid_at' => null,
            'amount_due' => $mcr * 0.50, 'amount_paid' => 0,
            'payment_status' => $st('2026-03-01', null),
            'notes' => 'Termin 1 – 50%',
        ];
        $rows[] = [
            'contract_id' => 51, 'period_number' => 2,
            'due_date' => '2026-04-15', 'paid_at' => null,
            'amount_due' => $mcr * 0.50, 'amount_paid' => 0,
            'payment_status' => $st('2026-04-15', null),
            'notes' => 'Termin 2 – 50%',
        ];

        // #52 JADDASOLUTION – 100% diperhitungkan dgn IPK, Rp 15.120.000
        $rows[] = [
            'contract_id' => 52, 'period_number' => 0,
            'due_date' => '2025-08-01', 'paid_at' => null,
            'amount_due' => 15120000, 'amount_paid' => 0,
            'payment_status' => $st('2025-08-01', null),
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // =================================================================
        // POST-PROCESSING: Semua due_date < 2026-02-01 dianggap sudah paid
        // =================================================================
        foreach ($rows as &$row) {
            if (Carbon::parse($row['due_date'])->lt($cutoff) && $row['payment_status'] !== 'paid') {
                $row['paid_at']         = $row['paid_at'] ?? $row['due_date'];
                $row['amount_paid']     = $row['amount_due'];
                $row['payment_status']  = 'paid';
            }
        }
        unset($row);

        // =================================================================
        // INSERT ALL
        // =================================================================
        foreach ($rows as $row) {
            DB::table('payments')->insert(array_merge($row, [
                'amendment_id' => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]));
        }

        $this->command->info('Created ' . count($rows) . ' payment records.');
    }
}