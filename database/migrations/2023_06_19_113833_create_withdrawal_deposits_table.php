<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawalDeposits', function (Blueprint $table) {
            $table->id('withdrawalDepositID');
            $table->foreignId('accountID')->constrained('accounts', 'accountID');
            $table->string('paymentType');
            $table->unsignedFloat('amount', 10,2);
            $table->date('date');
            $table->string('description')->nullable();
            $table->integer('refID');
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawalDeposits');
    }
};
