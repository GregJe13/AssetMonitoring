<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel actual_revenues menyimpan input manual pendapatan aktual per bulan.
     * Digunakan sebagai pembanding terhadap nilai accrual yang dihitung dari kontrak.
     * Setiap bulan dalam 1 tahun hanya boleh punya 1 record (unique year+month).
     */
    public function up(): void
    {
        Schema::create('actual_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');        // Tahun (e.g., 2026)
            $table->unsignedTinyInteger('month');         // Bulan (1-12)
            $table->decimal('amount', 15, 2);             // Nilai aktual pendapatan
            $table->text('notes')->nullable();            // Catatan opsional
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete(); // User yang menginput
            $table->timestamps();

            $table->unique(['year', 'month']);            // 1 record per bulan per tahun
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actual_revenues');
    }
};
