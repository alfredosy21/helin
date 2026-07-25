<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_specialties', function (Blueprint $table) {
            if (!Schema::hasColumn('resource_specialties', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resource_specialties', function (Blueprint $table) {
            if (Schema::hasColumn('resource_specialties', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
