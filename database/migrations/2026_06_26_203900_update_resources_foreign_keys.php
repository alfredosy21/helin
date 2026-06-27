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
            // Agregar foreign keys para tipo y especialidad
            $table->unsignedBigInteger('resource_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('resource_specialty_id')->nullable()->after('resource_type_id');
            
            // Foreign key constraints
            $table->foreign('resource_type_id')->references('id')->on('resource_types')->onDelete('set null');
            $table->foreign('resource_specialty_id')->references('id')->on('resource_specialties')->onDelete('set null');
            
            // Mantener los campos originales para compatibilidad temporal
            // Se eliminarán en una migración posterior después de migrar los datos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('resources', function (Blueprint $table) {
            // Eliminar foreign keys
            $table->dropForeign(['resource_type_id']);
            $table->dropForeign(['resource_specialty_id']);
            
            // Eliminar columnas
            $table->dropColumn(['resource_type_id', 'resource_specialty_id']);
        });
    }
};
