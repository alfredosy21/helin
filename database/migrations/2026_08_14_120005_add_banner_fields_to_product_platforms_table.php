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
        Schema::table('product_platforms', function (Blueprint $table) {
            $table->string('image')->nullable()->after('slug');
            $table->text('seo_keywords')->nullable()->after('seo_description');
            $table->string('banner_title')->nullable()->after('seo_keywords');
            $table->text('banner_description')->nullable()->after('banner_title');
            $table->string('banner_image')->nullable()->after('banner_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_platforms', function (Blueprint $table) {
            $table->dropColumn(['image', 'seo_keywords', 'banner_title', 'banner_description', 'banner_image']);
        });
    }
};
