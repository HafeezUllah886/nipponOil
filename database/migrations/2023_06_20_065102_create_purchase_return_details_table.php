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
        Schema::create('purchaseReturnDetails', function (Blueprint $table) {
            $table->id('purchaseReturnDetailID');
            $table->foreignId('purchaseReturnID')->constrained('purchaseReturns', 'purchaseReturnID');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->string('batchNumber');
            $table->integer('returnQuantity');
            $table->date('expiryDate')->nullable();
            $table->unsignedFloat('deductionAmount')->nullable();
            $table->unsignedFloat('subTotal', 10,2);
            $table->string('description')->nullable();
            $table->integer('refID');
            $table->string('createdBy')->nullable();
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseReturnDetails');
    }
};
