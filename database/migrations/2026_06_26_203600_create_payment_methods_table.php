<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // Nombre del método de pago
            $table->text('description')->nullable(); // Descripción detallada del método
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
        Schema::dropIfExists('payment_methods');
    }
};
