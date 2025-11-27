<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_detail_id')->constrained('enrollment_details')->cascadeOnDelete();
            $table->string('name'); // e.g., "Examen Parcial"
            $table->string('type')->nullable(); // e.g., "PARCIAL", "FINAL", "PRACTICA"
            $table->decimal('weight', 5, 2)->default(1.0); // Weightage (e.g., 0.3 for 30%)
            $table->decimal('score', 5, 2)->nullable(); // The actual grade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
