<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar métodos de pago existentes
        PaymentMethod::truncate();

        $paymentMethods = [
            [
                'name' => 'Tarjeta de Crédito',
                'description' => 'Paga con Visa, Mastercard, American Express y otras tarjetas de crédito. Procesamiento seguro con validación 3D.',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'name' => 'Transferencia Bancaria',
                'description' => 'Transferencia directa desde tu cuenta bancaria. Procesamiento en 24-48 horas hábiles. Confirmación requerida.',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'name' => 'Pago Móvil',
                'description' => 'Pago móvil desde Venezuela. Rápido, seguro y sin comisiones adicionales. Confirmación instantánea.',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'name' => 'Billeteras Digitales',
                'description' => 'Paga con PayPal, Apple Pay, Google Pay y otras billeteras digitales. Pago con un solo clic.',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'name' => 'Pago en Efectivo',
                'description' => 'Paga en efectivo en nuestras sedes o puntos de pago autorizados. Recibo requerido para validación.',
                'is_active' => true,
                'position' => 5,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}
