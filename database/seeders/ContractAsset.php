<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractAsset extends Seeder
{
    /**
     * Seed the contract_assets pivot table.
     *
     * Contract IDs  : 1-52 (insert order dari TestContractSeeder)
     * Asset IDs     : 1-49 (Toha 77), 50-62 (Toha 255) (insert order dari TestAsset)
     *
     * Asset ID Reference (Toha 77):
     *  1=AUD, 2=GKP-01, 3=GKP-02, 4=GKP-03, 5=GKP-04,
     *  6=GKP-05, 7=GKP-06, 8=GKP-07, 9=GKP-08, 10=GKP-09,
     *  11=GKP-10, 12=GKP-12(Atap), 13=GPT-01, 14=GPT-02,
     *  15=GPT-03, 16=GPT-04, 17=GPT-05, 18=GPT-06,
     *  19=GPT-07(Atap), 20=GKL-C, 21=GKL-D, 22=GKL-E,
     *  23=GKL-F, 24=GKL-G, 25=GKL-H, 26=GKL-I, 27=GKL-J,
     *  28=GKL-K, 29=GKL-L, 30=GKL-M, 31=GKL-N, 32=GKL-O,
     *  33=GKL-R, 34=GKL-S, 35=GKL-T, 36=GKL-U, 37=GKL-V,
     *  38=GKL-W, 39=GKL-X, 40=GKL-Y, 41=GKL-AA, 42=GKL-AB,
     *  43=GKL-AC, 44=GKL-AD, 45=GKL-AE, 46=GKL-AF,
     *  47=GKL-AG, 48=GKL-AH, 49=GKL-AJ
     *
     * Asset ID Reference (Toha 255):
     *  50=GP-A, 51=GP-B, 52=GP-C, 53=GP-D, 54=PG-1(Sentral),
     *  55=PG-2(Prafab), 56=PG-3(PCB), 57=PG-4(Pengecatan),
     *  58=PG-5(Workshop), 59=PG-6(Gudang Sentral),
     *  60=PG-7(Kimia), 61=PG-8(Utama), 62=PG-9(Poliklinik)
     */
    public function run(): void
    {
        $now = Carbon::now();

        $contractAssets = [
            // =====================================================
            // SEWA - Lokasi 77
            // =====================================================

            // #1 Kepolisian Negara RI – GKL Gedung S (asset 34)
            ['contract_id' => 1, 'asset_id' => 34, 'rented_area_sqm' => 850.28],

            // #2 PT CITRA INDUSTRI KERETA API – GKL Gedung H (asset 25)
            ['contract_id' => 2, 'asset_id' => 25, 'rented_area_sqm' => 153],

            // #3 PT Navitas Educational Service – GPT Lt. 1,2,3,4 (assets 13,14,15,16)
            ['contract_id' => 3, 'asset_id' => 13, 'rented_area_sqm' => 920.36],
            ['contract_id' => 3, 'asset_id' => 14, 'rented_area_sqm' => 971.14],
            ['contract_id' => 3, 'asset_id' => 15, 'rented_area_sqm' => 977.06],
            ['contract_id' => 3, 'asset_id' => 16, 'rented_area_sqm' => 977.06],

            // #4 YAYASAN PENDIDIKAN KEBANGSAAN RI – GKP Lt.5,6,7,8 + GKL F + GKL N
            ['contract_id' => 4, 'asset_id' => 6,  'rented_area_sqm' => 1120],   // GKP LT.5
            ['contract_id' => 4, 'asset_id' => 7,  'rented_area_sqm' => 1120],   // GKP LT.6
            ['contract_id' => 4, 'asset_id' => 8,  'rented_area_sqm' => 1120],   // GKP LT.7
            ['contract_id' => 4, 'asset_id' => 9,  'rented_area_sqm' => 1120],   // GKP LT.8
            ['contract_id' => 4, 'asset_id' => 23, 'rented_area_sqm' => 274.05],    // GKL Ged.F
            ['contract_id' => 4, 'asset_id' => 31, 'rented_area_sqm' => 220.45],    // GKL Ged.N

            // #5 PT Inti Pindad Mitra Sejati – GKP Lt. 4 Selatan (asset 5)
            ['contract_id' => 5, 'asset_id' => 5, 'rented_area_sqm' => 200],

            // #6 PT PUTRA TELEKOMUNIKASI INDONESIA 1 – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 6, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #7 PT PUTRA TELEKOMUNIKASI INDONESIA 2 – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 7, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #8 PT. MITRA BHAKTI INTI PERDANA – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 8, 'asset_id' => 4, 'rented_area_sqm' => 30],

            // #9 GAFRELLY – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 9, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #10 PT. MITRA GRAHA INTI UTAMA (MGIU) – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 10, 'asset_id' => 4, 'rented_area_sqm' => 51.12],

            // #11 PT. INTI BUMI PERKASA Lt3 – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 11, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #12 PT. INTI BUMI PERKASA Lt2 – GKP Lt. 2 Utara (asset 3)
            ['contract_id' => 12, 'asset_id' => 3, 'rented_area_sqm' => 124],

            // #13 PT. INTI KONTEN INDONESIA – GKP Lt. 2 Utara (asset 3)
            ['contract_id' => 13, 'asset_id' => 3, 'rented_area_sqm' => 186],

            // #14 PT DAYAMITRA TELEKOMUNIKASI Tbk – Rooftop GKP (asset 12)
            ['contract_id' => 14, 'asset_id' => 12, 'rented_area_sqm' => 32],

            // #15 PT EPID MENARA ASSETCO – Rooftop GPT (asset 19)
            ['contract_id' => 15, 'asset_id' => 19, 'rented_area_sqm' => 23.25],

            // #16 PT Mega Akses Persada – Sub Duct GPT
            // Belum ada asset di TestAsset, skip

            // #17 PT. WADMA BERKAH SEDAYA – Sub Duct GKP
            // Belum ada asset di TestAsset, skip

            // #18 Joni Wibowo – GKL Gedung O unit 1 (asset 32)
            ['contract_id' => 18, 'asset_id' => 32, 'rented_area_sqm' => 22],

            // #19 Ayi Dadi Cipta Ganda – GKL Gedung O unit 2 (asset 32)
            ['contract_id' => 19, 'asset_id' => 32, 'rented_area_sqm' => 27],

            // #20 YAYASAN DIAN KENCANA INTI 1 – GKL Gedung AC (asset 43)
            ['contract_id' => 20, 'asset_id' => 43, 'rented_area_sqm' => 500],

            // #21 YAYASAN DIAN KENCANA INTI 2 – GKL Gedung AD (asset 44)
            ['contract_id' => 21, 'asset_id' => 44, 'rented_area_sqm' => 124.4],

            // #22 PT. WIDYA BHAKTI INTI (WBI) Gd D Poli – GKL Gedung D (asset 21)
            ['contract_id' => 22, 'asset_id' => 21, 'rented_area_sqm' => 291.54],

            // #23 PT. WIDYA BHAKTI INTI (WBI) Gd AA Lab – GKL Gedung AA (asset 41)
            ['contract_id' => 23, 'asset_id' => 41, 'rented_area_sqm' => 103],

            // #24 PT. WIDYA BHAKTI INTI (WBI) Gd D Apotik – GKL Gedung D (asset 21)
            ['contract_id' => 24, 'asset_id' => 21, 'rented_area_sqm' => 57.04],

            // #25 PT. WIDYA BHAKTI INTI (WBI) Gd V Gudang – GKL Gedung V (asset 37)
            ['contract_id' => 25, 'asset_id' => 37, 'rented_area_sqm' => 20],

            // #26 PT. TARGET MEDIA NUSANTARA – Media LED
            // Belum ada asset di TestAsset, skip

            // #27 PT. BANK CIMB NIAGA TBK – Gerbang Toha 77 / GKL Ged.AJ (asset 49)
            ['contract_id' => 27, 'asset_id' => 49, 'rented_area_sqm' => 5],

            // #28 PT. BANK NEGARA INDONESIA – Gerbang Toha 77 / GKL Ged.AJ (asset 49)
            ['contract_id' => 28, 'asset_id' => 49, 'rented_area_sqm' => 5],

            // #29 PT. BANK OCBC NISP,Tbk – Gerbang Toha 77 / GKL Ged.AJ (asset 49)
            ['contract_id' => 29, 'asset_id' => 49, 'rented_area_sqm' => 5],

            // #30 PT. BANK MANDIRI, TBK – Gerbang Toha 77 / GKL Ged.AJ (asset 49)
            ['contract_id' => 30, 'asset_id' => 49, 'rented_area_sqm' => 5],

            // =====================================================
            // SEWA - Lokasi 225
            // =====================================================

            // #31 PT Inti Global Optical Communication – Ex Gedung Sentral (asset 54)
            ['contract_id' => 31, 'asset_id' => 54, 'rented_area_sqm' => 3110],

            // #32 PT GLOBAL YIMI CARGO – Ex Gedung Utama (asset 61)
            ['contract_id' => 32, 'asset_id' => 61, 'rented_area_sqm' => 3072],

            // #33 PT GLOBAL YIMI CARGO – Ex Gedung Prafab (asset 55)
            ['contract_id' => 33, 'asset_id' => 55, 'rented_area_sqm' => 1166],

            // #34 PT GLOBAL YIMI CARGO – Ex Gedung Utama Loading/Parkir (asset 61)
            ['contract_id' => 34, 'asset_id' => 61, 'rented_area_sqm' => 320],  // Loading 80 + Parkir 240

            // #35 CV. CIPTA KREASINDO TEKNIKA – Ex Gedung Prafab (asset 55)
            ['contract_id' => 35, 'asset_id' => 55, 'rented_area_sqm' => 77],

            // #36 PT SAGE KONSTRUKSI INDONESIA – EX Poliklinik (asset 62)
            ['contract_id' => 36, 'asset_id' => 62, 'rented_area_sqm' => 68.1],

            // #37 PT BANGUN BERKAT SAUDARA – Gedung B (asset 51)
            ['contract_id' => 37, 'asset_id' => 51, 'rented_area_sqm' => 33],

            // #38 PT Khaimar Indo Freight – Ex Gedung Prafab (asset 55)
            ['contract_id' => 38, 'asset_id' => 55, 'rented_area_sqm' => 300],

            // #39 PT Rhacindo Adi Persada – Gedung C (asset 52)
            ['contract_id' => 39, 'asset_id' => 52, 'rented_area_sqm' => 30],

            // #40 PT EDRA – Ex Gedung Kimia (asset 60)
            ['contract_id' => 40, 'asset_id' => 60, 'rented_area_sqm' => 150],

            // =====================================================
            // PERPANJANGAN / TAMBAHAN
            // =====================================================

            // #41 PT CITRA INDUSTRI KERETA API – GKL Gedung H perpanjangan (asset 25)
            ['contract_id' => 41, 'asset_id' => 25, 'rented_area_sqm' => 153],

            // #42 PT INTI KRIDA EKAJASA – GKP Lt. 3 Utara (asset 4)
            ['contract_id' => 42, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #43 PT PUTRA TELEKOMUNIKASI INDONESIA – GKP Lt. 3 Utara perpanjangan (asset 4)
            ['contract_id' => 43, 'asset_id' => 4, 'rented_area_sqm' => 26],

            // #44 PT GLOBAL YIMI CARGO – Ex Gedung Prafab perpanjangan (asset 55)
            ['contract_id' => 44, 'asset_id' => 55, 'rented_area_sqm' => 1166],

            // #45 CV. CIPTA KREASINDO TEKNIKA – Ex Gedung Prafab perpanjangan (asset 55)
            ['contract_id' => 45, 'asset_id' => 55, 'rented_area_sqm' => 77],

            // #46 PT BANGUN BERKAT SAUDARA – Gedung B perpanjangan (asset 51)
            ['contract_id' => 46, 'asset_id' => 51, 'rented_area_sqm' => 33],

            // #47 PT Khaimar Indo Freight – Ex Gedung Prafab perpanjangan (asset 55)
            ['contract_id' => 47, 'asset_id' => 55, 'rented_area_sqm' => 300],

            // #48 PT Rhacindo Adi Persada – Gedung C perpanjangan (asset 52)
            ['contract_id' => 48, 'asset_id' => 52, 'rented_area_sqm' => 30],

            // #49 PT EDRA – Ex Gedung Kimia perpanjangan (asset 60)
            ['contract_id' => 49, 'asset_id' => 60, 'rented_area_sqm' => 150],

            // =====================================================
            // TENANT BARU (dari Excel v2)
            // =====================================================

            // #50 PT KAIROS MULTI DIMENSI – Ex Gedung Prafab (asset 55)
            ['contract_id' => 50, 'asset_id' => 55, 'rented_area_sqm' => 1555],

            // #51 PD MCR JAYA – Ex Gedung Utama (asset 61)
            ['contract_id' => 51, 'asset_id' => 61, 'rented_area_sqm' => 888],

            // #52 JADDASOLUTION – Ex Gd Poliklinik (asset 62)
            ['contract_id' => 52, 'asset_id' => 62, 'rented_area_sqm' => 21],
        ];

        foreach ($contractAssets as $ca) {
            DB::table('contract_assets')->insert(array_merge($ca, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('Created ' . count($contractAssets) . ' contract-asset mappings.');
    }
}