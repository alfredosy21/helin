<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('resource_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // Nombre del tipo (Ej: "Caso Clínico", "Video")
            $table->string('slug')->unique(); // Slug para URLs (Ej: "caso-clinico")
            $table->text('description')->nullable(); // Descripción del tipo
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->integer('position')->default(0); // Orden de visualización
            $table->timestamps();

            // Índices
            $table->index(['is_active', 'position']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('resource_types');
    }
};
