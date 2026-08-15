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
        if (Schema::hasTable('investment_transactions') && !Schema::hasColumn('investment_transactions', 'season')) {
            Schema::table('investment_transactions', function (Blueprint $table) {
                $table->string('season')->nullable();
            });
        }

        if (Schema::hasTable('document_folders') && !Schema::hasColumn('document_folders', 'season')) {
            Schema::table('document_folders', function (Blueprint $table) {
                $table->string('season')->nullable();
            });
        }

        if (Schema::hasTable('document_files') && !Schema::hasColumn('document_files', 'season')) {
            Schema::table('document_files', function (Blueprint $table) {
                $table->string('season')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('investment_transactions') && Schema::hasColumn('investment_transactions', 'season')) {
            Schema::table('investment_transactions', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }

        if (Schema::hasTable('document_folders') && Schema::hasColumn('document_folders', 'season')) {
            Schema::table('document_folders', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }

        if (Schema::hasTable('document_files') && Schema::hasColumn('document_files', 'season')) {
            Schema::table('document_files', function (Blueprint $table) {
                $table->dropColumn('season');
            });
        }
    }
};
