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
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empID')->constrained('employees', 'id');
            $table->foreignId('advanceID')->constrained('advances', 'id');
            $table->foreignId('accountID')->constrained('accounts', 'accountID');
            $table->unsignedFloat('amount', 10,2);
            $table->date('date');
            $table->string('description')->nullable();
            $table->integer('refID');
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
