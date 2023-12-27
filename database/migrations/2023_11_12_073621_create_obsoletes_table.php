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
        Schema::create('obsoletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("productID")->constrained("products", "productID");
            $table->foreignId("warehouseID")->constrained("warehouses", "warehouseID");
            $table->string("batchNumber");
            $table->date("date");
            $table->date("expiry")->nullable();
            $table->unsignedFloat("quantity");
            $table->float("loss_amount")->nullable();
            $table->float("recovery_amount")->nullable();
            $table->float("net_loss")->nullable();
            $table->text("reason")->nullable();
            $table->integer("refID");
            $table->string("createdBy");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obsoletes');
    }
};
