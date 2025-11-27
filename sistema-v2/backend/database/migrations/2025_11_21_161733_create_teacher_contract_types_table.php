<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_contract_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45); // tipo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_contract_types');
    }
};
