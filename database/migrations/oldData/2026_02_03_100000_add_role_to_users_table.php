<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom role pada tabel users untuk multi-role support.
     * 
     * Consolidated from:
     * - 2026_02_03_100000_add_role_to_users_table.php
     * 
     * Note: Tabel users sudah ada dari Laravel default migrations.
     * Migration ini hanya menambahkan kolom role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password'); // Role: user, admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
