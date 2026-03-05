<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $tenants = [
            // id => [name, id_tenant, npwp, phone, email, pic, pic_phone]
            ['name' => 'Kepolisian Negara RI Daerah Jawa Barat', 'id_tenant' => 40232, 'npwp' => '141624700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT CITRA INDUSTRI KERETA API',           'id_tenant' => 40175, 'npwp' => '803373500000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT FANAUVI INFOTECH GEMILANG',           'id_tenant' => null,  'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Navitas Educational Service',         'id_tenant' => 40238, 'npwp' => '460499000000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'YAYASAN PENDIDIKAN KEBANGSAAN RI',       'id_tenant' => 40246, 'npwp' => '160542200000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Inti Pindad Mitra Sejati',            'id_tenant' => 40105, 'npwp' => '233292600000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT PUTRA TELEKOMUNIKASI INDONESIA',      'id_tenant' => 40237, 'npwp' => '430307700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT BERKAH SINAR PERMATA',                'id_tenant' => null,  'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. MITRA BHAKTI INTI PERDANA',          'id_tenant' => 40050, 'npwp' => '174591200000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'GAFRELLY',                               'id_tenant' => 40255, 'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. MITRA GRAHA INTI UTAMA (MGIU)',      'id_tenant' => 40051, 'npwp' => '182285300000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. INTI BUMI PERKASA',                  'id_tenant' => 40046, 'npwp' => '182280600000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. INTI KONTEN INDONESIA',              'id_tenant' => 40047, 'npwp' => '311953400000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT DAYAMITRA TELEKOMUNIKASI Tbk',        'id_tenant' => 40215, 'npwp' => '107124500000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT EPID MENARA ASSETCO',                 'id_tenant' => 40044, 'npwp' => '417324500000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Mega Akses Persada',                  'id_tenant' => 40243, 'npwp' => '662983100000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. WADMA BERKAH SEDAYA',                'id_tenant' => 40247, 'npwp' => '655933200000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Joni Wibowo',                            'id_tenant' => 40253, 'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Ayi Dadi Cipta Ganda',                   'id_tenant' => 40254, 'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Koperasi R Usaha',                       'id_tenant' => 40026, 'npwp' => '124082700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'YAYASAN DIAN KENCANA INTI',              'id_tenant' => 40194, 'npwp' => '210108700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. WIDYA BHAKTI INTI (WBI)',            'id_tenant' => 40060, 'npwp' => '211467200000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT FOCUS MEDIA INDONESIA',               'id_tenant' => null,  'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. TARGET MEDIA NUSANTARA',             'id_tenant' => 40220, 'npwp' => '841876200000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. BANK CIMB NIAGA TBK',                'id_tenant' => 40083, 'npwp' => '131066900000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. BANK NEGARA INDONESIA',              'id_tenant' => 40033, 'npwp' => '100160600000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. BANK OCBC NISP,Tbk',                 'id_tenant' => 40034, 'npwp' => '110491900000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT. BANK MANDIRI, TBK',                  'id_tenant' => 40134, 'npwp' => '106117400000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Inti Global Optical Communication',   'id_tenant' => 40190, 'npwp' => '317155400000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT GLOBAL YIMI CARGO',                   'id_tenant' => 40252, 'npwp' => '427781100000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'CV. CIPTA KREASINDO TEKNIKA',            'id_tenant' => 40009, 'npwp' => '216184700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT SAGE KONSTRUKSI INDONESIA',           'id_tenant' => 40213, 'npwp' => '733680600000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT BANGUN BERKAT SAUDARA',               'id_tenant' => 40160, 'npwp' => '332521000000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT JETEC INDONESIA',                     'id_tenant' => null,  'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Khaimar Indo Freight',                'id_tenant' => 40236, 'npwp' => '800517300000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT Rhacindo Adi Persada',                'id_tenant' => 40167, 'npwp' => '201204300000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'CV Rotary Pratama Eng',                  'id_tenant' => null,  'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'PT EDRA',                                'id_tenant' => 40124, 'npwp' => '317257800000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Elfia Minisoccer',                       'id_tenant' => 40228, 'npwp' => '500002800000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'INTIVENUE',                              'id_tenant' => 40159, 'npwp' => null,              'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'DMANTEN',                                'id_tenant' => 40250, 'npwp' => '830657400000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Kantin Sehat / Koperasi INTI',           'id_tenant' => 40026, 'npwp' => '124082700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Parkir Toha 77 / Koperasi INTI',         'id_tenant' => 40026, 'npwp' => '124082700000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
            ['name' => 'Parkir Palasari / PT. MITRA GRAHA INTI UTAMA', 'id_tenant' => 40051, 'npwp' => '182285300000000', 'phone' => null, 'email' => null, 'pic' => null, 'pic_phone' => null],
        ];

        foreach ($tenants as $tenant) {
            DB::table('tenants')->insert(array_merge($tenant, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}