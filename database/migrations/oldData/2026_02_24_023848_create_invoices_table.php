<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel invoices menyimpan data pencatatan penerimaan pembayaran.
     * 
     * Consolidated from:
     * - 2026_02_24_023848_create_invoices_table.php
     * - 2026_02_24_030701_add_paid_at_to_invoices_table.php (removed)
     * - 2026_06_26_023700_refactor_invoices_to_post_payment.php (refactored)
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tenant_name_manual')->nullable();
            $table->date('invoice_date')->nullable();       // Tanggal invoice diterbitkan (opsional)
            $table->date('payment_date');                    // Tanggal uang diterima (wajib)
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_date');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
