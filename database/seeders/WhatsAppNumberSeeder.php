<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\WhatsAppNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsAppNumberSeeder extends Seeder
{
    /**
     * Asignación comercial por zona según el estado del cliente.
     *
     * Nota: reemplaza los números de ejemplo por los números reales de cada ejecutivo.
     */
    public function run(): void
    {
        $zoneNumbers = [
            'Zona 1' => [
                'phone' => '584242789481',
                'executive' => 'Ejecutivo Caracas',
            ],
            'Zona 2' => [
                'phone' => '584244669150',
                'executive' => 'Ejecutivo Valencia',
            ],
            'Zona 3' => [
                'phone' => '584143805640',
                'executive' => 'Ejecutivo Barquisimeto',
            ],
            'Zona 4' => [
                'phone' => '584242550811',
                'executive' => 'Ejecutivo Maracaibo',
            ],
        ];

        $zones = [
            'Zona 1' => ['DC', 'MI', 'VA', 'AN', 'SU', 'MO', 'NE', 'DF', 'BO'],
            'Zona 2' => ['CA', 'AR', 'CO', 'GU', 'AP', 'AM'],
            'Zona 3' => ['LA', 'YA', 'PO', 'BA', 'ME', 'TR', 'TA'],
            'Zona 4' => ['ZU', 'FA'],
        ];

        foreach ($zones as $zoneName => $stateCodes) {
            foreach ($stateCodes as $code) {
                $state = State::where('code', $code)->first();

                if (! $state) {
                    $this->command->warn("Estado con código {$code} no encontrado; se omite asignación de WhatsApp.");
                    continue;
                }

                WhatsAppNumber::updateOrCreate(
                    ['state_id' => $state->id],
                    [
                        'phone_number' => $zoneNumbers[$zoneName]['phone'],
                        'executive_name' => $zoneNumbers[$zoneName]['executive'],
                        'description' => "{$zoneName} - {$zoneNumbers[$zoneName]['executive']}",
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('WhatsApp numbers seeded successfully by commercial zone.');
    }
}
