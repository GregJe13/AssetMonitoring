<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration aditif: menambah kolom luas (m²) yang dipakai perusahaan sendiri
     * pada tiap aset. Diisi manual dari halaman index aset. Tidak menyentuh tabel lain.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Luas (m²) yang dipakai perusahaan sendiri; diisi manual dari halaman index aset.
            $table->decimal('company_used_area_sqm', 10, 2)->default(0)->after('area_sqm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('company_used_area_sqm');
        });
    }
};
