<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractAsset extends Seeder
{
    /**
     * Pivot table contract_assets sesuai dengan AssetSeeder (per-lantai/per-gedung).
     *
     * PENTING: Satu baris kontrak di Excel yang menyebut lebih dari satu gedung/lantai
     * akan menghasilkan BANYAK baris di sini (satu per asset).
     *
     * Asset ID reference (dari AssetSeeder, urutan insert, 1-based):
     * ─── Moh. Toha 77 ────────────────────────────────────────────────────
     *  1  = 77-GKP-AUD  Ruang Auditorium GKP   (596 m²)
     *  2  = 77-GKP-01   GKP LT.1               (1579 m²)
     *  3  = 77-GKP-02   GKP LT.2               (1607 m²)
     *  4  = 77-GKP-03   GKP LT.3               (1403 m²)
     *  5  = 77-GKP-04   GKP LT.4               (1607 m²)
     *  6  = 77-GKP-05   GKP LT.5               (1607 m²)
     *  7  = 77-GKP-06   GKP LT.6               (1607 m²)
     *  8  = 77-GKP-07   GKP LT.7               (1607 m²)
     *  9  = 77-GKP-08   GKP LT.8               (1607 m²)
     * 10  = 77-GKP-09   GKP LT.9               (1402 m²)
     * 11  = 77-GKP-10   GKP LT.10              (1506 m²)
     * 12  = 77-GKP-12   GKP LT.Atap (Rooftop)  (1506 m²)
     * 13  = 77-GPT-01   GPT LT.1               (1114 m²)
     * 14  = 77-GPT-02   GPT LT.2               (1115 m²)
     * 15  = 77-GPT-03   GPT LT.3               (1115 m²)
     * 16  = 77-GPT-04   GPT LT.4               (1115 m²)
     * 17  = 77-GPT-05   GPT LT.5               (1115 m²)
     * 18  = 77-GPT-06   GPT LT.6               (1115 m²)
     * 19  = 77-GPT-07   GPT LT.Atap (Rooftop)  (1120 m²)
     * 20  = 77-GKL-C    GKL Ged.C              (614 m²)
     * 21  = 77-GKL-D    GKL Ged.D              (597 m²)
     * 22  = 77-GKL-E    GKL Ged.E              (850.94 m²)
     * 23  = 77-GKL-F    GKL Ged.F              (274.05 m²)
     * 24  = 77-GKL-G    GKL Ged.G              (245.82 m²)
     * 25  = 77-GKL-H    GKL Ged.H              (253.22 m²)
     * 26  = 77-GKL-I    GKL Ged.I              (152.93 m²)
     * 27  = 77-GKL-J    GKL Ged.J              (1760.30 m²)
     * 28  = 77-GKL-K    GKL Ged.K              (32.40 m²)
     * 29  = 77-GKL-L    GKL Ged.L              (185.22 m²)
     * 30  = 77-GKL-M    GKL Ged.M              (201.23 m²)
     * 31  = 77-GKL-N    GKL Ged.N              (220.45 m²)
     * 32  = 77-GKL-O    GKL Ged.O              (169.94 m²)
     * 33  = 77-GKL-R    GKL Ged.R              (469.65 m²)
     * 34  = 77-GKL-S    GKL Ged.S              (850.28 m²)
     * 35  = 77-GKL-T    GKL Ged.T              (501.60 m²)
     * 36  = 77-GKL-U    GKL Ged.U              (530.19 m²)
     * 37  = 77-GKL-V    GKL Ged.V              (281.71 m²)
     * 38  = 77-GKL-W    GKL Ged.W              (275.80 m²)
     * 39  = 77-GKL-X    GKL Ged.X              (234 m²)
     * 40  = 77-GKL-Y    GKL Ged.Y              (764 m²)
     * 41  = 77-GKL-AA   GKL Ged.AA             (89.24 m²)
     * 42  = 77-GKL-AB   GKL Ged.AB             (323 m²)
     * 43  = 77-GKL-AC   GKL Ged.AC             (602.25 m²)
     * 44  = 77-GKL-AD   GKL Ged.AD             (1800 m²)
     * 45  = 77-GKL-AE   GKL Ged.AE             (62 m²)
     * 46  = 77-GKL-AF   GKL Ged.AF             (58.19 m²)
     * 47  = 77-GKL-AG   GKL Ged.AG             (27 m²)
     * 48  = 77-GKL-AH   GKL Ged.AH             (20 m²)
     * 49  = 77-GKL-AJ   GKL Ged.AJ             (35 m²)
     * ─── Palasari 255 ────────────────────────────────────────────────────
     * 50  = 255-GP-A    GP Ged.A               (1085 m²)
     * 51  = 255-GP-B    GP Ged.B               (840 m²)   ← Gedung B
     * 52  = 255-GP-C    GP Ged.C               (880 m²)   ← Gedung C
     * 53  = 255-GP-D    GP Ged.D               (575 m²)
     * 54  = 255-PG-1    Gedung Sentral          (5480 m²)  ← Ex Gedung Sentral
     * 55  = 255-PG-2    Gedung Prafabrikasi     (3890 m²)  ← Ex Gedung Prafab
     * 56  = 255-PG-3    Gedung PCB              (1247 m²)
     * 57  = 255-PG-4    Gedung Pengecatan       (435 m²)
     * 58  = 255-PG-5    Gedung Workshop         (170 m²)
     * 59  = 255-PG-6    Gudang Komp. Sentral    (1780 m²)
     * 60  = 255-PG-7    Gudang Kimia            (750 m²)   ← Ex Gedung Kimia
     * 61  = 255-PG-8    Gudang Utama            (3621 m²)  ← Ex Gedung Utama
     *
     * Contract ID reference (dari ContractSeeder, actual DB IDs):
     *  1 = Kepolisian – GKL Gedung S
     *  2 = PT CITRA – GKL Gedung H
     *  3 = PT Navitas – GPT Lt. 1,2,3,4
     *  4 = YPKRI – GKP Lt. 5,6,7,8 + GKL Ged.F + GKL Ged.N
     *  5 = PT Inti Pindad – GKP Lt. 4 Selatan (sebagian Lt.4)
     *  6 = PT Putra Telkom #1 – GKP Lt. 3 Utara (sebagian Lt.3)
     *  7 = PT Putra Telkom #2 – GKP Lt. 3 Utara (sebagian Lt.3)
     *  8 = PT MBIP – GKP Lt. 3 Utara (sebagian Lt.3)
     *  9 = GAFRELLY – GKP Lt. 3 Utara (sebagian Lt.3)
     * 10 = MGIU – GKP Lt. 3 Utara (sebagian Lt.3)
     * 11 = PT IBP – GKP Lt. 3 Utara (sebagian Lt.3)
     * 12 = PT IBP – GKP Lt. 2 Utara (sebagian Lt.2)
     * 13 = PT Inti Konten – GKP Lt. 2 Utara (sebagian Lt.2)
     * 14 = PT Dayamitra – Rooftop GKP (LT.Atap)
     * 15 = PT EPID – Rooftop GPT (LT.Atap)
     * 16 = PT Mega Akses – Sub Duct GPT (tidak ada asset spesifik, gunakan GPT LT.1)
     * 17 = PT Wadma – Sub Duct GKP (tidak ada asset spesifik, gunakan GKP LT.1)
     * 18 = Joni Wibowo – GKL Ged.O (unit 1)
     * 19 = Ayi Dadi – GKL Ged.O (unit 2)
     * 20 = Koperasi R Usaha – GKL Ged.G
     * 21 = Yayasan DKI – GKL Ged.AC
     * 22 = Yayasan DKI – GKL Ged.AD
     * 23 = WBI – GKL Ged.D (Poli)
     * 24 = WBI – GKL Ged.AA (Lab)
     * 25 = WBI – GKL Ged.D (Apotik)
     * 26 = WBI – GKL Ged.V (Gudang)
     * 27 = Target Media – Media LED (tidak ada asset, skip atau pakai placeholder GKP-AUD)
     * 28 = Bank CIMB – Gerbang Toha 77 (ATM, tidak ada asset khusus, gunakan GKP LT.1)
     * 29 = Bank BNI  – Gerbang Toha 77 (ATM, gunakan GKP LT.1)
     * 30 = Bank OCBC – Gerbang Toha 77 (ATM, gunakan GKP LT.1)
     * 31 = Bank Mandiri – Gerbang Toha 77 (ATM, gunakan GKP LT.1)
     * 32 = PT IGOC – Ex Gedung Sentral (255-PG-1)
     * 33 = PT YIMI – Ex Gedung Utama (255-PG-8)
     * 34 = PT YIMI – Ex Gedung Prafab (255-PG-2)
     * 35 = PT YIMI – Ex Gedung Utama Loading (255-PG-8)
     * 36 = CV Cipta – Ex Gedung Prafab (255-PG-2)
     * 37 = PT Sage – EX Poliklinik (tidak ada asset spesifik, gunakan 255-GP-D)
     * 38 = PT Bangun – Gedung B (255-GP-B)
     * 39 = PT Khaimar – Ex Gedung Prafab (255-PG-2)
     * 40 = PT Rhacindo – Gedung C (255-GP-C)
     * 41 = PT EDRA – Ex Gedung Kimia (255-PG-7)
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Format: [contract_id, asset_id, rented_area_sqm]
        // Untuk kontrak dengan BANYAK lantai/gedung → satu baris per asset
        $pivots = [
            // ─── Contract 1: Kepolisian – GKL Gedung S ───────────────────────────
            // "GKL Gedung S" = 77-GKL-S (id: 34), luas disewa: 850.28 m²
            [1, 34, 850.28],

            // ─── Contract 2: PT CITRA – GKL Gedung H ─────────────────────────────
            // "GKL Gedung H" = 77-GKL-H (id: 25), luas disewa: 153 m²
            [2, 25, 153.00],

            // ─── Contract 3: PT Navitas – GPT Lt. 1,2,3,4 ───────────────────────
            // Setiap lantai GPT adalah asset terpisah
            // Luas disewa dari excel: 920.36 / 971.14 / 977.06 / 977.06
            [3, 13, 920.36],  // GPT LT.1
            [3, 14, 971.14],  // GPT LT.2
            [3, 15, 977.06],  // GPT LT.3
            [3, 16, 977.06],  // GPT LT.4

            // ─── Contract 4: YPKRI – GKP Lt. 5,6,7,8 + GKL Ged.F + GKL Ged.N ──
            // Luas: 4480 (untuk 4 lantai GKP) + 274 (GKL F) + 220 (GKL N)
            // Asumsi 4480 dibagi rata ke 4 lantai = 1120/lantai
            [4, 6,  1120.00],  // GKP LT.5
            [4, 7,  1120.00],  // GKP LT.6
            [4, 8,  1120.00],  // GKP LT.7
            [4, 9,  1120.00],  // GKP LT.8
            [4, 23, 274.00],   // GKL Ged.F
            [4, 31, 220.00],   // GKL Ged.N

            // ─── Contract 5: PT Inti Pindad – GKP Lt. 4 Selatan ─────────────────
            // Sebagian dari lantai 4 GKP → gunakan 77-GKP-04 (id: 5)
            [5, 5, 200.00],  // GKP LT.4 (selatan, 200 m²)

            // ─── Contract 6: PT Putra Telkom #1 – GKP Lt. 3 Utara ───────────────
            // Sebagian dari lantai 3 GKP → gunakan 77-GKP-03 (id: 4)
            [6, 4, 26.00],

            // ─── Contract 7: PT Putra Telkom #2 – GKP Lt. 3 Utara ───────────────
            [7, 4, 26.00],

            // ─── Contract 8: PT MBIP – GKP Lt. 3 Utara ──────────────────────────
            [8, 4, 51.12],

            // ─── Contract 9: GAFRELLY – GKP Lt. 3 Utara ──────────────────────────
            [9, 4, 26.00],

            // ─── Contract 10: MGIU – GKP Lt. 3 Utara ────────────────────────────
            [10, 4, 51.12],

            // ─── Contract 11: PT IBP – GKP Lt. 3 Utara ──────────────────────────
            [11, 4, 26.00],

            // ─── Contract 12: PT IBP – GKP Lt. 2 Utara ──────────────────────────
            // Sebagian dari lantai 2 GKP → gunakan 77-GKP-02 (id: 3)
            [12, 3, 124.00],

            // ─── Contract 13: PT Inti Konten – GKP Lt. 2 Utara ──────────────────
            [13, 3, 186.00],

            // ─── Contract 14: PT Dayamitra – Rooftop GKP ─────────────────────────
            // Rooftop GKP = 77-GKP-12 (id: 12)
            [14, 12, 32.00],

            // ─── Contract 15: PT EPID – Rooftop GPT ─────────────────────────────
            // Rooftop GPT = 77-GPT-07 (id: 19)
            [15, 19, 23.25],

            // ─── Contract 16: PT Mega Akses – Sub Duct GPT ───────────────────────
            // Sub duct tidak ada asset khusus di TestAsset.php
            // Gunakan GPT LT.1 (id: 13) sebagai referensi gedung
            [16, 13, 160.00],

            // ─── Contract 17: PT Wadma – Sub Duct GKP ────────────────────────────
            // Gunakan GKP LT.1 (id: 2) sebagai referensi gedung
            [17, 2, 30.00],

            // ─── Contract 18: Joni Wibowo – GKL Ged.O ───────────────────────────
            // GKL Ged.O = 77-GKL-O (id: 32)
            [18, 32, 22.00],

            // ─── Contract 19: Ayi Dadi – GKL Ged.O ──────────────────────────────
            [19, 32, 27.00],

            // ─── Contract 20: Koperasi R Usaha – GKL Ged.G ──────────────────────
            // GKL Ged.G = 77-GKL-G (id: 24)
            [20, 24, 0.00],  // luas belum diketahui

            // ─── Contract 21: Yayasan DKI – GKL Ged.AC ──────────────────────────
            // GKL Ged.AC = 77-GKL-AC (id: 43)
            [21, 43, 500.00],

            // ─── Contract 22: Yayasan DKI – GKL Ged.AD ──────────────────────────
            // GKL Ged.AD = 77-GKL-AD (id: 44)
            [22, 44, 124.40],

            // ─── Contract 23: WBI – GKL Ged.D (Poli) ────────────────────────────
            // GKL Ged.D = 77-GKL-D (id: 21)
            [23, 21, 291.54],

            // ─── Contract 24: WBI – GKL Ged.AA (Lab) ────────────────────────────
            // GKL Ged.AA = 77-GKL-AA (id: 41)
            [24, 41, 103.00],

            // ─── Contract 25: WBI – GKL Ged.D (Apotik) ──────────────────────────
            [25, 21, 57.04],

            // ─── Contract 26: WBI – GKL Ged.V (Gudang) ──────────────────────────
            // GKL Ged.V = 77-GKL-V (id: 37)
            [26, 37, 20.00],

            // ─── Contract 27: Target Media – Media LED ───────────────────────────
            // Tidak ada asset fisik khusus → pakai GKP LT.1 (id: 2) sebagai referensi lokasi pemasangan
            [27, 2, 0.00],

            // ─── Contract 28: Bank CIMB – ATM Gerbang Toha 77 ───────────────────
            // ATM di area GKP → pakai GKP LT.1 (id: 2)
            [28, 2, 5.00],

            // ─── Contract 29: Bank BNI – ATM Gerbang Toha 77 ─────────────────────
            [29, 2, 5.00],

            // ─── Contract 30: Bank OCBC – ATM Gerbang Toha 77 ───────────────────
            [30, 2, 5.00],

            // ─── Contract 31: Bank Mandiri – ATM Gerbang Toha 77 ────────────────
            [31, 2, 5.00],

            // ─── Contract 32: PT IGOC – Ex Gedung Sentral ────────────────────────
            // Ex Gedung Sentral = 255-PG-1 (id: 54)
            [32, 54, 3110.00],

            // ─── Contract 33: PT YIMI – Ex Gedung Utama ──────────────────────────
            // Ex Gedung Utama = 255-PG-8 Gudang Utama (id: 61)
            [33, 61, 3072.00],

            // ─── Contract 34: PT YIMI – Ex Gedung Prafab ─────────────────────────
            // Ex Gedung Prafab = 255-PG-2 Gedung Prafabrikasi (id: 55)
            [34, 55, 1166.00],

            // ─── Contract 35: PT YIMI – Ex Gedung Utama (Loading/Parkir) ─────────
            // Loading + Parkir tetap di area Gudang Utama = 255-PG-8 (id: 61)
            // Catatan: contract 33 juga memakai asset 61 → rented_area_sqm berbeda jadi aman (unique per contract+asset)
            // Tapi contract 33 dan 35 adalah kontrak BERBEDA → aman
            [35, 61, 320.00],

            // ─── Contract 36: CV Cipta Kreasindo – Ex Gedung Prafab ──────────────
            [36, 55, 77.00],

            // ─── Contract 37: PT Sage – EX Poliklinik ────────────────────────────
            // EX Poliklinik tidak ada di TestAsset → gunakan 255-GP-D (id: 53) sebagai terdekat
            [37, 53, 68.10],

            // ─── Contract 38: PT Bangun – Gedung B ───────────────────────────────
            // Gedung B = 255-GP-B (id: 51)
            [38, 51, 33.00],

            // ─── Contract 39: PT Khaimar – Ex Gedung Prafab ──────────────────────
            [39, 55, 300.00],

            // ─── Contract 40: PT Rhacindo – Gedung C ─────────────────────────────
            // Gedung C = 255-GP-C (id: 52)
            [40, 52, 30.00],

            // ─── Contract 41: PT EDRA – Ex Gedung Kimia ──────────────────────────
            // Ex Gedung Kimia = 255-PG-7 Gudang Kimia (id: 60)
            [41, 60, 150.00],
        ];

        foreach ($pivots as [$contractId, $assetId, $area]) {
            DB::table('contract_assets')->insert([
                'contract_id'     => $contractId,
                'asset_id'        => $assetId,
                'rented_area_sqm' => $area,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }
    }
}