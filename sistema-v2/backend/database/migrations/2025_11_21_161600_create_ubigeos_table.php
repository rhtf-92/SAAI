<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubigeos', function (Blueprint $table) {
            $table->string('id', 6)->primary(); // 010101
            $table->string('department', 45);
            $table->string('province', 45);
            $table->string('district', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubigeos');
    }
};
