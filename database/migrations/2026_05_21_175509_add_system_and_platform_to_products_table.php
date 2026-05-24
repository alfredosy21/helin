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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('system_product_id')->nullable()->constrained('system_products')->onDelete('cascade');
            $table->foreignId('product_platform_id')->nullable()->constrained('product_platforms')->onDelete('cascade');

            // Indexes
            $table->index('system_product_id');
            $table->index('product_platform_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['system_product_id']);
            $table->dropForeign(['product_platform_id']);
            $table->dropColumn('system_product_id');
            $table->dropColumn('product_platform_id');
        });
    }
};
