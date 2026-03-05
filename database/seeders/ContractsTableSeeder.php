<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test tenant
        $tenant = Tenant::create([
            'name' => 'PT Test Property',
            'contact_person' => 'Sri Wahyuni',
            'phone' => '021-98765432',
            'email' => 'sri@test-property.com',
            'address' => 'Jl. Jendral Sudirman No. 456, Jakarta Pusat',
        ]);

        // Create test assets
        $asset1 = Asset::create([
            'code' => 'GKP-LT2-001',
            'name' => 'Ruang Kantor Lantai 2',
            'location' => 'GKP LT.2 UTARA',
            'area_sqm' => 186,
            'building_condition' => 'baik',
        ]);

        $asset2 = Asset::create([
            'code' => 'GKP-LT3-001',
            'name' => 'Ruang Meeting Lantai 3',
            'location' => 'GKP LT.3 UTARA',
            'area_sqm' => 50,
            'building_condition' => 'baik',
        ]);

        $asset3 = Asset::create([
            'code' => 'GKP-LT4-001',
            'name' -> 'Ruang Seminar Lantai 4',
            'location' => 'GKP LT.4 UTARA',
            'area_sqm' => 120,
            'building_condition' => 'baik',
        ]);

        // Create multiple contracts with different payment intervals
        $contracts = [
            // Contract 1: Monthly contract for 12 months
            [
                'tenant_id' => $tenant->id,
                'contract_number' => 'CTR-2026-001',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'total_rental_value' => 120000000, // 120 juta
                'is_upfront' => false,
                'payment_interval_value' => 1,
                'payment_interval_unit' => 'month',
                'status' => 'active',
            ],
            
            // Contract 2: Quarterly contract for 12 months
            [
                'tenant_id' => $tenant->id,
                'contract_number' => 'CTR-2026-002',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'total_rental_value' => 48000000, // 48 juta
                'is_upfront' => false,
                'payment_interval_value' => 3,
                'payment_interval_unit' => 'month',
                'status' => 'active',
            ],
            
            // Contract 3: Annual contract for 2 years
            [
                'tenant_id' => $tenant->id,
                'contract_number' => 'CTR-2026-003',
                'start_date' => '2026-01-01',
                'end_date' => '2027-12-31',
                'total_rental_value' => 200000000, // 200 juta
                'is_upfront' => true,
                'status' => 'active',
            ],
            
            // Contract 4: Monthly contract for 6 months (future)
            [
                'tenant_id' => $tenant->id,
                'contract_number' => 'CTR-2027-001',
                'start_date' => '2027-01-01',
                'end_date' => '2027-06-30',
                'total_rental_value' => 72000000, // 72 juta
                'is_upfront' => false,
                'payment_interval_value' => 1,
                'payment_interval_unit' => 'month',
                'status' => 'active',
            ],
        ];

        foreach ($contracts as $contractData) {
            $contract = Contract::create($contractData);
            
            // Attach assets to contract
            if (isset($contractData['assets'])) {
                foreach ($contractData['assets'] as $assetId) {
                    $contract->assets()->attach($assetId, ['rental_value' => 10000000]);
                }
            } else {
                // Default attachment for first three contracts
                if ($contract->id <= 3) {
                    $contract->assets()->attach([$asset1->id], ['rental_value' => 10000000]);
                    
                    if ($contract->id === 2 || $contract->id === 3) {
                        $contract->assets()->attach($asset2->id, ['rental_value' => 10000000]);
                        
                        if ($contract->id === 3) {
                            $contract->assets()->attach($asset3->id, ['rental_value' => 10000000]);
                        }
                    }
                }
            }

            // Generate payments based on contract configuration
            if (!$contractData['is_upfront']) {
                $this->generatePaymentsForContract($contract);
            }
        }

        $this->command->info("Created {$contract->count()} contracts with sample data");
    }

    /**
     * Generate payments for a given contract based on its payment interval.
     */
    private function generatePaymentsForContract(Contract $contract): void
    {
        // Calculate number of periods based on payment interval and duration
        $periods = 0;
        
        if ($contract->payment_interval_unit === 'month') {
            $months = date_diff(date_create($contract->start_date), date_create($contract->end_date))->m + 1;
            $periods = ceil($months / $contract->payment_interval_value);
        } elseif ($contract->payment_interval_unit === 'year') {
            $years = date_diff(date_create($contract->start_date), date_create($contract->end_date))->y + 1;
            $periods = $contract->payment_interval_value * $years;
        }
        
        // Generate payments
        for ($i = 0; $i < $periods; $i++) {
            Payment::create([
                'contract_id' => $contract->id,
                'period_number' => $i + 1,
                'amount_due' => $contractData['total_rental_value'] / $periods,
                'due_date' => date_create_from_format('Y-m-d', $contract->start_date)
                    ->modify("+" . ($i * $contract->payment_interval_value) . " months")
                    ->format('Y-m-d'),
            ]);
        }
    }
}
