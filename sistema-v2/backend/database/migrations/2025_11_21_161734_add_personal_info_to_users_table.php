<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('document_type', 20)->nullable()->after('email');
            $table->string('document_number', 20)->nullable()->after('document_type');
            $table->date('birthdate')->nullable()->after('document_number');
            $table->string('phone', 20)->nullable()->after('birthdate');
            $table->string('address', 255)->nullable()->after('phone');
            $table->string('ubigeo_id', 6)->nullable()->after('address');
            
            $table->foreign('ubigeo_id')->references('id')->on('ubigeos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ubigeo_id']);
            $table->dropColumn(['document_type', 'document_number', 'birthdate', 'phone', 'address', 'ubigeo_id']);
        });
    }
};
