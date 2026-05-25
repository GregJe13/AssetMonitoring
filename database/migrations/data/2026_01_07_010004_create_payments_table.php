<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel payments menyimpan jadwal dan status pembayaran.
     * CRUCIAL untuk Late Payment Detection:
     * - due_date: tanggal jatuh tempo
     * - payment_status: status pembayaran (pending, paid, partial, overdue, cancelled)
     * 
     * Consolidated from:
     * - 2026_01_07_010004_create_payments_table.php
     * - 2026_02_27_040002_add_amendment_id_to_payments_table.php (amendment_id FK)
     * - 2026_03_04_070000_update_payments_unique_constraint.php (unique constraint update)
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained()
                  ->onDelete('cascade');                    // FK ke tabel contracts
            $table->unsignedBigInteger('amendment_id')
                  ->nullable();                             // FK ke amendments (constraint ditambahkan setelah tabel amendments dibuat)
            $table->integer('period_number');               // Periode pembayaran (1, 2, 3, ... atau 0 jika upfront)
            $table->date('due_date');                       // Tanggal jatuh tempo
            $table->date('paid_at')->nullable();            // Tanggal pembayaran aktual
            $table->decimal('amount_due', 15, 2);           // Jumlah yang harus dibayar
            $table->decimal('amount_paid', 15, 2)->default(0);  // Jumlah yang sudah dibayar
            $table->enum('payment_status', [
                'pending',      // Belum jatuh tempo, belum dibayar
                'paid',         // Sudah dibayar lunas
                'partial',      // Dibayar sebagian
                'overdue',      // Sudah jatuh tempo, belum dibayar/lunas
                'cancelled'     // Dibatalkan
            ])->default('pending');
            $table->text('notes')->nullable();              // Catatan pembayaran
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk query Late Payment Detection
            $table->index('due_date');
            $table->index('payment_status');
            
            // Prevent duplicate: satu kontrak+amendment tidak bisa punya 2 periode yang sama
            $table->unique(['contract_id', 'amendment_id', 'period_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
