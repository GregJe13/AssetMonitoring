<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_workflows', function (Blueprint $table) {
            $table->enum('renewal_action', ['pending', 'new_contract', 'amendment'])
                  ->nullable()
                  ->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('contract_workflows', function (Blueprint $table) {
            $table->dropColumn('renewal_action');
        });
    }
};
