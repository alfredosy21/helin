<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('settings', 'opinion_url')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('opinion_url')->nullable()->after('analytics_code');
            });
        }
        if (!Schema::hasColumn('settings', 'offices')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->json('offices')->nullable()->after('opinion_url');
            });
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'opinion_url')) {
                $table->dropColumn('opinion_url');
            }
            if (Schema::hasColumn('settings', 'offices')) {
                $table->dropColumn('offices');
            }
        });
    }
};