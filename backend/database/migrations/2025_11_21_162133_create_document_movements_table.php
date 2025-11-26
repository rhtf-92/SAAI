<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('origin_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('destination_area_id')->nullable()->constrained('areas')->nullOnDelete();
            
            $table->string('description', 350)->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->tinyInteger('status')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_movements');
    }
};
