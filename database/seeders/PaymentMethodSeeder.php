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
        $paymentMethods = [
            [
                'name'        => 'Acordar con Helin',
                'description' => 'Coordina directamente con nuestro equipo comercial la forma de pago más conveniente para ti. Te contactaremos en menos de 24 horas para confirmar los detalles.',
                'is_active'   => true,
                'position'    => 1,
            ],
            [
                'name'        => 'Pago Móvil',
                'description' => 'Realiza tu pago desde cualquier banco venezolano usando la plataforma de Pago Móvil. Rápido, seguro y sin comisiones adicionales. Envíanos el comprobante al finalizar.',
                'is_active'   => true,
                'position'    => 2,
            ],
            [
                'name'        => 'Zelle',
                'description' => 'Transfiere en dólares de forma inmediata a través de Zelle. Disponible para cuentas bancarias en EE. UU. Sin comisiones y con confirmación instantánea.',
                'is_active'   => true,
                'position'    => 3,
            ],
            [
                'name'        => 'Binance Pay',
                'description' => 'Paga con criptomonedas a través de Binance Pay. Aceptamos USDT y otras criptomonedas estables. Envíanos el comprobante de transacción al concluir.',
                'is_active'   => true,
                'position'    => 4,
            ],
            [
                'name'        => 'Pagos Múltiples',
                'description' => 'Divide tu pago en varias transacciones combinando diferentes métodos. Ideal para montos elevados. Nuestro equipo te guiará en el proceso de confirmación.',
                'is_active'   => true,
                'position'    => 5,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(['name' => $method['name']], $method);
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}
