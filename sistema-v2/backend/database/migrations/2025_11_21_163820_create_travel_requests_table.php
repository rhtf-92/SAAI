<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('year');
            
            $table->decimal('amount_requested', 18, 2)->nullable();
            
            $table->string('origin_ubigeo_id', 6)->nullable();
            $table->foreign('origin_ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
            
            $table->string('destination_ubigeo_id', 6)->nullable();
            $table->foreign('destination_ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
            
            $table->dateTime('departure_date')->nullable();
            $table->dateTime('return_date')->nullable();
            
            $table->string('reason', 255)->nullable();
            
            // Authorization
            $table->tinyInteger('authorization_status')->default(0); // 0: Pending, 1: Approved, 2: Rejected
            $table->foreignId('authorized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('authorization_date')->nullable();
            $table->string('authorization_note', 255)->nullable();
            
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};
