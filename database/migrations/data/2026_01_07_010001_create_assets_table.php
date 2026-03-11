<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel assets menyimpan data aset yang disewakan (ruangan, gedung, dll).
     * 
     * Consolidated from:
     * - 2026_01_07_010001_create_assets_table.php
     * - 2026_01_13_081326_modify_building_condition_in_assets_table.php (expanded ENUM)
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('id_gedung')->unique();
            $table->string('name');                         // Nama/deskripsi singkat aset
            $table->decimal('area_sqm', 10, 2);             // Luas dalam meter persegi
            $table->enum('building_condition', [
                'baik', 
                'cukup', 
                'rusak_ringan',
                'rusak_berat',
                'perlu_renovasi'
            ])->default('baik');                             // Kondisi bangunan (expanded)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
