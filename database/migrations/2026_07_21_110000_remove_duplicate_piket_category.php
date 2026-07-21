<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove duplicate পিকেট entries — keep only the one with the lowest id
        $ids = DB::table('categories')
            ->where('name', 'পিকেট')
            ->orderBy('id')
            ->pluck('id');

        if ($ids->count() > 1) {
            // Keep the first one, delete the rest
            DB::table('categories')
                ->where('name', 'পিকেট')
                ->whereNotIn('id', [$ids->first()])
                ->delete();
        }
    }

    public function down(): void
    {
        // Nothing to reverse
    }
};
