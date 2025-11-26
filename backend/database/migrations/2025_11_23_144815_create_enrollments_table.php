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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete(); // Calendar Semester
            $table->foreignId('plan_id')->nullable()->constrained(); // Snapshot of plan
            $table->foreignId('academic_period_id')->nullable()->constrained(); // Target Cycle (e.g. Ciclo III)
            $table->date('date');
            $table->string('status')->default('active'); // active, withdrawn, cancelled
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
