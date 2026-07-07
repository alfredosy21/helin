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
        Schema::create('whatsapp_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number'); // Número de WhatsApp (ej: 584242789481)
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade'); // Relación con tabla states
            $table->boolean('is_active')->default(true); // Si el número está activo
            $table->string('description')->nullable(); // Descripción opcional
            $table->timestamps();

            // Índices para mejor rendimiento
            $table->index('state_id');
            $table->index('is_active');
            $table->unique(['phone_number', 'state_id']); // Un número por estado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_numbers');
    }
};
