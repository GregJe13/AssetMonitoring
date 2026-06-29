<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pivot table untuk relasi many-to-many antara amendments dan assets.
     */
    public function up(): void
    {
        Schema::create('amendment_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amendment_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('asset_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('rented_area_sqm', 10, 2);
            $table->timestamps();

            $table->unique(['amendment_id', 'asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amendment_assets');
    }
};
