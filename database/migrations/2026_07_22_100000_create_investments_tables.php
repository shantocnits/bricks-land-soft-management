<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('total_invested', 15, 2)->default(0);
            $table->decimal('total_repaid', 15, 2)->default(0);
            $table->decimal('profit_percentage', 15, 2)->default(0);
            $table->string('status')->default('active'); // active, closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('investment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->enum('transaction_type', ['deposit', 'profit_payout', 'capital_return'])->default('deposit');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('date');
            $table->string('payment_method')->default('নগদ'); // নগদ, ব্যাংক, বিকাশ, ইত্যাদি
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_transactions');
        Schema::dropIfExists('investors');
    }
};
