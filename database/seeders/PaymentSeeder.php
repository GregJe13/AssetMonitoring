<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Payment seeder disesuaikan dengan TestContractSeeder.
     *
     * Contract ID (urutan insert di TestContractSeeder, 1-based):
     *  1  Kepolisian          upfront  Rp 67.567.568      2026-01-01 s/d 2026-12-31  active
     *  2  PT CITRA            upfront  Rp 82.620.000      2025-09-01 s/d 2026-02-28  active
     *  3  PT Navitas          3-bln    Rp 26.028.217.200  2024-09-01 s/d 2029-08-31  active
     *  4  YPKRI               upfront  Rp 2.027.027.027   2025-06-25 s/d 2026-06-24  active
     *  5  PT Inti Pindad      upfront  Rp 156.000.000     2025-01-01 s/d 2025-12-31  expired
     *  6  PT Putra Telkom #1  upfront  Rp 37.440.000      2025-04-25 s/d 2026-04-26  active
     *  7  PT Putra Telkom #2  upfront  Rp 8.970.000       2025-11-01 s/d 2026-01-31  active
     *  8  PT MBIP             upfront  Rp 47.848.320      2025-01-01 s/d 2025-12-31  expired
     *  9  GAFRELLY            upfront  Rp 23.400.000      2025-01-01 s/d 2025-12-31  expired
     * 10  MGIU                upfront  Rp 47.848.320      2025-01-01 s/d 2025-12-31  expired
     * 11  PT IBP Lt3          50%+50%  Rp 28.860.000      2024-11-01 s/d 2025-10-31  expired
     * 12  PT IBP Lt2          balance  Rp 88.164.000      2025-07-01 s/d 2025-12-31  expired
     * 13  PT IKI              tahunan  Rp 528.984.000     2025-02-03 s/d 2027-02-02  active
     * 14  PT Dayamitra        upfront  Rp 1.000.000.000   2023-01-02 (anomali end)   active
     * 15  PT EPID             upfront  Rp 115.133.200     2023-06-05 s/d 2027-10-27  active
     * 16  PT Mega Akses       upfront  Rp 9.600.000       2025-08-23 s/d 2026-08-22  active
     * 17  PT Wadma            upfront  Rp 1.440.000       2025-05-09 s/d 2026-05-08  active
     * 18  Joni Wibowo         upfront  Rp 17.160.000      2025-04-15 s/d 2026-04-14  active
     * 19  Ayi Dadi            upfront  Rp 21.060.000      2025-04-15 s/d 2026-04-14  active
     * 20  Koperasi R Usaha    draft    Rp 0               2026-01-01 s/d 2026-12-31  draft → SKIP
     * 21  Yayasan DKI AC      tahunan  Rp 475.000.000     2025-03-01 (anomali end)   active
     * 22  Yayasan DKI AD      tahunan  Rp 1.000.000.000   2024-11-01 s/d 2029-10-31  active
     * 23  WBI GKL D Poli      upfront  Rp 787.158.000     2023-01-01 s/d 2027-12-31  active
     * 24  WBI GKL AA Lab      tahunan  Rp 262.400.000     2021-12-01 s/d 2026-11-30  active
     * 25  WBI GKL D Apotik    balance  Rp 30.801.600      2025-01-01 s/d 2025-12-31  expired
     * 26  WBI GKL V Gudang    balance  Rp 10.800.000      2025-01-02 s/d 2025-12-31  expired
     * 27  Target Media        tahunan  Rp 22.394.880      2025-09-08 s/d 2027-09-07  active
     * 28  Bank CIMB           upfront  Rp 100.800.000     2023-12-31 s/d 2025-12-30  expired
     * 29  Bank BNI            upfront  Rp 96.000.000      2024-07-02 s/d 2026-07-01  active
     * 30  Bank OCBC           upfront  Rp 151.200.000     2025-12-31 s/d 2028-12-30  active
     * 31  Bank Mandiri        upfront  Rp 48.000.000      2025-12-13 s/d 2026-12-12  active
     * 32  PT IGOC             termin   Rp 1.866.000.000   2025-04-13 s/d 2026-04-12  active
     * 33  PT Yimi Ex GU       upfront  Rp 414.720.000     2025-11-16 s/d 2026-02-15  active
     * 34  PT Yimi Ex Prafab   upfront  Rp 174.900.000     2025-11-10 s/d 2026-02-09  active
     * 35  PT Yimi Loading     upfront  Rp 29.800.000      2025-12-03 s/d 2026-03-02  active
     * 36  CV Cipta            upfront  Rp 25.830.000      2025-09-01 s/d 2026-02-28  active
     * 37  PT Sage             upfront  Rp 16.539.000      2025-10-01 s/d 2025-12-31  expired
     * 38  PT Bangun           upfront  Rp 4.455.000       2025-10-16 s/d 2026-01-15  active
     * 39  PT Khaimar          upfront  Rp 36.000.000      2025-11-02 s/d 2026-02-01  active
     * 40  PT Rhacindo         upfront  Rp 4.500.000       2025-12-02 s/d 2026-03-01  active
     * 41  PT EDRA             upfront  Rp 31.500.000      2025-10-21 s/d 2026-01-20  active
     * 42  Elfia Minisoccer    rev-share (kerjasama, tidak ada di TestContractSeeder) → SKIP
     */
    public function run(): void
    {
        DB::table('payments')->truncate();

        $now   = Carbon::now();
        $today = Carbon::today();
        $rows  = [];

        // ─────────────────────────────────────────────────────────────────────────
        // HELPER: status otomatis berdasarkan due_date & paid_at
        // ─────────────────────────────────────────────────────────────────────────
        $st = function (string $dueDate, ?string $paidAt) use ($today): string {
            if ($paidAt) return 'paid';
            return Carbon::parse($dueDate)->lt($today) ? 'overdue' : 'pending';
        };

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 1 – KEPOLISIAN
        // upfront, Rp 67.567.568, due 2026-01-01, belum dibayar (future)
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 1, 'period_number' => 0,
            'due_date' => '2026-01-01', 'paid_at' => null,
            'amount_due' => 67567568, 'amount_paid' => 0,
            'payment_status' => 'pending',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 2 – PT CITRA
        // upfront, Rp 82.620.000, dibayar saat PKS Sep 2025
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 2, 'period_number' => 0,
            'due_date' => '2025-09-01', 'paid_at' => '2025-09-19',
            'amount_due' => 82620000, 'amount_paid' => 82620000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 3 – PT NAVITAS
        // Per 3 bulan, 2024-09-01 s/d 2029-08-31 = 20 periode
        // Total Rp 26.028.217.200 / 20 = Rp 1.301.410.860/periode
        // Asumsi: 2 periode pertama (Sep & Des 2024) sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $navAmount  = round(26028217200 / 20);
        $navDate    = Carbon::parse('2024-09-01');
        $navEnd     = Carbon::parse('2029-08-31');
        $navPeriod  = 1;

        while ($navDate->lte($navEnd)) {
            $due    = $navDate->toDateString();
            $isPaid = $navPeriod <= 2;
            $rows[] = [
                'contract_id' => 3, 'period_number' => $navPeriod,
                'due_date' => $due, 'paid_at' => $isPaid ? $due : null,
                'amount_due' => $navAmount, 'amount_paid' => $isPaid ? $navAmount : 0,
                'payment_status' => $st($due, $isPaid ? $due : null),
                'notes' => 'Per 3 bulan – periode ' . $navPeriod,
            ];
            $navDate->addMonths(3);
            $navPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 4 – YPKRI
        // upfront, Rp 2.027.027.027, dibayar saat kontrak mulai
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 4, 'period_number' => 0,
            'due_date' => '2025-06-25', 'paid_at' => '2025-06-25',
            'amount_due' => 2027027027, 'amount_paid' => 2027027027,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 5 – PT INTI PINDAD
        // upfront, Rp 156.000.000, expired, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 5, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-01-10',
            'amount_due' => 156000000, 'amount_paid' => 156000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 6 – PT PUTRA TELKOM #1
        // upfront, Rp 37.440.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 6, 'period_number' => 0,
            'due_date' => '2025-04-25', 'paid_at' => '2025-04-25',
            'amount_due' => 37440000, 'amount_paid' => 37440000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 7 – PT PUTRA TELKOM #2
        // upfront, Rp 8.970.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 7, 'period_number' => 0,
            'due_date' => '2025-11-01', 'paid_at' => '2025-11-07',
            'amount_due' => 8970000, 'amount_paid' => 8970000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 8 – PT MBIP
        // upfront, Rp 47.848.320, expired, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 8, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-11-10',
            'amount_due' => 47848320, 'amount_paid' => 47848320,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 9 – GAFRELLY
        // upfront, Rp 23.400.000, expired, sudah dibayar
        // Catatan: "100% diperhitungkan dengan nilai IPK"
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 9, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2025-08-01',
            'amount_due' => 23400000, 'amount_paid' => 23400000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 10 – MGIU
        // upfront, Rp 47.848.320, expired, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 10, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => '2024-12-24',
            'amount_due' => 47848320, 'amount_paid' => 47848320,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 11 – PT IBP Lt3
        // 50% dibayar dimuka + 50% balancing
        // Total Rp 28.860.000 → masing-masing Rp 14.430.000
        // ─────────────────────────────────────────────────────────────────────────
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

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 12 – PT IBP Lt2
        // Balancing, Rp 88.164.000, expired, belum dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 12, 'period_number' => 0,
            'due_date' => '2025-07-01', 'paid_at' => null,
            'amount_due' => 88164000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => 'Balancing – Proses BAK',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 13 – PT INTI KONTEN INDONESIA
        // Per tahun, 2025-02-03 s/d 2027-02-02 = 2 periode
        // Total Rp 528.984.000 / 2 = Rp 264.492.000/tahun
        // Periode 1 sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $ikiDate   = Carbon::parse('2025-02-03');
        $ikiEnd    = Carbon::parse('2027-02-02');
        $ikiPeriod = 1;

        while ($ikiDate->lte($ikiEnd)) {
            $due    = $ikiDate->toDateString();
            $isPaid = $ikiPeriod === 1;
            $rows[] = [
                'contract_id' => 13, 'period_number' => $ikiPeriod,
                'due_date' => $due, 'paid_at' => $isPaid ? '2025-02-10' : null,
                'amount_due' => 264492000, 'amount_paid' => $isPaid ? 264492000 : 0,
                'payment_status' => $st($due, $isPaid ? '2025-02-10' : null),
                'notes' => 'Per tahun – periode ' . $ikiPeriod,
            ];
            $ikiDate->addYear();
            $ikiPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 14 – PT DAYAMITRA
        // upfront, Rp 1.000.000.000, dibayar 2023
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 14, 'period_number' => 0,
            'due_date' => '2023-01-02', 'paid_at' => '2023-03-13',
            'amount_due' => 1000000000, 'amount_paid' => 1000000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 15 – PT EPID
        // upfront, Rp 115.133.200, dibayar 2023
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 15, 'period_number' => 0,
            'due_date' => '2023-06-05', 'paid_at' => '2023-06-05',
            'amount_due' => 115133200, 'amount_paid' => 115133200,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 16 – PT MEGA AKSES
        // upfront, Rp 9.600.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 16, 'period_number' => 0,
            'due_date' => '2025-08-23', 'paid_at' => '2025-10-02',
            'amount_due' => 9600000, 'amount_paid' => 9600000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 17 – PT WADMA
        // upfront, Rp 1.440.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 17, 'period_number' => 0,
            'due_date' => '2025-05-09', 'paid_at' => '2025-05-19',
            'amount_due' => 1440000, 'amount_paid' => 1440000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 18 – JONI WIBOWO
        // upfront, Rp 17.160.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 18, 'period_number' => 0,
            'due_date' => '2025-04-15', 'paid_at' => '2025-04-14',
            'amount_due' => 17160000, 'amount_paid' => 17160000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 19 – AYI DADI
        // upfront, Rp 21.060.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 19, 'period_number' => 0,
            'due_date' => '2025-04-15', 'paid_at' => '2025-04-14',
            'amount_due' => 21060000, 'amount_paid' => 21060000,
            'payment_status' => 'paid',
            'notes' => '100% diperhitungkan dengan nilai IPK',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 20 – KOPERASI R USAHA
        // Status: draft, total_rental_value = 0 → SKIP
        // ─────────────────────────────────────────────────────────────────────────

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 21 – YAYASAN DKI (GKL Ged.AC)
        // Per tahun, Rp 475.000.000/tahun, mulai 2025-03-01
        // End date anomali (1930) → seed 3 periode ke depan
        // Periode 1 sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $dkiAcDate   = Carbon::parse('2025-03-01');
        $dkiAcPeriod = 1;

        for ($i = 0; $i < 3; $i++) {
            $due    = $dkiAcDate->toDateString();
            $isPaid = $dkiAcPeriod === 1;
            $rows[] = [
                'contract_id' => 21, 'period_number' => $dkiAcPeriod,
                'due_date' => $due, 'paid_at' => $isPaid ? '2025-03-10' : null,
                'amount_due' => 475000000, 'amount_paid' => $isPaid ? 475000000 : 0,
                'payment_status' => $st($due, $isPaid ? '2025-03-10' : null),
                'notes' => 'Per tahun – periode ' . $dkiAcPeriod,
            ];
            $dkiAcDate->addYear();
            $dkiAcPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 22 – YAYASAN DKI (GKL Ged.AD)
        // Per tahun, 2024-11-01 s/d 2029-10-31 = 5 periode
        // Total Rp 1.000.000.000 / 5 = Rp 200.000.000/tahun
        // Periode 1 sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $dkiAdDate   = Carbon::parse('2024-11-01');
        $dkiAdEnd    = Carbon::parse('2029-10-31');
        $dkiAdPeriod = 1;

        while ($dkiAdDate->lte($dkiAdEnd)) {
            $due    = $dkiAdDate->toDateString();
            $isPaid = $dkiAdPeriod === 1;
            $rows[] = [
                'contract_id' => 22, 'period_number' => $dkiAdPeriod,
                'due_date' => $due, 'paid_at' => $isPaid ? '2024-10-15' : null,
                'amount_due' => 200000000, 'amount_paid' => $isPaid ? 200000000 : 0,
                'payment_status' => $st($due, $isPaid ? '2024-10-15' : null),
                'notes' => 'Per tahun – periode ' . $dkiAdPeriod,
            ];
            $dkiAdDate->addYear();
            $dkiAdPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 23 – WBI GKL GED.D (POLI)
        // upfront, Rp 787.158.000, dibayar 2023
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 23, 'period_number' => 0,
            'due_date' => '2023-01-01', 'paid_at' => '2023-03-13',
            'amount_due' => 787158000, 'amount_paid' => 787158000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka, realisasi balancing',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 24 – WBI GKL GED.AA (LAB)
        // Per tahun, balancing, 2021-12-01 s/d 2026-11-30 = 5 periode
        // Total Rp 262.400.000 / 5 = Rp 52.480.000/tahun
        // Semua periode yang sudah lewat dianggap dibayar (balancing)
        // ─────────────────────────────────────────────────────────────────────────
        $wbiAaDate   = Carbon::parse('2021-12-01');
        $wbiAaEnd    = Carbon::parse('2026-11-30');
        $wbiAaPeriod = 1;

        while ($wbiAaDate->lte($wbiAaEnd)) {
            $due    = $wbiAaDate->toDateString();
            $isPaid = $wbiAaDate->lt($today);
            $rows[] = [
                'contract_id' => 24, 'period_number' => $wbiAaPeriod,
                'due_date' => $due, 'paid_at' => $isPaid ? $due : null,
                'amount_due' => 52480000, 'amount_paid' => $isPaid ? 52480000 : 0,
                'payment_status' => $isPaid ? 'paid' : 'pending',
                'notes' => 'Balancing per tahun – periode ' . $wbiAaPeriod,
            ];
            $wbiAaDate->addYear();
            $wbiAaPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 25 – WBI GKL GED.D (APOTIK)
        // Balancing, Rp 30.801.600, expired, belum dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 25, 'period_number' => 0,
            'due_date' => '2025-01-01', 'paid_at' => null,
            'amount_due' => 30801600, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => 'Balancing – Konfirmasi Perpanjangan',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 26 – WBI GKL GED.V (GUDANG)
        // Balancing, Rp 10.800.000, expired, belum dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 26, 'period_number' => 0,
            'due_date' => '2025-01-02', 'paid_at' => null,
            'amount_due' => 10800000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => 'Balancing – Konfirmasi Perpanjangan',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 27 – TARGET MEDIA
        // Per tahun, 2025-09-08 s/d 2027-09-07 = 2 periode
        // Total Rp 22.394.880 / 2 = Rp 11.197.440/tahun
        // Belum ada pembayaran (belum ada keterangan lunas di excel)
        // ─────────────────────────────────────────────────────────────────────────
        $tmDate   = Carbon::parse('2025-09-08');
        $tmEnd    = Carbon::parse('2027-09-07');
        $tmPeriod = 1;

        while ($tmDate->lte($tmEnd)) {
            $due = $tmDate->toDateString();
            $rows[] = [
                'contract_id' => 27, 'period_number' => $tmPeriod,
                'due_date' => $due, 'paid_at' => null,
                'amount_due' => 11197440, 'amount_paid' => 0,
                'payment_status' => $st($due, null),
                'notes' => 'Per tahun – periode ' . $tmPeriod,
            ];
            $tmDate->addYear();
            $tmPeriod++;
        }

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 28 – BANK CIMB NIAGA
        // upfront, Rp 100.800.000, expired, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 28, 'period_number' => 0,
            'due_date' => '2023-12-31', 'paid_at' => '2024-01-31',
            'amount_due' => 100800000, 'amount_paid' => 100800000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 29 – BANK BNI
        // upfront, Rp 96.000.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 29, 'period_number' => 0,
            'due_date' => '2024-07-02', 'paid_at' => '2024-07-11',
            'amount_due' => 96000000, 'amount_paid' => 96000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 30 – BANK OCBC
        // upfront, Rp 151.200.000, belum dibayar (start 2025-12-31)
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 30, 'period_number' => 0,
            'due_date' => '2025-12-31', 'paid_at' => null,
            'amount_due' => 151200000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 31 – BANK MANDIRI
        // upfront, Rp 48.000.000, belum dibayar (Proses PKS)
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 31, 'period_number' => 0,
            'due_date' => '2025-12-13', 'paid_at' => null,
            'amount_due' => 48000000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '100% dibayar dimuka – Proses PKS',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 32 – PT IGOC
        // Termin 25% / 50% / 25%, Total Rp 1.866.000.000
        // Termin 1 & 2 sudah dibayar, Termin 3 belum
        // ─────────────────────────────────────────────────────────────────────────
        $igoc = 1866000000;
        $rows[] = [
            'contract_id' => 32, 'period_number' => 1,
            'due_date' => '2025-04-13', 'paid_at' => '2025-04-13',
            'amount_due' => $igoc * 0.25, 'amount_paid' => $igoc * 0.25,
            'payment_status' => 'paid', 'notes' => 'Termin 1 – 25%',
        ];
        $rows[] = [
            'contract_id' => 32, 'period_number' => 2,
            'due_date' => '2025-07-13', 'paid_at' => '2025-07-13',
            'amount_due' => $igoc * 0.50, 'amount_paid' => $igoc * 0.50,
            'payment_status' => 'paid', 'notes' => 'Termin 2 – 50%',
        ];
        $rows[] = [
            'contract_id' => 32, 'period_number' => 3,
            'due_date' => '2026-04-12', 'paid_at' => null,
            'amount_due' => $igoc * 0.25, 'amount_paid' => 0,
            'payment_status' => 'pending', 'notes' => 'Termin 3 – 25%',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 33 – PT YIMI (EX GEDUNG UTAMA)
        // upfront, Rp 414.720.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 33, 'period_number' => 0,
            'due_date' => '2025-11-16', 'paid_at' => '2025-11-16',
            'amount_due' => 414720000, 'amount_paid' => 414720000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 34 – PT YIMI (EX GEDUNG PRAFAB)
        // upfront, Rp 174.900.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 34, 'period_number' => 0,
            'due_date' => '2025-11-10', 'paid_at' => '2025-11-10',
            'amount_due' => 174900000, 'amount_paid' => 174900000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 35 – PT YIMI (EX GEDUNG UTAMA LOADING)
        // upfront, Rp 29.800.000, belum dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 35, 'period_number' => 0,
            'due_date' => '2025-12-03', 'paid_at' => null,
            'amount_due' => 29800000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 36 – CV CIPTA KREASINDO
        // upfront, Rp 25.830.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 36, 'period_number' => 0,
            'due_date' => '2025-09-01', 'paid_at' => '2025-09-01',
            'amount_due' => 25830000, 'amount_paid' => 25830000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 37 – PT SAGE
        // upfront, Rp 16.539.000, expired, belum dibayar (Proses BAK)
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 37, 'period_number' => 0,
            'due_date' => '2025-10-01', 'paid_at' => null,
            'amount_due' => 16539000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '100% dibayar dimuka – Proses BAK',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 38 – PT BANGUN BERKAT SAUDARA
        // upfront, Rp 4.455.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 38, 'period_number' => 0,
            'due_date' => '2025-10-16', 'paid_at' => '2025-10-16',
            'amount_due' => 4455000, 'amount_paid' => 4455000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 39 – PT KHAIMAR
        // upfront, Rp 36.000.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 39, 'period_number' => 0,
            'due_date' => '2025-11-02', 'paid_at' => '2025-11-24',
            'amount_due' => 36000000, 'amount_paid' => 36000000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 40 – PT RHACINDO
        // upfront, Rp 4.500.000, belum dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 40, 'period_number' => 0,
            'due_date' => '2025-12-02', 'paid_at' => null,
            'amount_due' => 4500000, 'amount_paid' => 0,
            'payment_status' => 'overdue',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // KONTRAK 41 – PT EDRA
        // upfront, Rp 31.500.000, sudah dibayar
        // ─────────────────────────────────────────────────────────────────────────
        $rows[] = [
            'contract_id' => 41, 'period_number' => 0,
            'due_date' => '2025-10-21', 'paid_at' => '2025-10-21',
            'amount_due' => 31500000, 'amount_paid' => 31500000,
            'payment_status' => 'paid',
            'notes' => '100% dibayar dimuka',
        ];

        // ─────────────────────────────────────────────────────────────────────────
        // INSERT
        // ─────────────────────────────────────────────────────────────────────────
        foreach ($rows as $row) {
            DB::table('payments')->insert(array_merge($row, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('Created ' . DB::table('payments')->count() . ' payment records.');
    }
}