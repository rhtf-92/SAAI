<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->cascadeOnDelete();
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->nullOnDelete();
            $table->string('ubigeo_id', 6)->nullable();
            $table->foreign('ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
            
            $table->string('title', 255); // puesto
            $table->integer('vacancies')->default(1);
            $table->string('type', 45)->nullable(); // Full-time, Part-time
            $table->string('modality', 45)->nullable(); // Presencial, Remoto
            $table->string('experience', 45)->nullable();
            $table->date('publication_date')->nullable();
            $table->date('closing_date')->nullable();
            
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            
            $table->string('education_level', 45)->nullable();
            $table->text('external_link')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
