<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pivot table untuk relasi many-to-many antara contracts dan assets.
     * Satu kontrak bisa mencakup beberapa aset sekaligus.
     */
    public function up(): void
    {
        Schema::create('contract_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained()
                  ->onDelete('cascade');                    // FK ke tabel contracts
            $table->foreignId('asset_id')
                  ->constrained()
                  ->onDelete('cascade');                    // FK ke tabel assets
            $table->decimal('rented_area_sqm', 15, 2)->nullable();  // Nilai sewa per aset (opsional breakdown)
            $table->timestamps();

            // Prevent duplicate: satu aset tidak bisa muncul 2x di kontrak yang sama
            $table->unique(['contract_id', 'asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_assets');
    }
};
