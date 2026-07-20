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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('ledger');
            $table->string('desc')->nullable();
            $table->decimal('qty', 15, 2)->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('advance', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->decimal('payment', 15, 2)->default(0);
            $table->decimal('purchase_receive', 15, 2)->default(0);
            $table->string('doc_url')->nullable();
            $table->boolean('has_doc')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
