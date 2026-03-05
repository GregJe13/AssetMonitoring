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
     * Modifying building_condition ENUM to include more options.
     */
    public function up(): void
    {
        // MySQL requires raw SQL to modify ENUM
        DB::statement("ALTER TABLE assets MODIFY COLUMN building_condition ENUM('baik', 'cukup', 'rusak_ringan', 'rusak_berat', 'perlu_renovasi') DEFAULT 'baik'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE assets MODIFY COLUMN building_condition ENUM('baik', 'cukup', 'perlu_renovasi') DEFAULT 'baik'");
    }
};
