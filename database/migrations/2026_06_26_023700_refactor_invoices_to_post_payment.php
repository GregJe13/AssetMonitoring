<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor invoices dari pre-payment (tagihan) ke post-payment (pencatatan penerimaan).
     *
     * Perubahan:
     * - Tambah `payment_date` (date, required) — tanggal uang diterima
     * - Ubah `invoice_date` menjadi nullable (tanggal invoice diterbitkan, opsional)
     * - Hapus `due_date`, `status`, `paid_at`
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // 1. Tambah kolom payment_date
            $table->date('payment_date')->after('invoice_date');
        });

        Schema::table('invoices', function (Blueprint $table) {
            // 2. Ubah invoice_date menjadi nullable
            $table->date('invoice_date')->nullable()->change();

            // 3. Hapus kolom yang tidak diperlukan lagi
            $table->dropIndex(['status']); // Drop index dulu
            $table->dropColumn(['due_date', 'status', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Restore kolom yang dihapus
            $table->date('due_date')->nullable()->after('invoice_date');
            $table->enum('status', ['draft', 'unpaid', 'paid', 'cancelled'])->default('draft')->after('due_date');
            $table->timestamp('paid_at')->nullable()->after('status');

            // Ubah invoice_date kembali ke required
            $table->date('invoice_date')->nullable(false)->change();

            // Restore index
            $table->index('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            // Hapus payment_date
            $table->dropColumn('payment_date');
        });
    }
};
