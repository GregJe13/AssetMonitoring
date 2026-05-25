<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan field untuk mendukung kontrak KSU (Kerjasama Usaha / Bagi Hasil).
     * 
     * contract_type: 'sewa' (default, fixed rental) atau 'ksu' (bagi hasil)
     * sharing_type: 'revenue_sharing' atau 'profit_sharing'
     * company_share_pct: persentase bagi hasil untuk perusahaan (misal: 70.00 = 70%)
     * tenant_share_pct: persentase bagi hasil untuk tenant (misal: 30.00 = 30%)
     * 
     * Untuk KSU, total_rental_value dibiarkan NULL karena tidak ada nilai pasti.
     * Cash basis dicatat melalui Invoice manual dari hasil rekon KSU.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Tipe kontrak
            $table->enum('contract_type', ['sewa', 'ksu'])
                  ->default('sewa')
                  ->after('tenant_id');

            // KSU-specific fields (nullable, hanya diisi jika contract_type = 'ksu')
            $table->enum('sharing_type', ['revenue_sharing', 'profit_sharing'])
                  ->nullable()
                  ->after('security_deposit');

            $table->decimal('company_share_pct', 5, 2)
                  ->nullable()
                  ->after('sharing_type');

            $table->decimal('tenant_share_pct', 5, 2)
                  ->nullable()
                  ->after('company_share_pct');

            $table->index('contract_type');
        });

        // Buat total_rental_value nullable (KSU tidak punya nilai pasti)
        Schema::table('contracts', function (Blueprint $table) {
            $table->decimal('total_rental_value', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropIndex(['contract_type']);
            $table->dropColumn([
                'contract_type',
                'sharing_type',
                'company_share_pct',
                'tenant_share_pct',
            ]);
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->decimal('total_rental_value', 15, 2)->nullable(false)->change();
        });
    }
};
