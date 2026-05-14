<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes for search optimization
            $table->index('name', 'categories_name_index'); // For name searches
            $table->index('parent_id', 'categories_parent_id_index'); // For parent-child queries
            $table->index(['is_active', 'order'], 'categories_active_order_index'); // For active listings with ordering
            $table->index(['parent_id', 'is_active'], 'categories_parent_active_index'); // For active child categories
            $table->index('created_at', 'categories_created_at_index'); // For sorting by date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
