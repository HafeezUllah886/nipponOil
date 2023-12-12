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
        Schema::create('emp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empID')->constrained('employees', 'id');
            $table->date('date');
            $table->unsignedFloat('credit', 10,2)->nullable();
            $table->unsignedFloat('debt', 10,2)->nullable();
            $table->integer('refID');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_transactions');
    }
};
