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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empID')->constrained('employees', 'id');
            $table->foreignId('accountID')->nullable()->constrained('accounts', 'accountID');
            $table->date('genDate')->nullable();
            $table->date('issueDate')->nullable();
            $table->string('month')->nullable();
            $table->unsignedBigInteger('salary');
            $table->unsignedBigInteger('commission')->nullable();
            $table->unsignedBigInteger('return_commission')->nullable();
            $table->unsignedBigInteger('fine')->nullable();
            $table->unsignedBigInteger('advance')->nullable();
            $table->unsignedBigInteger('adv_payment')->nullable();
            $table->unsignedBigInteger('adv_deduction_amount')->nullable();
            $table->unsignedBigInteger('adv_balance')->nullable();
            $table->float('adv_deduction')->nullable();
            $table->integer('workingDays')->nullable();
            $table->integer('absenties')->nullable();
            $table->integer('fineRate')->nullable();
            $table->integer('sales')->nullable();
            $table->integer('returns')->nullable();
            $table->bigInteger('netSalary')->nullable();
            $table->text('notes')->nullable();
            $table->string('status');
            $table->string('createdBy')->nullable();
            $table->integer('refID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
