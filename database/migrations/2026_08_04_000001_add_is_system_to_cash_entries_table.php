<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_entries', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('time');
        });

        DB::table('cash_entries')
            ->whereIn('description', ['নগদ ইট বিক্রি', 'বাকি কালেকশন', 'মোট পেমেন্ট দেওয়া'])
            ->update(['is_system' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_entries', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
