<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commercial_requests', function (Blueprint $table) {
            $table->id();
            
            // Datos del cliente
            $table->foreignId('customer_type_id')->constrained('customer_types')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cedula')->nullable();
            $table->string('company_name')->nullable();
            $table->string('rif')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->text('address');
            $table->text('observations')->nullable();
            
            // Datos de envío
            $table->foreignId('delivery_method_id')->constrained('delivery_methods')->onDelete('cascade');
            $table->string('other_delivery_company')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_document')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->foreignId('shipping_state_id')->nullable()->constrained('states')->onDelete('cascade');
            $table->foreignId('shipping_city_id')->nullable()->constrained('cities')->onDelete('cascade');
            $table->string('destination_agency')->nullable();
            
            // Datos de pago
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->string('payment_receipt_number')->nullable();
            
            // WhatsApp asociado al estado
            $table->foreignId('whatsapp_number_id')->nullable()->constrained('whatsapp_numbers')->onDelete('set null');
            
            // Estado de la solicitud
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            
            // Datos del carrito (JSON)
            $table->json('cart_data');
            
            // Aceptación de política
            $table->boolean('privacy_accepted')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('status');
            $table->index('customer_type_id');
            $table->index('state_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercial_requests');
    }
};
