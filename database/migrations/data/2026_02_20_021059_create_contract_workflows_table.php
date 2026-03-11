<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel contract_workflows menyimpan status workflow perpanjangan kontrak.
     * Setiap kontrak hanya punya satu workflow (contract_id unique).
     * 
     * Consolidated from:
     * - 2026_02_20_021059_create_contract_workflows_table.php
     * - 2026_02_27_060000_add_renewal_action_to_contract_workflows_table.php (renewal_action)
     */
    public function up(): void
    {
        Schema::create('contract_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->unique()
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('current_step')->default('confirmation_sent');
            $table->enum('branch', ['A', 'B'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('renewal_action', ['pending', 'new_contract', 'amendment'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_workflows');
    }
};
