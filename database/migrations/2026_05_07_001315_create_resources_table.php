<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['case_study', 'video', 'manual', 'digital_planning', 'technical_sheet', 'guide', 'downloadable_guide']);
            $table->string('format')->default('article')->comment('Formato: article, pdf, video');
            $table->string('file_path');
            $table->string('thumbnail')->nullable();
            $table->string('icon_symbol')->nullable()->comment('Símbolo de formato: ▣, ▶, ▤, →, ↓');
            $table->json('tags')->nullable()->comment('Etiquetas clínicas');
            $table->integer('view_count')->default(0)->comment('Contador de vistas');
            $table->timestamp('published_at')->nullable()->comment('Fecha de publicación');
            $table->string('specialty')->nullable()->comment('Especialidad clínica (legacy)');
            $table->string('url')->nullable();
            $table->integer('views')->default(0)->comment('Contador de vistas (legacy)');
            $table->integer('position')->default(0);
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('resource_type_id')->nullable();
            $table->unsignedBigInteger('resource_specialty_id')->nullable();
            $table->nullableMorphs('resourceable');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('resource_type_id')->references('id')->on('resource_types')->onDelete('set null');
            $table->foreign('resource_specialty_id')->references('id')->on('resource_specialties')->onDelete('set null');

            // Índices
            $table->index(['is_active', 'published_at']);
            $table->index('format');
            $table->index('view_count');
            $table->index(['resource_type_id', 'resource_specialty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('resources');
    }
};
