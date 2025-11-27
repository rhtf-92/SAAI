<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('teaching_hours')->default(0); // horas_lectivas
            $table->integer('non_teaching_hours')->default(0); // horas_nolectivas
            $table->tinyInteger('status')->default(1); // estado
            
            $table->foreignId('teacher_contract_type_id')->nullable()->constrained('teacher_contract_types')->nullOnDelete();
            $table->foreignId('teacher_type_id')->nullable()->constrained('teacher_types')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
