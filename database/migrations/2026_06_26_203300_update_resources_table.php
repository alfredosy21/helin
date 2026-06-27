<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('resources', function (Blueprint $table) {
            // Actualizar el enum para incluir todos los tipos de recursos de la vista
            $table->enum('type', ['case_study', 'video', 'manual', 'technical_sheet', 'downloadable_guide', 'article'])->change();
            
            // Agregar campos faltantes según la vista recursos-clinicos.blade.php
            $table->string('specialty')->nullable()->after('type'); // Cirugía Bucal, Maxilofacial, etc.
            $table->string('format')->nullable()->after('specialty'); // Artículo, PDF, Video
            $table->text('tags')->nullable()->after('format'); // Tags como JSON
            $table->string('url')->nullable()->after('file_path'); // URL para recursos externos
            $table->integer('views')->default(0)->after('is_active'); // Para estadísticas
            $table->integer('position')->default(0)->after('views'); // Para ordenamiento
            $table->boolean('featured')->default(false)->after('position'); // Para recursos destacados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('resources', function (Blueprint $table) {
            $table->enum('type', ['case_study', 'video', 'manual', 'digital_planning'])->change();
            $table->dropColumn(['specialty', 'format', 'tags', 'url', 'views', 'position', 'featured']);
        });
    }
};
