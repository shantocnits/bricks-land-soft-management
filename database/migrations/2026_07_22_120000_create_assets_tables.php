<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('asset_categories')->onDelete('set null');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('image')->nullable();
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->integer('total_qty')->default(0);
            $table->integer('current_qty')->default(0);
            $table->integer('issued_qty')->default(0);
            $table->integer('damaged_qty')->default(0);
            $table->integer('lost_qty')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('asset_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('issued_to');
            $table->string('location')->nullable();
            $table->integer('quantity')->default(1);
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['issued', 'returned'])->default('issued');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('action_type'); // add_stock, issue, return, damaged, lost
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
        Schema::dropIfExists('asset_issues');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('asset_categories');
    }
};
