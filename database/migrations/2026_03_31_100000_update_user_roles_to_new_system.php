<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mengubah sistem role dari 2 role (user, admin) menjadi 4 role:
     * - admin    : Full access, bisa assign/remove manager
     * - manager  : Worker access + activity log + assign/remove worker
     * - worker   : Full operational access (sebelumnya 'user')
     * - guest    : View only (dashboard & assets)
     *
     * Semua user dengan role 'user' akan di-rename menjadi 'worker'.
     * Default role untuk user baru diubah menjadi 'guest'.
     */
    public function up(): void
    {
        // Rename existing 'user' role to 'worker'
        DB::table('users')->where('role', 'user')->update(['role' => 'worker']);

        // Change default to 'guest' for new registrations
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('guest')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'worker' back to 'user'
        DB::table('users')->where('role', 'worker')->update(['role' => 'user']);

        // Revert default back to 'user'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });
    }
};
