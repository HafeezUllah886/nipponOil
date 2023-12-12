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
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotationID')->constrained('quotations', 'id');
            $table->foreignId('productID')->constrained('products', 'productID');
            $table->unsignedFloat('discount')->nullable();
            $table->unsignedFloat('tax')->nullable();
            $table->unsignedFloat('price');
            $table->unsignedFloat('qty');
            $table->unsignedFloat('net');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_details');
    }
};
