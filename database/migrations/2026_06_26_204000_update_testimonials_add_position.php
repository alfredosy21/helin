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
        Schema::table('testimonials', function (Blueprint $table) {
            // Agregar campo position para ordenamiento
            $table->integer('position')->default(0)->after('is_active');
            
            // Agregar índice para ordenamiento
            $table->index(['is_active', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Eliminar campo position
            $table->dropColumn('position');
            
            // Eliminar índice
            $table->dropIndex(['is_active', 'position']);
        });
    }
};
