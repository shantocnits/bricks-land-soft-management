<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // The three system rows (নগদ ইট বিক্রি, বাকি কালেকশন, মোট পেমেন্ট দেওয়া) are now
        // computed dynamically in real-time from চালান & বাকি খাতা modules, so the old
        // static placeholder rows are no longer needed.
        DB::table('cash_entries')->where('is_system', true)->delete();
    }

    public function down(): void
    {
        // Nothing to restore — the rows are derived dynamically at render time.
    }
};
