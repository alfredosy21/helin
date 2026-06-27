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
                'slug' => 'tarjeta-credito',
                'icon' => 'fas fa-credit-card',
                'description' => 'Paga con Visa, Mastercard, American Express y otras tarjetas de crédito.',
                'image' => 'payment-methods/credit-card.jpg',
                'config' => json_encode([
                    'accepted_cards' => ['visa', 'mastercard', 'amex', 'discover'],
                    'installments' => [1, 3, 6, 12],
                    '3ds_secure' => true
                ]),
                'is_active' => true,
                'position' => 1,
                'is_default' => true,
                'provider' => 'stripe',
                'provider_config' => json_encode([
                    'public_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_secret' => 'whsec_...'
                ]),
                'fee_percentage' => 3.50,
                'fee_fixed' => 0.50,
                'min_amount' => 10.00,
                'max_amount' => 50000.00,
            ],
            [
                'name' => 'Transferencia Bancaria',
                'slug' => 'transferencia-bancaria',
                'icon' => 'fas fa-university',
                'description' => 'Transferencia directa desde tu cuenta bancaria. Procesamiento en 24-48 horas.',
                'image' => 'payment-methods/bank-transfer.jpg',
                'config' => json_encode([
                    'account_types' => ['checking', 'savings'],
                    'processing_time' => '24-48 hours',
                    'confirmation_required' => true
                ]),
                'is_active' => true,
                'position' => 2,
                'is_default' => false,
                'provider' => 'plaid',
                'provider_config' => json_encode([
                    'client_id' => 'plaid_client_id',
                    'secret' => 'plaid_secret',
                    'environment' => 'sandbox'
                ]),
                'fee_percentage' => 1.00,
                'fee_fixed' => 5.00,
                'min_amount' => 50.00,
                'max_amount' => 100000.00,
            ],
            [
                'name' => 'Pago Móvil',
                'slug' => 'pago-movil',
                'icon' => 'fas fa-mobile-alt',
                'description' => 'Pago móvil desde Venezuela. Rápido, seguro y sin comisiones adicionales.',
                'image' => 'payment-methods/mobile-payment.jpg',
                'config' => json_encode([
                    'banks' => ['Banesco', 'Mercantil', 'Provincial', 'Venezuela'],
                    'instant_confirmation' => true
                ]),
                'is_active' => true,
                'position' => 3,
                'is_default' => false,
                'provider' => 'local',
                'provider_config' => json_encode([
                    'phone_required' => true,
                    'verification_code' => true
                ]),
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'min_amount' => 5.00,
                'max_amount' => 20000.00,
            ],
            [
                'name' => 'Billeteras Digitales',
                'slug' => 'billeteras-digitales',
                'icon' => 'fas fa-wallet',
                'description' => 'Paga con PayPal, Apple Pay, Google Pay y otras billeteras digitales.',
                'image' => 'payment-methods/digital-wallets.jpg',
                'config' => json_encode([
                    'wallets' => ['paypal', 'apple_pay', 'google_pay', 'samsung_pay'],
                    'one_click_payment' => true
                ]),
                'is_active' => true,
                'position' => 4,
                'is_default' => false,
                'provider' => 'paypal',
                'provider_config' => json_encode([
                    'client_id' => 'paypal_client_id',
                    'client_secret' => 'paypal_client_secret',
                    'sandbox' => true
                ]),
                'fee_percentage' => 2.90,
                'fee_fixed' => 0.30,
                'min_amount' => 1.00,
                'max_amount' => 25000.00,
            ],
            [
                'name' => 'Pago en Efectivo',
                'slug' => 'pago-efectivo',
                'icon' => 'fas fa-money-bill-wave',
                'description' => 'Paga en efectivo en nuestras sedes o puntos de pago autorizados.',
                'image' => 'payment-methods/cash.jpg',
                'config' => json_encode([
                    'payment_points' => ['Caracas', 'Valencia', 'Barquisimeto', 'Maracaibo'],
                    'receipt_required' => true
                ]),
                'is_active' => true,
                'position' => 5,
                'is_default' => false,
                'provider' => 'local',
                'provider_config' => json_encode([
                    'manual_verification' => true,
                    'cash_handling' => true
                ]),
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'min_amount' => 10.00,
                'max_amount' => 50000.00,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}
