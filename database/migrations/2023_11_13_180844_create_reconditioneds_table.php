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
        Schema::create('reconditioneds', function (Blueprint $table) {
            $table->id();
            $table->foreignId("obsoleteID")->constrained('obsoletes', 'id');
            $table->foreignId("productID")->constrained('products', 'productID');
            $table->foreignId("warehouseID")->constrained('warehouses', 'warehouseID');
            $table->date('date');
            $table->string("batchNumber");
            $table->date("expiry")->nullable();
            $table->unsignedFloat("quantity");
            $table->unsignedFloat("expense");
            $table->foreignId("accountID")->constrained("accounts", "accountID");
            $table->text("notes")->nullable();
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
        Schema::dropIfExists('reconditioneds');
    }
};
