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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchaseID');
            $table->foreignId('supplierID')->constrained('accounts', 'accountID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->string('purchaseStatus');
            $table->string('image')->nullable();
            $table->unsignedFloat('orderTax')->nullable();
            $table->unsignedFloat('discount')->nullable();
            $table->unsignedFloat('shippingCost')->nullable();
            $table->string('description')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('purchases');
    }
};
