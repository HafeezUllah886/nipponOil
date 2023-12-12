<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('productID');
            $table->string('name');
            $table->string('code');
            $table->string('defaultBatch')->nullable();
            $table->foreignId('brandID')->constrained('brands', 'brandID');
            $table->foreignId('categoryID')->constrained('categories', 'categoryID');
            $table->tinyInteger('isExpire');
            $table->integer('productUnit');
            $table->unsignedFloat('purchasePrice');
            $table->unsignedFloat('salePrice');
            $table->unsignedFloat('wholeSalePrice');
            $table->integer('alertQuantity')->nullable();
            $table->float('commission', 10,2)->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
