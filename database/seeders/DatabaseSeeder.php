<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan seeder mengikuti prefix nama file di database/seeders/data/:
     *   1. TenantSeeder        — isi tabel tenants
     *   2. TestAsset           — isi tabel assets (61 gedung/lantai)
     *   3. TestContractSeeder  — isi tabel contracts
     *   4. ContractAsset       — isi pivot contract_assets
     *   5. PaymentSeeder       — isi tabel payments
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,        // 1 — harus pertama (contracts & payments referensi tenant)
            TestAsset::class,           // 2 — harus sebelum contracts (contract_assets referensi asset)
            TestContractSeeder::class,  // 3 — harus setelah tenant & asset
            ContractAsset::class,       // 4 — harus setelah contracts & assets
            PaymentSeeder::class,       // 5 — harus paling akhir (referensi contracts)
        ]);
    }
}
