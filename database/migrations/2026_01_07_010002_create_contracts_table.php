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
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')                  //tambah security deposit (uang jaminan kerusakan gedung), nilai tergantung negosiasi, ada tidak tergantung masa sewa
                  ->constrained()                           //kontrak number aja, gausah tambah id lagi. jadi id langsung berisi contract number
                  ->onDelete('cascade');                    // FK ke tabel tenants
            $table->string('no_bak')->unique()->nullable(); // Nomor kontrak (unique)
            $table->date('date_bak')->nullable();
            $table->string('no_pks')->nullable()->unique(); // Nomor kontrak (unique)
            $table->date('date_pks')->nullable();
            $table->date('start_date');                     // Tanggal mulai kontrak
            $table->date('end_date');                       // Tanggal berakhir kontrak
            $table->decimal('total_rental_value', 15, 2);   // Total nilai sewa sebelum pajak
            $table->decimal('security_deposit', 15, 2)->nullable();     // Uang jaminan kerusakan gedung

            // Flexible Payment Frequency
            $table->boolean('is_upfront')->default(false);  // True = bayar 100% dimuka
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
