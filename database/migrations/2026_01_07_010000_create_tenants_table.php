<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel tenants menyimpan data penyewa/perusahaan yang menyewa aset.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Nama tenant/perusahaan
            $table->integer('id_tenant')->nullable();       // id tenant/perusahaan
            $table->string('phone')->nullable();            // Nomor telepon
            $table->string('email')->nullable();            // Email
            $table->string('npwp')->nullable();             // NPWP tambah nama pic, kontak pic
            $table->string('pic')->nullable();              // PIC
            $table->string('pic_phone')->nullable();        // Kontak PIC
            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
