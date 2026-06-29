<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder2 extends Seeder
{
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            TestAsset::class,
            TestContractSeeder2::class,
            ContractAsset2::class,
            PaymentSeeder2::class,
        ]);
    }
}
