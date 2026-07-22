<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'vendor')) {
                $table->string('vendor')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('assets', 'has_warranty')) {
                $table->boolean('has_warranty')->default(false)->after('vendor');
            }
            if (!Schema::hasColumn('assets', 'warranty_expiry')) {
                $table->date('warranty_expiry')->nullable()->after('has_warranty');
            }
        });

        Schema::table('asset_issues', function (Blueprint $table) {
            if (!Schema::hasColumn('asset_issues', 'image')) {
                $table->string('image')->nullable()->after('status');
            }
        });

        Schema::table('asset_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('asset_histories', 'proof_image')) {
                $table->string('proof_image')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['vendor', 'has_warranty', 'warranty_expiry']);
        });

        Schema::table('asset_issues', function (Blueprint $table) {
            $table->dropColumn(['image']);
        });

        Schema::table('asset_histories', function (Blueprint $table) {
            $table->dropColumn(['proof_image']);
        });
    }
};
