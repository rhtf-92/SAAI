<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('debt_id')->nullable()->constrained()->nullOnDelete(); // Link to specific debt if applicable
            $table->decimal('amount', 10, 2);
            $table->dateTime('paid_at');
            $table->string('payment_method')->nullable(); // e.g., "CASH", "TRANSFER", "DEPOSIT"
            $table->string('operation_number')->nullable(); // Bank operation number
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
