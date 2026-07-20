<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('load_rounds', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default rounds
        DB::table('load_rounds')->insert([
            ['name' => '-১ নম্বর রাউন্ড', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '৫৪ নম্বর রাউন্ড', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '৫৫ নম্বর রাউন্ড', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '৫৬ নম্বর রাউন্ড', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('load_rounds');
    }
};
