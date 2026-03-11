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
            ['name' => 'Kepolisian Negara RI Daerah Jawa Barat',       'id_tenant' => 40232, 'npwp' => '0001416247422000',  'phone' => '08122198779',   'email' => null,                              'pic' => 'Yusyus',       'pic_phone' => null],
            ['name' => 'PT CITRA INDUSTRI KERETA API',                 'id_tenant' => 40175, 'npwp' => '0803373539424000',  'phone' => '081931265506',  'email' => null,                              'pic' => 'Lisye Herlina','pic_phone' => null],
            ['name' => 'PT FANAUVI INFOTECH GEMILANG',                 'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT Navitas Educational Service',               'id_tenant' => 40238, 'npwp' => '0046049904047000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'YAYASAN PENDIDIKAN KEBANGSAAN RI',             'id_tenant' => 40246, 'npwp' => '0016054215064000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT Inti Pindad Mitra Sejati',                  'id_tenant' => 40105, 'npwp' => '00023329261441000', 'phone' => '0818207579',     'email' => null,                              'pic' => 'Desi',         'pic_phone' => null],
            ['name' => 'PT PUTRA TELEKOMUNIKASI INDONESIA',            'id_tenant' => 40237, 'npwp' => '00430307686445000', 'phone' => '081398685506',   'email' => 'info@putratelkom-indonesia.co.id','pic' => 'Ela Nurlaela', 'pic_phone' => null],
            ['name' => 'PT BERKAH SINAR PERMATA',                      'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. MITRA BHAKTI INTI PERDANA',                'id_tenant' => 40050, 'npwp' => '0017459116441000',  'phone' => '08122363336',    'email' => null,                              'pic' => 'Yuli',         'pic_phone' => null],
            ['name' => 'GAFRELLY',                                     'id_tenant' => 40255, 'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. MITRA GRAHA INTI UTAMA (MGIU)',            'id_tenant' => 40051, 'npwp' => '0018228528424000',  'phone' => '081211978523',   'email' => null,                              'pic' => 'Inggit',       'pic_phone' => null],
            ['name' => 'PT. INTI BUMI PERKASA',                        'id_tenant' => 40046, 'npwp' => '0018228064441000',  'phone' => '08122312923',    'email' => 'ekr0303@yahoo.co.id',             'pic' => 'endah kartika r','pic_phone' => null],
            ['name' => 'PT. INTI KONTEN INDONESIA',                    'id_tenant' => 40047, 'npwp' => '0031195344242000',  'phone' => '08122106963',    'email' => null,                              'pic' => 'Yesi',         'pic_phone' => null],
            ['name' => 'PT INTI KRIDA EKAJASA',                        'id_tenant' => 40253, 'npwp' => null,                'phone' => '081220538383',   'email' => 'rara.pache@gmail.com',            'pic' => 'Ira Wati',     'pic_phone' => null],
            ['name' => 'PT DAYAMITRA TELEKOMUNIKASI Tbk',              'id_tenant' => 40215, 'npwp' => '0010712446093000',  'phone' => '082214859632',   'email' => 'tedi.permana.ext@huawei.com',     'pic' => 'Tedi Permana', 'pic_phone' => null],
            ['name' => 'PT EPID MENARA ASSETCO',                       'id_tenant' => 40044, 'npwp' => '0417324548022000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT Mega Akses Persada',                        'id_tenant' => 40243, 'npwp' => '0662983097035000',  'phone' => '085720202303',   'email' => null,                              'pic' => 'Octo',         'pic_phone' => null],
            ['name' => 'PT. WADMA BERKAH SEDAYA',                      'id_tenant' => 40247, 'npwp' => '0655933224422000',  'phone' => '08111199992',    'email' => null,                              'pic' => 'Feizal',       'pic_phone' => null],
            ['name' => 'Joni Wibowo',                                  'id_tenant' => 40253, 'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'Ayi Dadi Cipta Ganda',                         'id_tenant' => 40254, 'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'Koperasi R Usaha',                             'id_tenant' => 40026, 'npwp' => '0012408274424000',  'phone' => '082122535042',   'email' => 'enungrukmini64@yahoo.com',        'pic' => 'Enung Rukmini','pic_phone' => null],
            ['name' => 'YAYASAN DIAN KENCANA INTI',                   'id_tenant' => 40194, 'npwp' => '0210108650424000',  'phone' => '087722575981',   'email' => null,                              'pic' => 'Lilis',        'pic_phone' => null],
            ['name' => 'PT. WIDYA BHAKTI INTI (WBI)',                  'id_tenant' => 40060, 'npwp' => '0211467212424000',  'phone' => '081394019899',   'email' => 'umbrah_wbi@yahoo.com',            'pic' => 'Tami',         'pic_phone' => null],
            ['name' => 'PT FOCUS MEDIA INDONESIA',                     'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. TARGET MEDIA NUSANTARA',                   'id_tenant' => 40220, 'npwp' => '0841876162063000',  'phone' => '085311987173',   'email' => null,                              'pic' => 'Niko',         'pic_phone' => null],
            ['name' => 'PT. BANK CIMB NIAGA TBK',                     'id_tenant' => 40083, 'npwp' => '0013106687091000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. BANK NEGARA INDONESIA',                    'id_tenant' => 40033, 'npwp' => '0010016061093000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. BANK OCBC NISP,Tbk',                      'id_tenant' => 40034, 'npwp' => '0011049194091000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT. BANK MANDIRI, TBK',                       'id_tenant' => 40134, 'npwp' => '0010611739093000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT Inti Global Optical Communication',         'id_tenant' => 40190, 'npwp' => '0317155372445000',  'phone' => '081802021712',   'email' => null,                              'pic' => 'Opik',         'pic_phone' => null],
            ['name' => 'PT GLOBAL YIMI CARGO',                        'id_tenant' => 40252, 'npwp' => '0427781075047000',  'phone' => '082240362738',   'email' => null,                              'pic' => 'Diki',         'pic_phone' => null],
            ['name' => 'CV. CIPTA KREASINDO TEKNIKA',                  'id_tenant' => 40009, 'npwp' => '0021618467421000',  'phone' => '08122444436',    'email' => null,                              'pic' => 'Nandang',      'pic_phone' => null],
            ['name' => 'PT SAGE KONSTRUKSI INDONESIA',                 'id_tenant' => 40213, 'npwp' => '0733680599421000',  'phone' => '0811985001',     'email' => null,                              'pic' => 'Wiwin',        'pic_phone' => null],
            ['name' => 'PT BANGUN BERKAT SAUDARA',                     'id_tenant' => 40160, 'npwp' => '0033252099422000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT JETEC INDONESIA',                           'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT Khaimar Indo Freight',                      'id_tenant' => 40236, 'npwp' => '0800517302444000',  'phone' => '081394351772',   'email' => 'harlialata@gmail.com',            'pic' => 'Abdurahman',   'pic_phone' => null],
            ['name' => 'PT Rhacindo Adi Persada',                      'id_tenant' => 40167, 'npwp' => '0020120432445000',  'phone' => '087724854337',   'email' => 'maulanaibenrap@gmail.com',        'pic' => 'Ibeng',        'pic_phone' => null],
            ['name' => 'CV Rotary Pratama Eng',                        'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT EDRA',                                      'id_tenant' => 40124, 'npwp' => '0031725781413000',  'phone' => '087870280480',   'email' => null,                              'pic' => 'Ellan',        'pic_phone' => null],
            ['name' => 'Elfia Minisoccer',                             'id_tenant' => 40228, 'npwp' => '0500002837422000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'INTIVENUE',                                    'id_tenant' => 40159, 'npwp' => null,                'phone' => '088215068888',   'email' => null,                              'pic' => 'Andri',        'pic_phone' => null],
            ['name' => 'DMANTEN',                                      'id_tenant' => 40250, 'npwp' => '0830657425409000',  'phone' => '081313773553',   'email' => null,                              'pic' => 'Rimadanial',   'pic_phone' => null],
            ['name' => 'Kantin Sehat / Koperasi INTI',                 'id_tenant' => 40026, 'npwp' => '0012408274424000',  'phone' => '082122535042',   'email' => 'enungrukmini64@yahoo.com',        'pic' => 'Enung Rukmini','pic_phone' => null],
            ['name' => 'Parkir Toha 77 / Koperasi INTI',               'id_tenant' => 40026, 'npwp' => '0012408274424000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'Parkir Palasari / PT. MITRA GRAHA INTI UTAMA', 'id_tenant' => 40051, 'npwp' => '0018228528424000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],

            // -------------------------------------------------------
            // Tenant baru dari Data Kontrak 2026v2
            // -------------------------------------------------------
            ['name' => 'PT JALAWAVE',                                  'id_tenant' => null,  'npwp' => null,                'phone' => '08172372832',    'email' => null,                              'pic' => 'Adi',          'pic_phone' => null],
            ['name' => 'PT INET',                                      'id_tenant' => null,  'npwp' => null,                'phone' => '085213811976',   'email' => null,                              'pic' => 'Karina',       'pic_phone' => null],
            ['name' => 'PT MARLIP',                                    'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PT KAIROS MULTI DIMENSI',                      'id_tenant' => 40256, 'npwp' => '0027451509035000',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PD MCR JAYA',                                  'id_tenant' => 40257, 'npwp' => '1000000008322360',  'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'JADDASOLUTION',                                'id_tenant' => 40211, 'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'Ex BNI',                                       'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'Kerjasama',                                    'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
            ['name' => 'PADEL',                                        'id_tenant' => null,  'npwp' => null,                'phone' => null,             'email' => null,                              'pic' => null,           'pic_phone' => null],
        ];

        foreach ($tenants as $tenant) {
            DB::table('tenants')->insert(array_merge($tenant, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}