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
        Schema::create('saleOrders', function (Blueprint $table) {
            $table->id('saleOrderID');
            $table->foreignId('saleID')->constrained('sales', 'saleID');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->integer('quantity');
            $table->string('code');
            $table->string('batchNumber')->nullable();
            $table->date('expiryDate')->nullable();
            $table->unsignedFloat('netUnitCost');
            $table->unsignedFloat('discountValue')->nullable();
            $table->unsignedFloat('tax')->nullable();
            $table->unsignedFloat('subTotal', 10,2);
            $table->foreignId('saleUnit')->constrained('units', 'unitID');
            $table->foreignId('salesManID')->constrained('employees', 'id');
            $table->float('commission',10,2)->nullable();
            $table->string('createdBy')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleOrders');
    }
};
