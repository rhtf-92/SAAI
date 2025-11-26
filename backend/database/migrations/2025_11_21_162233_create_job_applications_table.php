<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained('job_offers')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            
            $table->date('application_date');
            $table->tinyInteger('status')->default(1); // Pendiente, Revisado, Aceptado, Rechazado
            $table->text('cv_url')->nullable(); // documento
            $table->boolean('is_viewed')->default(false);
            $table->text('feedback')->nullable();
            $table->date('feedback_date')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
