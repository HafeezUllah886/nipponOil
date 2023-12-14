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
            $table->float('ltr');
            $table->string('grade');
            $table->integer('alertQuantity')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
