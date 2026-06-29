<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom amendment_id pada tabel payments,
     * sekaligus mengupdate unique constraint agar mencakup amendment_id.
     * 
     * Sebelumnya: unique(['contract_id', 'period_number'])
     * Sekarang:   unique(['contract_id', 'amendment_id', 'period_number'])
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Tambah kolom amendment_id
            $table->foreignId('amendment_id')
                  ->nullable()
                  ->after('contract_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Drop FK contract_id dulu (MySQL butuh ini sebelum drop unique index)
            $table->dropForeign(['contract_id']);

            // Ganti unique constraint lama dengan yang baru (include amendment_id)
            $table->dropUnique(['contract_id', 'period_number']);
            $table->unique(['contract_id', 'amendment_id', 'period_number']);

            // Re-add FK contract_id
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Balik constraint ke semula
            $table->dropForeign(['contract_id']);
            $table->dropUnique(['contract_id', 'amendment_id', 'period_number']);
            $table->unique(['contract_id', 'period_number']);
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            // Hapus kolom amendment_id
            $table->dropForeign(['amendment_id']);
            $table->dropColumn('amendment_id');
        });
    }
};
