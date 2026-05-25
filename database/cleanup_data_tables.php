<?php
/**
 * Cleanup script: Drop semua tabel data (kecuali users, cache, jobs)
 * dan bersihkan tabel migrations agar bisa re-migrate dari folder data/.
 * 
 * Run with: php artisan tinker database/cleanup_data_tables.php
 * Delete this file after finishing.
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔧 Starting data table cleanup...\n\n";

// 1. Disable FK checks agar bisa drop tabel tanpa masalah urutan
DB::statement('SET FOREIGN_KEY_CHECKS=0');
echo "✅ Deactivate FK checks\n";

// 2. Daftar semua tabel data yang perlu di-drop (KECUALI users, cache, jobs, dll)
$tables = [
    'actual_revenues',
    'activity_logs',
    'pending_renewal_assets',
    'pending_renewals',
    'amendment_assets',
    'amendments',
    'invoice_assets',
    'invoices',
    'workflow_evidence',
    'contract_workflows',
    'payments',
    'contract_assets',
    'contracts',
    'assets',
    'tenants',
];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        Schema::drop($table);
        echo "  🗑️  Dropped: {$table}\n";
    } else {
        echo "  ⏭️  Skipped (tidak ada): {$table}\n";
    }
}

// 3. Re-enable FK checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');
echo "\n✅ Activate FK checks\n";

// 4. Bersihkan tabel migrations (simpan hanya Laravel default + users role)
$keep = [
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
    '2026_02_03_100000_add_role_to_users_table',
];

$deleted = DB::table('migrations')
    ->whereNotIn('migration', $keep)
    ->delete();

echo "\n🧹 Deleted {$deleted} entries from migrations table\n";
echo "   (Kept: " . implode(', ', $keep) . ")\n";

echo "\n🎉 Cleanup done!\n";

