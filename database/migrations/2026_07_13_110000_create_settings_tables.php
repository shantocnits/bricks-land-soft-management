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
        // 1. Settings table for key-value settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 2. Categories & Rates table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // e.g. ইট, আধলা, অন্যান্য
            $table->decimal('rate', 8, 2);
            $table->timestamps();
        });

        // 3. Ledgers/Accounts table
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group');
            $table->decimal('rate', 8, 2)->nullable();
            $table->integer('divisor')->default(1); // পরিমাণ ভাজক
            $table->timestamps();
        });

        // 4. User Transaction Limits table
        Schema::create('user_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('limit_type'); // e.g. দৈনিক, মাসিক, ডিসকাউন্ট
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_limits');
        Schema::dropIfExists('ledgers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
    }
};
