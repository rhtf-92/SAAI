<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // Tipo de notificación (payment_received, grade_published, etc.)
            $table->string('title'); // Título de la notificación
            $table->text('message'); // Mensaje de la notificación
            $table->json('data')->nullable(); // Datos adicionales en formato JSON
            $table->timestamp('read_at')->nullable(); // Fecha de lectura
            $table->timestamps();

            // Índices para mejorar performance
            $table->index('user_id');
            $table->index('read_at');
            $table->index('created_at');
            $table->index(['user_id', 'read_at']); // Índice compuesto para queries comunes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
