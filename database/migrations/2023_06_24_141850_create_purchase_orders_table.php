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
        Schema::create('purchaseOrders', function (Blueprint $table) {
            $table->id('purchaseOrderID');
            $table->foreignId('purchaseID')->constrained('purchases', 'purchaseID');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->string('code');
            $table->integer('quantity');
            $table->string('batchNumber')->nullable();
            $table->date('expiryDate')->nullable();
            $table->unsignedFloat('netUnitCost');
            $table->unsignedFloat('discount')->nullable();
            $table->unsignedFloat('tax')->nullable();
            $table->unsignedFloat('subTotal', 10,2);
            $table->foreignId('purchaseUnit')->constrained('units', 'unitID');
            $table->date('date');
            $table->timestamps();
            $table->string('createdBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseOrders');
    }
};
