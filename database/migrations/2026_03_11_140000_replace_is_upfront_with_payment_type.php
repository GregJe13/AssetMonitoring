<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace is_upfront boolean with payment_type ENUM on contracts and amendments.
     * 
     * Mapping:
     * - is_upfront = true  → payment_type = 'upfront'
     * - is_upfront = false → payment_type = 'interval'
     * - (new)              → payment_type = 'termin'
     */
    public function up(): void
    {
        // --- Contracts ---
        Schema::table('contracts', function (Blueprint $table) {
            $table->enum('payment_type', ['upfront', 'interval', 'termin'])
                  ->default('interval')
                  ->after('security_deposit');
        });

        // Migrate existing data
        DB::table('contracts')->where('is_upfront', true)->update(['payment_type' => 'upfront']);
        DB::table('contracts')->where('is_upfront', false)->update(['payment_type' => 'interval']);

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('is_upfront');
        });

        // --- Amendments ---
        Schema::table('amendments', function (Blueprint $table) {
            $table->enum('payment_type', ['upfront', 'interval', 'termin'])
                  ->default('interval')
                  ->after('total_rental_value');
        });

        DB::table('amendments')->where('is_upfront', true)->update(['payment_type' => 'upfront']);
        DB::table('amendments')->where('is_upfront', false)->update(['payment_type' => 'interval']);

        Schema::table('amendments', function (Blueprint $table) {
            $table->dropColumn('is_upfront');
        });
    }

    public function down(): void
    {
        // --- Contracts ---
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('is_upfront')->default(false)->after('security_deposit');
        });

        DB::table('contracts')->where('payment_type', 'upfront')->update(['is_upfront' => true]);

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });

        // --- Amendments ---
        Schema::table('amendments', function (Blueprint $table) {
            $table->boolean('is_upfront')->default(false)->after('total_rental_value');
        });

        DB::table('amendments')->where('payment_type', 'upfront')->update(['is_upfront' => true]);

        Schema::table('amendments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
};
