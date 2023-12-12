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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('stockID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->string('batchNumber')->nullable();
            $table->date('expiryDate')->nullable();
            $table->date('date')->nullable();
            $table->string('credit')->nullable();
            $table->string('debt')->nullable();
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
        Schema::dropIfExists('stocks');
    }
};
