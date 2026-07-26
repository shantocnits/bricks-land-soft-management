<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deuna_transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deuna_transaction_id')->constrained('deuna_transactions')->onDelete('cascade');
            $table->string('type')->default('initial'); // 'initial', 'new_loan', 'payment'
            $table->date('transaction_date')->nullable();
            $table->text('description')->nullable();
            $table->decimal('given_amount', 15, 2)->default(0);
            $table->decimal('received_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deuna_transaction_histories');
    }
};
