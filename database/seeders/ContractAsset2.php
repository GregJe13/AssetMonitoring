<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ContractAsset2 extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // rented_area_sqm = luas TERSEWA (dari data kontrak), bisa < kapasitas aset.
        // Kontrak #5, #19, #24, #25 tidak punya aset padanan -> tanpa baris.
        $contractAssets = [
            ['contract_id' => 1, 'asset_id' => 43, 'rented_area_sqm' => 500],  // YAYASAN DIAN KENCANA INTI 1 Gd AC
            ['contract_id' => 2, 'asset_id' => 12, 'rented_area_sqm' => 32],  // PT DAYAMITRA TELEKOMUNIKASI Tb
            ['contract_id' => 3, 'asset_id' => 3, 'rented_area_sqm' => 124],  // PT. INTI BUMI PERKASA Lt2
            ['contract_id' => 4, 'asset_id' => 49, 'rented_area_sqm' => 5],  // PT. BANK NEGARA INDONESIA
            ['contract_id' => 6, 'asset_id' => 25, 'rented_area_sqm' => 153],  // PT CITRA INDUSTRI KERETA API
            ['contract_id' => 7, 'asset_id' => 1, 'rented_area_sqm' => 596],  // INTIVENUE
            ['contract_id' => 8, 'asset_id' => 62, 'rented_area_sqm' => 77],  // CV. CIPTA KREASINDO TEKNIKA
            ['contract_id' => 9, 'asset_id' => 41, 'rented_area_sqm' => 103],  // PT. WIDYA BHAKTI INTI (WBI) Gd AA Lab
            ['contract_id' => 10, 'asset_id' => 49, 'rented_area_sqm' => 5],  // PT. BANK MANDIRI, TBK
            ['contract_id' => 11, 'asset_id' => 49, 'rented_area_sqm' => 5],  // PT. BANK CIMB NIAGA TBK
            ['contract_id' => 13, 'asset_id' => 34, 'rented_area_sqm' => 850.28],  // Kepolisian Negara RI Daerah Jawa Barat
            ['contract_id' => 14, 'asset_id' => 4, 'rented_area_sqm' => 51.12],  // PT. MITRA GRAHA INTI UTAMA (MGIU)
            ['contract_id' => 15, 'asset_id' => 56, 'rented_area_sqm' => 1247],  // PT MARLIP
            ['contract_id' => 15, 'asset_id' => 60, 'rented_area_sqm' => 750],  // PT MARLIP
            ['contract_id' => 15, 'asset_id' => 57, 'rented_area_sqm' => 435],  // PT MARLIP
            ['contract_id' => 16, 'asset_id' => 21, 'rented_area_sqm' => 57.04],  // PT. WIDYA BHAKTI INTI (WBI) Gd D Apotik
            ['contract_id' => 17, 'asset_id' => 3, 'rented_area_sqm' => 186],  // PT. INTI KONTEN INDONESIA
            ['contract_id' => 18, 'asset_id' => 62, 'rented_area_sqm' => 21],  // JADDASOLUTION
            ['contract_id' => 21, 'asset_id' => 19, 'rented_area_sqm' => 23.25],  // PT EPID MENARA ASSETCO
            ['contract_id' => 23, 'asset_id' => 21, 'rented_area_sqm' => 291.54],  // PT. WIDYA BHAKTI INTI (WBI) Gd D Poli
            ['contract_id' => 26, 'asset_id' => 49, 'rented_area_sqm' => 5],  // PT. BANK OCBC NISP,Tbk
            ['contract_id' => 27, 'asset_id' => 13, 'rented_area_sqm' => 920.36],  // PT Navitas Educational Service
            ['contract_id' => 27, 'asset_id' => 14, 'rented_area_sqm' => 971.14],  // PT Navitas Educational Service
            ['contract_id' => 27, 'asset_id' => 15, 'rented_area_sqm' => 977.06],  // PT Navitas Educational Service
            ['contract_id' => 27, 'asset_id' => 16, 'rented_area_sqm' => 977.06],  // PT Navitas Educational Service
            ['contract_id' => 28, 'asset_id' => 44, 'rented_area_sqm' => 124.4],  // YAYASAN DIAN KENCANA INTI 2 Gd AD
        ];

        foreach ($contractAssets as $ca) {
            DB::table('contract_assets')->insert(array_merge($ca, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
