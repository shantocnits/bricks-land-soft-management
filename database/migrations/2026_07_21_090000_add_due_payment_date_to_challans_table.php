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
        if (!Schema::hasColumn('challans', 'due_payment_date')) {
            Schema::table('challans', function (Blueprint $table) {
                $table->date('due_payment_date')->nullable()->after('due');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('challans', 'due_payment_date')) {
            Schema::table('challans', function (Blueprint $table) {
                $table->dropColumn('due_payment_date');
            });
        }
    }
};
