<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // SEO fields (web uses seo_description/seo_keywords, DB has meta_description/meta_keywords)
            if (!Schema::hasColumn('products', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('products', 'seo_keywords')) {
                $table->text('seo_keywords')->nullable()->after('seo_description');
            }
            
            // Material field for filtering
            if (!Schema::hasColumn('products', 'material')) {
                $table->string('material')->nullable()->after('unit');
            }
            
            // Biomaterial flag for tagging
            if (!Schema::hasColumn('products', 'is_biomaterial')) {
                $table->boolean('is_biomaterial')->default(false)->after('material');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'seo_keywords')) {
                $table->dropColumn('seo_keywords');
            }
            if (Schema::hasColumn('products', 'seo_description')) {
                $table->dropColumn('seo_description');
            }
            if (Schema::hasColumn('products', 'is_biomaterial')) {
                $table->dropColumn('is_biomaterial');
            }
            if (Schema::hasColumn('products', 'material')) {
                $table->dropColumn('material');
            }
        });
    }
};
