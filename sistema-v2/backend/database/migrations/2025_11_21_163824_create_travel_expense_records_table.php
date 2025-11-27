<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_expense_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            $table->foreignId('travel_expense_concept_id')->constrained('travel_expense_concepts')->cascadeOnDelete();
            
            $table->string('voucher_number', 50)->nullable();
            $table->dateTime('voucher_date')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->string('note', 255)->nullable();
            
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_expense_records');
    }
};
