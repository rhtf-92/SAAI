<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->integer('number'); // 1, 2, 3... (Cycle number)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_periods');
    }
};
