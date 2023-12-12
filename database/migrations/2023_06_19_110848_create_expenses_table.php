<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('expenseID');
            $table->foreignId('expenseCategoryID')->constrained('expensecategories', 'expenseCategoryID');
            $table->foreignId('accountID')->constrained('accounts', 'accountID');
            $table->foreignId("warehouseID")->constrained("warehouses", "warehouseID");
            $table->unsignedFloat('amount', 10,2);
            $table->date('date');
            $table->string('description')->nullable();
            $table->integer('refID');
            $table->string('createdBy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
