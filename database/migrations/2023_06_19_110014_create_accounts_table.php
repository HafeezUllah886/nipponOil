<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('accountID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->string('name');
            $table->string('type');
            $table->string('category')->nullable();
            $table->string('area')->nullable();
            $table->string('status')->default('Active');
            $table->string('accountNumber')->nullable();
            $table->unsignedFloat('initialBalance')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
