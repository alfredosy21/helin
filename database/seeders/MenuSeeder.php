<?php

namespace Database\Seeders;

use App\Models\Menus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Limpiar la tabla antes de sembrar para evitar duplicados
        DB::table('menus')->truncate();

        // === HEADER MENU ITEMS ===
        $headerItems = [
            ['title' => 'Inicio', 'url' => '/', 'position' => 1],
            ['title' => 'Implantología', 'url' => null, 'position' => 2],
            ['title' => 'Osteosíntesis', 'url' => null, 'position' => 3],
            ['title' => 'Instrumentos', 'url' => null, 'position' => 4],
            ['title' => 'Planificación digital', 'url' => null, 'position' => 5],
            ['title' => 'Ofertas', 'url' => '/catalogo?tag=on_sale', 'position' => 6],
        ];

        foreach ($headerItems as $item) {
            Menus::create(array_merge($item, [
                'type' => 1,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        // === FOOTER MENU ITEMS ===

        // Nuestra Empresa - Parent
        $nuestraEmpresa = Menus::create([
            'title' => 'Nuestra Empresa',
            'url' => null,
            'type' => 2,
            'position' => 1,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => null,
            'image' => null,
        ]);

        // Children of Nuestra Empresa
        $empresaChildren = [
            ['title' => 'Quiénes somos', 'url' => '/nuestra-empresa#quienes-somos', 'position' => 1],
            ['title' => 'Aliados comerciales', 'url' => '/nuestra-empresa#nuestros-aliados', 'position' => 2],
            ['title' => 'Nuestras políticas', 'url' => '/politicas', 'position' => 3],
            ['title' => 'Contáctanos', 'url' => '/contactanos', 'position' => 4],
        ];

        foreach ($empresaChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 2,
                'parent_id' => $nuestraEmpresa->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        // Políticas - Parent
        $politicas = Menus::create([
            'title' => 'Políticas',
            'url' => null,
            'type' => 2,
            'position' => 3,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => null,
            'image' => null,
        ]);

        // Children of Políticas
        $politicasChildren = [
            ['title' => 'Políticas de envío y garantías', 'url' => '/politicas#envio-garantias', 'position' => 1],
            ['title' => 'Términos y condiciones', 'url' => '/politicas#terminos-condiciones', 'position' => 2],
            ['title' => 'Política de privacidad', 'url' => '/politicas#privacidad', 'position' => 3],
        ];

        foreach ($politicasChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 2,
                'parent_id' => $politicas->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        $this->command->info('Menu seeded successfully!');
    }
}
