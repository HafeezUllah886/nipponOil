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
        Schema::create('saleReturnDetails', function (Blueprint $table) {
            $table->id('saleReturnDetailID');
            $table->foreignId('saleReturnID')->constrained('saleReturns', 'saleReturnID');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->string('batchNumber');
            $table->integer('returnQuantity');
            $table->date('expiryDate')->nullable();
            $table->unsignedFloat('deductionAmount')->nullable();
            $table->unsignedFloat('subTotal', 10,2);
            $table->string('description')->nullable();
            $table->integer('refID');
            $table->foreignId('salesManID')->constrained('employees', 'id');
            $table->float('commission',10,2)->nullable();
            $table->date('date');
            $table->string('createdBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleReturnDetails');
    }
};
