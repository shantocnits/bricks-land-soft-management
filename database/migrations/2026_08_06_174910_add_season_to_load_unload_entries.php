<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Add season to load_entries
        Schema::table('load_entries', function (Blueprint $table) {
            $table->string('season')->nullable()->after('date');
        });

        // Add season to unload_entries
        Schema::table('unload_entries', function (Blueprint $table) {
            $table->string('season')->nullable()->after('date');
        });

        // Seed existing rows with the currently active season
        $activeSeason = Setting::get('season', '২৫-২৬');
        DB::table('load_entries')->whereNull('season')->update(['season' => $activeSeason]);
        DB::table('unload_entries')->whereNull('season')->update(['season' => $activeSeason]);
    }

    public function down(): void
    {
        Schema::table('load_entries', function (Blueprint $table) {
            $table->dropColumn('season');
        });
        Schema::table('unload_entries', function (Blueprint $table) {
            $table->dropColumn('season');
        });
    }
};
