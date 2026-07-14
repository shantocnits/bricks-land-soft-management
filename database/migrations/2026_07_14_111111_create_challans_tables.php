<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->string('customer_type')->default('new'); // new, old
            $table->string('customer_phone')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('challan_no')->nullable();
            $table->date('date')->nullable();
            $table->string('challan_type')->default('আজকের'); // আজকের, অগ্রিম, সব
            $table->text('notes')->nullable();
            
            $table->decimal('value', 15, 2)->default(0); // sum of items (quantity * rate)
            $table->decimal('total_value', 15, 2)->default(0); // same as value or custom
            $table->decimal('rent', 15, 2)->default(0); // ভাড়া
            $table->decimal('transport_rent', 15, 2)->default(0); // গাড়ি ভাড়া
            $table->decimal('discount', 15, 2)->default(0); // ছাড়
            $table->decimal('grand_total', 15, 2)->default(0); // value + rent + transport_rent - discount
            $table->decimal('cash', 15, 2)->default(0); // নগদ
            $table->decimal('due', 15, 2)->default(0); // grand_total - cash
            
            $table->boolean('send_sms')->default(false);
            $table->timestamps();
        });

        Schema::create('challan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challan_id')->constrained('challans')->onDelete('cascade');
            $table->string('category_name')->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challan_items');
        Schema::dropIfExists('challans');
    }
};
