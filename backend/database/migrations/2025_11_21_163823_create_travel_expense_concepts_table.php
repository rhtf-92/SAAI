<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_expense_concepts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // Seed initial concepts
        DB::table('travel_expense_concepts')->insert([
            ['name' => 'Alimentos', 'status' => 1],
            ['name' => 'Movilidad', 'status' => 1],
            ['name' => 'Alquiler', 'status' => 1],
            ['name' => 'Combustible', 'status' => 1],
            ['name' => 'Viaje', 'status' => 1],
            ['name' => 'Capacitación', 'status' => 1],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_expense_concepts');
    }
};
