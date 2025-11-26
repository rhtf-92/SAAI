<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->string('name', 45);
            $table->string('type', 45)->nullable();
            $table->string('modality', 45)->nullable();
            $table->string('focus', 45)->nullable(); // enfoque
            $table->date('date')->nullable();
            $table->text('document_url')->nullable(); // documento
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
