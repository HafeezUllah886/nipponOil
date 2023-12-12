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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empID')->constrained('employees', 'id');
            $table->foreignId('accountID')->constrained('accounts', 'accountID');
            $table->date('date');
            $table->bigInteger('amount');
            $table->unsignedFloat('deduction')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('advances');
    }
};
