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
        if (!Schema::hasColumn('payments', 'season')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('season')->nullable()->after('ledger');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payments', 'season')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }
    }
};
