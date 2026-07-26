<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->text('message');
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('status')->default('Failed');
            $table->timestamps();
        });

        Schema::create('sms_recharges', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method');
            $table->string('sender_phone');
            $table->string('trx_id');
            $table->decimal('amount', 10, 2);
            $table->integer('sms_count');
            $table->string('status')->default('Cancelled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_recharges');
        Schema::dropIfExists('sms_logs');
    }
};
