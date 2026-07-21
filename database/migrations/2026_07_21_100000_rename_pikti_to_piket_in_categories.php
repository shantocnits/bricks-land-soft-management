<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Rename পিকটি → পিকেট in categories table
        DB::table('categories')
            ->where('name', 'পিকটি')
            ->update(['name' => 'পিকেট']);
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('name', 'পিকেট')
            ->update(['name' => 'পিকটি']);
    }
};
