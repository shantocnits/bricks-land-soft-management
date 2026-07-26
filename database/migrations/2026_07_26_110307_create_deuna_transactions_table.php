<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deuna_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ledger_name');          // লেনদেনের নাম (ব্যক্তি/প্রতিষ্ঠান)
            $table->string('transaction_type');     // 'দেওয়া' or 'নেওয়া'
            $table->string('address')->nullable();   // ঠিকানা
            $table->string('phone')->nullable();     // ফোন নম্বর
            $table->decimal('amount', 15, 2)->default(0); // টাকার পরিমাণ
            $table->date('start_date')->nullable();         // লেনদেনের শুরু তারিখ
            $table->date('transaction_date')->nullable();   // লেনদেনের তারিখ
            $table->date('due_date')->nullable();           // পরিশোধের তারিখ
            $table->string('row1')->nullable();      // সারি ১
            $table->string('row2')->nullable();      // সারি ২
            $table->text('description')->nullable(); // লেনদেনের কারণ / বর্ণনা
            $table->decimal('paid_amount', 15, 2)->default(0); // পরিশোধিত
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deuna_transactions');
    }
};
