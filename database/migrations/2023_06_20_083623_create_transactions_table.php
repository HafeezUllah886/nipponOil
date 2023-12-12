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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transactionID');
            $table->foreignId('accountID')->constrained('accounts', 'accountID');
            $table->date('date');
            $table->string('type');
            $table->unsignedFloat('credit', 10,2)->nullable();
            $table->unsignedFloat('debt', 10,2)->nullable();
            $table->integer('refID');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->string('createdBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
