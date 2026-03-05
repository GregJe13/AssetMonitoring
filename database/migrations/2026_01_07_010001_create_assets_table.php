<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel assets menyimpan data aset yang disewakan (ruangan, gedung, dll).
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();                                   //id akan berformat 77/255-kode_gedung(001)-lantai(10)
            $table->string('id_gedung')->unique();
            $table->string('name');                         // Nama/deskripsi singkat aset
            $table->decimal('area_sqm', 10, 2);             // Luas dalam meter persegi
            $table->enum('building_condition', [
                'baik', 
                'cukup', 
                'perlu_renovasi'
            ])->default('baik');                            // Kondisi bangunan
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
