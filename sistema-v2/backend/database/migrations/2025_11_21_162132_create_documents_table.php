<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->nullable()->constrained('document_types')->nullOnDelete();
            $table->foreignId('student_profile_id')->nullable()->constrained('student_profiles')->nullOnDelete();
            $table->string('ubigeo_id', 6)->nullable();
            $table->foreign('ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
            
            $table->string('code', 50)->nullable(); // codigosolicitud
            $table->integer('term_days')->nullable(); // termino
            $table->integer('folio')->nullable();
            $table->text('subject')->nullable(); // asunto
            $table->string('status', 45)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
