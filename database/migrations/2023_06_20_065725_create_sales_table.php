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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('saleID');
            $table->foreignId('customerID')->constrained('accounts', 'accountID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->foreignId('salesManID')->constrained('employees', 'id');
            $table->string('saleStatus');
            $table->integer('referenceNo')->nullable();
            $table->unsignedFloat('shippingCost')->nullable();
            $table->unsignedFloat('discountValue')->nullable();
            $table->unsignedFloat('orderTax')->nullable();
            $table->string('description')->nullable();
            $table->string('points')->nullable();
            $table->string('orderDiscountType')->nullable();
            $table->integer('refID');
            $table->date('date');
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
