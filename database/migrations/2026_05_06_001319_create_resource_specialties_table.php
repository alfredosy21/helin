<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('resource_specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // Nombre de la especialidad (Ej: "Cirugía Bucal", "Maxilofacial")
            $table->text('description')->nullable(); // Descripción de la especialidad
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->integer('position')->default(0); // Orden de visualización
            $table->timestamps();

            // Índices
            $table->index(['is_active', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('resource_specialties');
    }
};
