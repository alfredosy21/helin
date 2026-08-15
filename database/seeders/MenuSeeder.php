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

        $headerParents = [];
        foreach ($headerItems as $item) {
            $menu = Menus::create(array_merge($item, [
                'type' => 1,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
            $headerParents[$item['title']] = $menu;
        }

        // Children de Implantología
        $implantologiaChildren = [
            ['title' => 'Implantes', 'url' => '/catalogo?category[]=implantologia', 'position' => 1],
            ['title' => 'Aditamentos', 'url' => '/catalogo?category[]=aditamentos', 'position' => 2],
            ['title' => 'Kits Quirúrgicos', 'url' => '/catalogo?category[]=kits-quirurgicos', 'position' => 3],
            ['title' => 'Biomateriales', 'url' => '/catalogo?category[]=biomateriales', 'position' => 4],
            ['title' => 'Regeneración Guiada Bucal', 'url' => '/catalogo?category[]=regeneracion-guiada-bucal-gbr', 'position' => 5],
        ];
        foreach ($implantologiaChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 1,
                'parent_id' => $headerParents['Implantología']->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        // Children de Osteosíntesis
        $osteosintesisChildren = [
            ['title' => 'Placas', 'url' => '/catalogo?category[]=placas', 'position' => 1],
            ['title' => 'Tornillos', 'url' => '/catalogo?category[]=tornillos', 'position' => 2],
            ['title' => 'Suturas', 'url' => '/catalogo?category[]=suturas', 'position' => 3],
        ];
        foreach ($osteosintesisChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 1,
                'parent_id' => $headerParents['Osteosíntesis']->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        // Children de Instrumentos
        $instrumentosChildren = [
            ['title' => 'Tijeras', 'url' => '/catalogo?category[]=tijeras', 'position' => 1],
            ['title' => 'Pinzas', 'url' => '/catalogo?category[]=pinzas', 'position' => 2],
            ['title' => 'Separadores', 'url' => '/catalogo?category[]=separadores', 'position' => 3],
            ['title' => 'Cinceles', 'url' => '/catalogo?category[]=cinceles', 'position' => 4],
            ['title' => 'Periostótomos', 'url' => '/catalogo?category[]=periostomos', 'position' => 5],
            ['title' => 'Cajetín', 'url' => '/catalogo?category[]=cajetin', 'position' => 6],
        ];
        foreach ($instrumentosChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 1,
                'parent_id' => $headerParents['Instrumentos']->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]));
        }

        // Children de Planificación digital
        $planificacionChildren = [
            ['title' => 'Planificación Digital', 'url' => '/catalogo?category[]=planificacion-digital', 'position' => 1],
            ['title' => 'Equipos odontológicos', 'url' => '/catalogo?category[]=equipos-odontologicos', 'position' => 2],
        ];
        foreach ($planificacionChildren as $child) {
            Menus::create(array_merge($child, [
                'type' => 1,
                'parent_id' => $headerParents['Planificación digital']->id,
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
