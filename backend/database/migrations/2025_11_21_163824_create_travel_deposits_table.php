<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            
            $table->dateTime('deposit_date')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            
            $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete();
            $table->string('account_number', 100)->nullable();
            $table->string('note', 255)->nullable();
            
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_deposits');
    }
};
