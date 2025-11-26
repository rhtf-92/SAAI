<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_plan_id')->constrained('admission_plans')->cascadeOnDelete();
            $table->foreignId('identification_type_id')->nullable()->constrained('identification_types')->nullOnDelete();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
            $table->string('ubigeo_id', 6)->nullable();
            $table->foreign('ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
            
            $table->string('id_number', 45);
            $table->string('names', 45);
            $table->string('paternal_surname', 45);
            $table->string('maternal_surname', 45);
            $table->date('birthdate');
            $table->string('email', 45);
            $table->string('phone', 45)->nullable();
            $table->string('cell_phone', 45)->nullable();
            $table->string('address', 255)->nullable();
            
            // Document URLs
            $table->text('dni_url')->nullable();
            $table->text('certificate_url')->nullable();
            $table->text('voucher_url')->nullable();
            $table->text('photo_url')->nullable();
            
            $table->decimal('score', 5, 2)->nullable();
            $table->tinyInteger('status')->default(1); // Inscrito, Apto, Ingresante, No Ingresante
            $table->string('modality', 45)->nullable(); // Ordinario, Exonerado
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
