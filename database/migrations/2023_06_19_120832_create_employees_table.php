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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status');
            $table->text('address')->nullable();
            $table->string("salary_type");
            $table->unsignedBigInteger('salary')->nullable();
            $table->string('image')->nullable();
            $table->date('doe');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
