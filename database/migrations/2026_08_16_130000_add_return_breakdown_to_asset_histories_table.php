<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('asset_histories', 'good_qty')) {
                $table->integer('good_qty')->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('asset_histories', 'damaged_qty')) {
                $table->integer('damaged_qty')->default(0)->after('good_qty');
            }
            if (!Schema::hasColumn('asset_histories', 'lost_qty')) {
                $table->integer('lost_qty')->default(0)->after('damaged_qty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asset_histories', function (Blueprint $table) {
            $table->dropColumn(['good_qty', 'damaged_qty', 'lost_qty']);
        });
    }
};
