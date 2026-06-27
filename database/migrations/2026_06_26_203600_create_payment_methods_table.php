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
            $table->string('name'); // Nombre del método (Ej: "Tarjeta de Crédito", "Transferencia Bancaria")
            $table->string('slug')->unique(); // Slug para URLs (Ej: "tarjeta-credito")
            $table->string('icon')->nullable(); // Icono o clase CSS (Ej: "fas fa-credit-card")
            $table->text('description')->nullable(); // Descripción del método
            $table->string('image')->nullable(); // Imagen del método de pago
            $table->json('config')->nullable(); // Configuración adicional (comisiones, datos, etc.)
            $table->boolean('is_active')->default(true); // Estado activo/inactivo
            $table->integer('position')->default(0); // Orden de visualización
            $table->boolean('is_default')->default(false); // Método de pago por defecto
            $table->string('provider')->nullable(); // Proveedor (Ej: "stripe", "paypal", "mercado_pago")
            $table->json('provider_config')->nullable(); // Configuración del proveedor (API keys, etc.)
            $table->decimal('fee_percentage', 8, 2)->default(0); // Comisión porcentual
            $table->decimal('fee_fixed', 8, 2)->default(0); // Comisión fija
            $table->decimal('min_amount', 8, 2)->nullable(); // Monto mínimo
            $table->decimal('max_amount', 8, 2)->nullable(); // Monto máximo
            $table->timestamps();

            // Índices
            $table->index(['is_active', 'position']);
            $table->index('is_default');
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
};
