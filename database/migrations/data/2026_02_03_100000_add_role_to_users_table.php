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
     * Safe migration: tidak akan error jika kolom sudah ada,
     * dan tidak akan menghapus data users saat rollback.
     * 
     * Consolidated from:
     * - 2026_02_03_100000_add_role_to_users_table.php
     * - 2026_03_31_100000_update_user_roles_to_new_system.php (default role guest, 4 tiers: admin, manager, worker, guest)
     * 
     * Note: Tabel users sudah ada dari Laravel default migrations.
     * Migration ini hanya menambahkan kolom role.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('guest')->after('password'); // Role: admin, manager, worker, guest
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Sengaja tidak menghapus kolom role agar data users tetap utuh
     * saat rollback. Kolom role akan tetap ada di tabel users.
     */
    public function down(): void
    {
        // Tidak menghapus kolom role untuk menjaga data users yang sudah ada
    }
};
