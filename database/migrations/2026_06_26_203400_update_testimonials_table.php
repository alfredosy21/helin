<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('testimonials', function (Blueprint $table) {
            // Actualizar campos para coincidir con el seeder creado
            $table->renameColumn('name', 'author'); // Cambiar 'name' a 'author'
            $table->renameColumn('specialty', 'title'); // Cambiar 'specialty' a 'title'
            $table->dropColumn('city'); // Eliminar campo 'city' no usado en el seeder
            
            // Agregar campos faltantes
            $table->integer('position')->default(0)->after('is_active'); // Para ordenamiento
            $table->text('text')->change(); // Asegurar que content sea text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('author', 'name');
            $table->renameColumn('title', 'specialty');
            $table->string('city')->nullable()->after('title');
            $table->dropColumn('position');
        });
    }
};
