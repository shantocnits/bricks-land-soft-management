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
        Schema::create('unload_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('round');
            $table->timestamps();

            $table->unique(['date', 'round']);
        });

        Schema::create('unload_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unload_entry_id')->constrained('unload_entries')->onDelete('cascade');
            $table->string('category_name');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unload_items');
        Schema::dropIfExists('unload_entries');
    }
};
