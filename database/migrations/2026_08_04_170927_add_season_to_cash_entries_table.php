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
        if (!Schema::hasColumn('cash_entries', 'season')) {
            Schema::table('cash_entries', function (Blueprint $table) {
                $table->string('season')->nullable()->after('time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('cash_entries', 'season')) {
            Schema::table('cash_entries', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }
    }
};
