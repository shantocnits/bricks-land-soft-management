<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_no')->nullable();
            $table->foreignId('challan_id')->constrained('challans')->onDelete('cascade');
            $table->foreignId('challan_item_id')->nullable()->constrained('challan_items')->onDelete('cascade');
            $table->string('category_name')->nullable();
            $table->integer('quantity')->default(0);
            $table->date('delivery_date')->nullable();
            $table->date('next_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->decimal('vehicle_rent', 15, 2)->default(0);
            $table->boolean('sms_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
