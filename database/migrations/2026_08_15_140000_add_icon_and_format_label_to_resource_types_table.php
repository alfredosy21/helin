<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade campos icon y format_label a resource_types para gestionar
     * los iconos y etiquetas de formato desde la BD en lugar de hardcoded maps.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('resource_types', 'icon')) {
            Schema::table('resource_types', function (Blueprint $table) {
                $table->string('icon')->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('resource_types', 'format_label')) {
            Schema::table('resource_types', function (Blueprint $table) {
                $table->string('format_label')->nullable()->after('icon');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_types', function (Blueprint $table) {
            if (Schema::hasColumn('resource_types', 'format_label')) {
                $table->dropColumn('format_label');
            }
            if (Schema::hasColumn('resource_types', 'icon')) {
                $table->dropColumn('icon');
            }
        });
    }
};
