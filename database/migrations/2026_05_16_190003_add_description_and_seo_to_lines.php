<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lines', function (Blueprint $table) {
            if (!Schema::hasColumn('lines', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('lines', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lines', function (Blueprint $table) {
            if (Schema::hasColumn('lines', 'seo_description')) {
                $table->dropColumn('seo_description');
            }
            if (Schema::hasColumn('lines', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
