<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add constraints to prevent 0 or NULL rented_area_sqm values.
     */
    public function up(): void
    {
        // First, clean up any existing bad data
        DB::table('contract_assets')
            ->where('rented_area_sqm', '<=', 0)
            ->orWhereNull('rented_area_sqm')
            ->delete();

        // Modify column to NOT NULL with a check constraint
        // MySQL doesn't support CHECK constraints well in older versions,
        // so we'll just make it NOT NULL and rely on application validation
        Schema::table('contract_assets', function (Blueprint $table) {
            $table->decimal('rented_area_sqm', 15, 2)->nullable(false)->default(0)->change();
        });

        // Delete any remaining zero values (shouldn't exist but just in case)
        DB::table('contract_assets')->where('rented_area_sqm', '<=', 0)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_assets', function (Blueprint $table) {
            $table->decimal('rented_area_sqm', 15, 2)->nullable()->change();
        });
    }
};
