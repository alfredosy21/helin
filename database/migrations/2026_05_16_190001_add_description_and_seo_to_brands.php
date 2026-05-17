<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('brands', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'seo_description')) {
                $table->dropColumn('seo_description');
            }
            if (Schema::hasColumn('brands', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
