<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel contracts menyimpan data kontrak sewa.
     * Mendukung frekuensi pembayaran fleksibel:
     * - Bulanan, per 3 bulan, per semester, tahunan
     * - Atau 100% bayar dimuka (is_upfront = true)
     * 
     * Consolidated from:
     * - 2026_01_07_010002_create_contracts_table.php
     * - 2026_01_21_070213_add_file_columns_to_contracts_table.php (file_bak, file_pks)
     * - 2026_02_02_090000_add_renewal_notes_to_contracts_table.php (renewal_notes)
     * - 2026_02_09_040000_add_payment_start_date_to_contracts_table.php (payment_start_date)
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                  ->constrained()
                  ->onDelete('cascade');                    // FK ke tabel tenants
            $table->string('no_bak')->unique()->nullable(); // Nomor BAK (unique)
            $table->date('date_bak')->nullable();
            $table->string('file_bak')->nullable();         // Path file PDF BAK
            $table->string('no_pks')->nullable()->unique(); // Nomor PKS (unique)
            $table->date('date_pks')->nullable();
            $table->string('file_pks')->nullable();         // Path file PDF PKS
            $table->date('start_date');                     // Tanggal mulai kontrak
            $table->date('end_date');                       // Tanggal berakhir kontrak
            $table->decimal('total_rental_value', 15, 2);   // Total nilai sewa sebelum pajak
            $table->decimal('security_deposit', 15, 2)->nullable(); // Uang jaminan kerusakan gedung

            // Flexible Payment Frequency
            $table->boolean('is_upfront')->default(false);  // True = bayar 100% dimuka
            $table->date('payment_start_date')->nullable(); // Kapan jadwal pembayaran dimulai (fallback ke start_date)
            $table->unsignedInteger('payment_interval_value')->default(1);  // Nilai interval (contoh: 3)
            $table->enum('payment_interval_unit', [
                'month', 
                'year'
            ])->default('month');                           // Unit interval (month/year)
            // Contoh kombinasi:
            // - interval_value=1, interval_unit=month → bayar bulanan
            // - interval_value=3, interval_unit=month → bayar per 3 bulan
            // - interval_value=1, interval_unit=year → bayar tahunan
            // - is_upfront=true → bayar 100% dimuka (abaikan interval)

            $table->enum('status', [
                'draft',        // Kontrak masih draft
                'active',       // Kontrak aktif berjalan
                'expired',      // Kontrak sudah berakhir
                'terminated'    // Kontrak dihentikan sebelum waktunya
            ])->default('draft');
            $table->string('pihak_pertama');
            $table->string('pihak_kedua');
            $table->text('renewal_notes')->nullable();      // Progress perpanjangan/penghentian kontrak
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk query performance
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
