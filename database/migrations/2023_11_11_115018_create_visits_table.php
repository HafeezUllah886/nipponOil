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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId("warehouseID")->constrained("warehouses", "warehouseID");
            $table->foreignId("visit_by")->constrained("employees", "id");
            $table->string("visit_to");
            $table->date("date");
            $table->bigInteger("exp")->default(0);
            $table->foreignId("account")->constrained("accounts", "accountID");
            $table->text("notes")->nullable();
            $table->integer("refID");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
