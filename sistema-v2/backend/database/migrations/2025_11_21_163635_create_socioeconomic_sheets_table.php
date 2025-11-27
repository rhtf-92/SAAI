<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socioeconomic_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            
            $table->string('civil_status', 45)->nullable();
            $table->integer('num_children')->default(0);
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('occupation', 45)->nullable();
            $table->string('housing_type', 45)->nullable();
            $table->decimal('monthly_expenses', 10, 2)->nullable();
            
            $table->boolean('works')->default(false);
            $table->json('work_details')->nullable(); // empresa, rubro, cargo, contrato
            $table->json('education_details')->nullable(); // satisfaccion, motivos
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socioeconomic_sheets');
    }
};
