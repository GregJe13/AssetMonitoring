<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel untuk menyimpan log aktivitas user.
     * Digunakan oleh role Manager & Admin untuk memantau aktivitas.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');              // e.g. 'created', 'updated', 'deleted'
            $table->string('model_type');           // e.g. 'App\Models\Contract'
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description');
            $table->json('properties')->nullable(); // old/new values
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
