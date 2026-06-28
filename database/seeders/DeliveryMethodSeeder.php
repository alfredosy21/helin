<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryMethod;

class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'MRW (Cobro destino)',          'slug' => 'mrw',     'description' => 'Envío nacional con cobro en destino vía MRW.',        'order' => 1],
            ['name' => 'Liberty (Cobro destino)',       'slug' => 'liberty', 'description' => 'Envío nacional con cobro en destino vía Liberty.',     'order' => 2],
            ['name' => 'Tealca',                        'slug' => 'tealca',  'description' => 'Servicio de mensajería y encomiendas Tealca.',          'order' => 3],
            ['name' => 'Zoom',                          'slug' => 'zoom',    'description' => 'Mensajería rápida nacional Zoom.',                      'order' => 4],
            ['name' => 'Pick Up (Recoger en tienda)',   'slug' => 'pickup',  'description' => 'El cliente retira el pedido directamente en tienda.',   'order' => 5],
        ];

        foreach ($methods as $method) {
            DeliveryMethod::updateOrCreate(
                ['slug' => $method['slug']],
                array_merge($method, ['is_active' => true])
            );
        }
    }
}
