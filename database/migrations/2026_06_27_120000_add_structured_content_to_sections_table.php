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
        Schema::table('sections', function (Blueprint $table) {
            // Campos de contenido estructurado
            $table->text('subtitle')->nullable()->after('title'); // Para subtítulos
            $table->text('description')->nullable()->after('content'); // Para descripciones largas
            $table->json('items')->nullable()->after('description'); // Para listas estructuradas (badges, points, etc.)
            $table->string('layout_type')->default('text_simple')->after('items'); // Tipo de layout
            $table->string('icon_style')->default('emoji')->after('layout_type'); // Estilo de iconos
            
            // Campos adicionales para botones múltiples
            $table->json('buttons')->nullable()->after('url_button'); // Para múltiples botones con texto y URL
            
            // Índices
            $table->index('layout_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'description', 
                'items',
                'layout_type',
                'icon_style',
                'buttons'
            ]);
            $table->dropIndex(['layout_type']);
        });
    }
};
