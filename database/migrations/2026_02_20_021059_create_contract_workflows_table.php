<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_workflows');
    }
};
