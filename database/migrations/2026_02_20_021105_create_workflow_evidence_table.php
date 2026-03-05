<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')
                  ->constrained('contract_workflows')
                  ->onDelete('cascade');
            $table->string('step');
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_evidence');
    }
};
