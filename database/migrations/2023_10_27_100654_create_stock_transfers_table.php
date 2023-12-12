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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from')->constrained('warehouses', 'warehouseID');
            $table->foreignId('to')->constrained('warehouses', 'warehouseID');
            $table->date('date');
            $table->string('status');
            $table->integer('refID');
            $table->string('createdBy');
            $table->string('acceptedBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
