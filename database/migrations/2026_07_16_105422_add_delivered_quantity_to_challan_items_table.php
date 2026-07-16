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
        Schema::table('challan_items', function (Blueprint $table) {
            if (!Schema::hasColumn('challan_items', 'delivered_quantity')) {
                $table->integer('delivered_quantity')->default(0)->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challan_items', function (Blueprint $table) {
            $table->dropColumn('delivered_quantity');
        });
    }
};
