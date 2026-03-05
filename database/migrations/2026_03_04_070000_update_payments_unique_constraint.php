<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update unique constraint pada payments table.
     * 
     * Sebelumnya: unique(['contract_id', 'period_number'])
     * Sekarang:   unique(['contract_id', 'amendment_id', 'period_number'])
     * 
     * Diperlukan karena satu kontrak bisa punya banyak payment sets
     * dari kontrak asli (amendment_id=NULL) dan setiap amendment.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Must drop FK first, otherwise MySQL won't let us drop the unique index
            $table->dropForeign(['contract_id']);
            
            // Drop old unique constraint
            $table->dropUnique(['contract_id', 'period_number']);
            
            // Add new unique constraint including amendment_id
            $table->unique(['contract_id', 'amendment_id', 'period_number']);
            
            // Re-add FK
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropUnique(['contract_id', 'amendment_id', 'period_number']);
            $table->unique(['contract_id', 'period_number']);
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }
};
