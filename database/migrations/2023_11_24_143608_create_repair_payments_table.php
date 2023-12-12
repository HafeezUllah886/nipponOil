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
        Schema::create('repair_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repairID')->constrained("repairs", "id");
            $table->foreignId('accountID')->constrained("accounts", "accountID");
            $table->float("amount");
            $table->date("date");
            $table->integer('refID');
            $table->text("notes")->nullable();
            $table->string("createdBy");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_payments');
    }
};
