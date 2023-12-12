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
        Schema::create('purchaseReturns', function (Blueprint $table) {
            $table->id('purchaseReturnID');
            $table->foreignId('purchaseID')->constrained('purchases', 'purchaseID');
            $table->foreignId('accountID')->nullable()->constrained('accounts', 'accountID');
            $table->foreignId('supplierID')->constrained('accounts', 'accountID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->unsignedFloat('amount', 10,2)->nullable();
            $table->unsignedFloat('shippingCost')->nullable();
            $table->string('description')->nullable();
            $table->date('date');
            $table->string('createdBy')->nullable();
            $table->integer('refID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseReturns');
    }
};
