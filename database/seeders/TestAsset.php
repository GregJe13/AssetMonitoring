<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class TestAsset extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create All Assets for Moh. Toha 77
        $assets77 = [
            ['id_gedung' => '77-GKP-AUD', 'name' => 'Ruang Auditorium GKP', 'area_sqm' => 596],
            ['id_gedung' => '77-GKP-01', 'name' => 'GKP LT.1', 'area_sqm' => 1579],
            ['id_gedung' => '77-GKP-02', 'name' => 'GKP LT.2', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-03', 'name' => 'GKP LT.3', 'area_sqm' => 1403],
            ['id_gedung' => '77-GKP-04', 'name' => 'GKP LT.4', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-05', 'name' => 'GKP LT.5', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-06', 'name' => 'GKP LT.6', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-07', 'name' => 'GKP LT.7', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-08', 'name' => 'GKP LT.8', 'area_sqm' => 1607],
            ['id_gedung' => '77-GKP-09', 'name' => 'GKP LT.9', 'area_sqm' => 1402],
            ['id_gedung' => '77-GKP-10', 'name' => 'GKP LT.10', 'area_sqm' => 1506],
            ['id_gedung' => '77-GKP-12', 'name' => 'GKP LT.Atap', 'area_sqm' => 1506],
            ['id_gedung' => '77-GPT-01', 'name' => 'GPT LT.1', 'area_sqm' => 1114],
            ['id_gedung' => '77-GPT-02', 'name' => 'GPT LT.2', 'area_sqm' => 1115],
            ['id_gedung' => '77-GPT-03', 'name' => 'GPT LT.3', 'area_sqm' => 1115],
            ['id_gedung' => '77-GPT-04', 'name' => 'GPT LT.4', 'area_sqm' => 1115],
            ['id_gedung' => '77-GPT-05', 'name' => 'GPT LT.5', 'area_sqm' => 1115],
            ['id_gedung' => '77-GPT-06', 'name' => 'GPT LT.6', 'area_sqm' => 1115],
            ['id_gedung' => '77-GPT-07', 'name' => 'GPT LT.Atap', 'area_sqm' => 1120],
            ['id_gedung' => '77-GKL-C', 'name' => 'GKL Ged.C', 'area_sqm' => 614],
            ['id_gedung' => '77-GKL-D', 'name' => 'GKL Ged.D', 'area_sqm' => 597],
            ['id_gedung' => '77-GKL-E', 'name' => 'GKL Ged.E', 'area_sqm' => 850.94],
            ['id_gedung' => '77-GKL-F', 'name' => 'GKL Ged.F', 'area_sqm' => 274.05],
            ['id_gedung' => '77-GKL-G', 'name' => 'GKL Ged.G', 'area_sqm' => 245.82],
            ['id_gedung' => '77-GKL-H', 'name' => 'GKL Ged.H', 'area_sqm' => 253.22],
            ['id_gedung' => '77-GKL-I', 'name' => 'GKL Ged.I', 'area_sqm' => 152.93],
            ['id_gedung' => '77-GKL-J', 'name' => 'GKL Ged.J', 'area_sqm' => 1760.30],
            ['id_gedung' => '77-GKL-K', 'name' => 'GKL Ged.K', 'area_sqm' => 32.40],
            ['id_gedung' => '77-GKL-L', 'name' => 'GKL Ged.L', 'area_sqm' => 185.22],
            ['id_gedung' => '77-GKL-M', 'name' => 'GKL Ged.M', 'area_sqm' => 201.23],
            ['id_gedung' => '77-GKL-N', 'name' => 'GKL Ged.N', 'area_sqm' => 220.45],
            ['id_gedung' => '77-GKL-O', 'name' => 'GKL Ged.O', 'area_sqm' => 169.94],
            ['id_gedung' => '77-GKL-R', 'name' => 'GKL Ged.R', 'area_sqm' => 469.65],
            ['id_gedung' => '77-GKL-S', 'name' => 'GKL Ged.S', 'area_sqm' => 850.28],
            ['id_gedung' => '77-GKL-T', 'name' => 'GKL Ged.T', 'area_sqm' => 501.60],
            ['id_gedung' => '77-GKL-U', 'name' => 'GKL Ged.U', 'area_sqm' => 530.19],
            ['id_gedung' => '77-GKL-V', 'name' => 'GKL Ged.V', 'area_sqm' => 281.71],
            ['id_gedung' => '77-GKL-W', 'name' => 'GKL Ged.W', 'area_sqm' => 275.80],
            ['id_gedung' => '77-GKL-X', 'name' => 'GKL Ged.X', 'area_sqm' => 234],
            ['id_gedung' => '77-GKL-Y', 'name' => 'GKL Ged.Y', 'area_sqm' => 764],
            ['id_gedung' => '77-GKL-AA', 'name' => 'GKL Ged.AA', 'area_sqm' => 103],
            ['id_gedung' => '77-GKL-AB', 'name' => 'GKL Ged.AB', 'area_sqm' => 323],
            ['id_gedung' => '77-GKL-AC', 'name' => 'GKL Ged.AC', 'area_sqm' => 602.25],
            ['id_gedung' => '77-GKL-AD', 'name' => 'GKL Ged.AD', 'area_sqm' => 1800],
            ['id_gedung' => '77-GKL-AE', 'name' => 'GKL Ged.AE', 'area_sqm' => 62],
            ['id_gedung' => '77-GKL-AF', 'name' => 'GKL Ged.AF', 'area_sqm' => 58.19],
            ['id_gedung' => '77-GKL-AG', 'name' => 'GKL Ged.AG', 'area_sqm' => 27],
            ['id_gedung' => '77-GKL-AH', 'name' => 'GKL Ged.AH', 'area_sqm' => 20],
            ['id_gedung' => '77-GKL-AJ', 'name' => 'GKL Ged.AJ', 'area_sqm' => 35],
        ];

        // Create assets for Moh. Toha 77 and store first few for contract testing
        $createdAssets = [];
        foreach ($assets77 as $assetData) {
            $assetData['building_condition'] = 'baik';
            $createdAssets[] = Asset::create($assetData);
        }

        // Create Assets for Moh. Toha 255
        $assets255 = [
            ['id_gedung' => '255-GP-A', 'name' => 'GP Ged.A', 'area_sqm' => 1085],
            ['id_gedung' => '255-GP-B', 'name' => 'GP Ged.B', 'area_sqm' => 840],
            ['id_gedung' => '255-GP-C', 'name' => 'GP Ged.C', 'area_sqm' => 880],
            ['id_gedung' => '255-GP-D', 'name' => 'GP Ged.D', 'area_sqm' => 575],
            ['id_gedung' => '255-PG-1', 'name' => 'Gedung Sentral', 'area_sqm' => 5480],
            ['id_gedung' => '255-PG-2', 'name' => 'Gedung Prafabrikasi', 'area_sqm' => 3890],
            ['id_gedung' => '255-PG-3', 'name' => 'Gedung PCB', 'area_sqm' => 1247],
            ['id_gedung' => '255-PG-4', 'name' => 'Gedung Pengecatan', 'area_sqm' => 435],
            ['id_gedung' => '255-PG-5', 'name' => 'Gedung Workshop', 'area_sqm' => 170],
            ['id_gedung' => '255-PG-6', 'name' => 'Gudang Komp. Sentral', 'area_sqm' => 1780],
            ['id_gedung' => '255-PG-7', 'name' => 'Gudang Kimia', 'area_sqm' => 750],
            ['id_gedung' => '255-PG-8', 'name' => 'Gudang Utama', 'area_sqm' => 3621],
        ];

        foreach ($assets255 as $assetData) {
            $assetData['building_condition'] = 'baik';
            Asset::create($assetData);
        }

        $this->command->info('Created ' . Asset::count() . ' assets.');

        // $tenant = [
        //     ['name' => 'Kepolisian Negara RI Daerah Jawa Barat', 'id_tenant' => 40232, 'npwp' => '0001416247422000'],
        //     ['name' => 'PT CITRA INDUSTRI KERETA API', 'id_tenant' => 40175, 'npwp' => '0803373539424000'],
        //     ['name' => 'PT Navitas Educational Service', 'id_tenant' => 40238, 'npwp' => '0046049904047000'],
        //     ['name' => 'YAYASAN PENDIDIKAN KEBANGSAAN RI', 'id_tenant' => 40246, 'npwp' => '0016054215064000'],
        //     ['name' => 'PT Inti Pindad Mitra Sejati', 'id_tenant' => 40105, 'npwp' => '00023329261441000'],
        //     ['name' => 'PT PUTRA TELEKOMUNIKASI INDONE', 'id_tenant' => 40237, 'npwp' => '00430307686445000'],
        //     ['name' => 'PT. MITRA BHAKTI INTI PERDANA', 'id_tenant' => 40050, 'npwp' => '0017459116441000'],
        //     ['name' => 'Perorangan - GAFRELLY', 'id_tenant' => 40255],
        //     ['name' => 'PT. MITRA GRAHA INTI UTAMA (MGIU)', 'id_tenant' => 40051, 'npwp' => '0018228528424000'],
        //     ['name' => 'PT. INTI BUMI PERKASA', 'id_tenant' => 40046, 'npwp' => '0018228064441000'],
        //     ['name' => 'PT. INTI KONTEN INDONESIA', 'id_tenant' => 40047, 'npwp' => '0031195344242000'],
        //     ['name' => 'PT DAYAMITRA TELEKOMUNIKASI Tb', 'id_tenant' => 40215, 'npwp' => '0010712446093000'],
        // ];

        // foreach ($tenant as $tenantData) {
        //     Tenant::create($tenantData);
        // }
    }
}
