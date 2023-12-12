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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("warehouseID")->constrained('warehouses', 'warehouseID');
            $table->string("customerName");
            $table->string("contact");
            $table->string("product");
            $table->string("accessories");
            $table->string("fault");
            $table->float("charges");
            $table->date("date");
            $table->date("returnDate");
            $table->string("createdBy");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
