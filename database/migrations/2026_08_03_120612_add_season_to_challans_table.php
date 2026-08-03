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
        if (!Schema::hasColumn('challans', 'season')) {
            Schema::table('challans', function (Blueprint $table) {
                $table->string('season')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('challans', 'season')) {
            Schema::table('challans', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }
    }
};
