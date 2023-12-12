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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date');
            $table->foreignId('warehouseID')->constrained('warehouses', 'warehouseID');
            $table->unsignedFloat('shipping')->nullable();
            $table->unsignedFloat('discount')->nullable();
            $table->unsignedFloat('tax')->nullable();
            $table->string('notes')->nullable();
            $table->string('createdBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
