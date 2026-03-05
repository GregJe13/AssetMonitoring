<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->unsignedInteger('amendment_number');       // Amandemen ke-berapa
            $table->string('no_amendment')->unique();          // Nomor surat amandemen
            $table->date('date_amendment');                     // Tanggal ditandatangani

            // Old dates (snapshot dari kontrak sebelumnya)
            $table->date('old_start_date');
            $table->date('old_end_date');

            // New dates (periode amandemen)
            $table->date('new_start_date');
            $table->date('new_end_date');

            // Financial terms (independen dari kontrak induk)
            $table->decimal('total_rental_value', 15, 2);
            $table->boolean('is_upfront')->default(false);
            $table->date('payment_start_date')->nullable();
            $table->unsignedInteger('payment_interval_value')->default(1);
            $table->enum('payment_interval_unit', ['month', 'year'])->default('month');

            // Documents
            $table->string('no_bak')->nullable();
            $table->date('date_bak')->nullable();
            $table->string('file_bak')->nullable();
            $table->string('no_pks')->nullable();
            $table->date('date_pks')->nullable();
            $table->string('file_pks')->nullable();

            // Parties
            $table->string('pihak_pertama');
            $table->string('pihak_kedua');

            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'active', 'expired'])->default('active');
            $table->timestamps();

            $table->index(['contract_id', 'amendment_number']);
            $table->index('status');
            $table->index(['new_start_date', 'new_end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amendments');
    }
};
