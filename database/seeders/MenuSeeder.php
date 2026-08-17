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
            ['title' => 'Implantología', 'url' => '/catalogo?category=implantologia', 'position' => 2],
            ['title' => 'Osteosíntesis', 'url' => '/catalogo?category=osteosintesis', 'position' => 3],
            ['title' => 'Instrumentos', 'url' => '/catalogo?category=instrumentos', 'position' => 4],
            ['title' => 'Planificación digital', 'url' => '/catalogo?category=planificacion-digital', 'position' => 5],
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
            ['title' => 'Implantes', 'url' => '/catalogo?category=implantologia', 'position' => 1],
            ['title' => 'Aditamentos', 'url' => '/catalogo?category=aditamentos', 'position' => 2],
            ['title' => 'Kits Quirúrgicos', 'url' => '/catalogo?category=kits-quirurgicos', 'position' => 3],
            ['title' => 'Biomateriales', 'url' => '/catalogo?tag=biomaterial', 'position' => 4],
            ['title' => 'Regeneración Guiada Bucal', 'url' => '/catalogo?category=regeneracion-guiada-bucal-gbr', 'position' => 5],
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
            ['title' => 'Placas', 'url' => '/catalogo?category=placas-osteosintesis', 'position' => 1],
            ['title' => 'Tornillos', 'url' => '/catalogo?category=tornillos-osteosintesis', 'position' => 2],
            ['title' => 'Suturas', 'url' => '/catalogo?category=suturas', 'position' => 3],
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
            ['title' => 'Tijeras', 'url' => '/catalogo?category=tijeras', 'position' => 1],
            ['title' => 'Pinzas', 'url' => '/catalogo?category=pinzas', 'position' => 2],
            ['title' => 'Separadores', 'url' => '/catalogo?category=separadores', 'position' => 3],
            ['title' => 'Cinceles', 'url' => '/catalogo?category=cinceles', 'position' => 4],
            ['title' => 'Periostótomos', 'url' => '/catalogo?category=periostomos', 'position' => 5],
            ['title' => 'Cajetín', 'url' => '/catalogo?category=cajetin-osteosintesis', 'position' => 6],
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
            ['title' => 'Planificación Digital', 'url' => '/catalogo?category=planificacion-digital', 'position' => 1],
            ['title' => 'Equipos odontológicos', 'url' => '/catalogo?category=equipos-odontologicos', 'position' => 2],
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

        // === MEGA MENU ITEMS ===

        // Columna 1: Implantología
        $megaImplantologia = Menus::create([
            'title' => 'Implantología',
            'url' => '/catalogo?category=implantologia',
            'type' => 4,
            'position' => 1,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-tooth',
            'image' => null,
        ]);

        $megaImplantologiaAb = Menus::create([
            'title' => 'AB',
            'url' => null,
            'type' => 4,
            'position' => 1,
            'parent_id' => $megaImplantologia->id,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => null,
            'image' => null,
        ]);
        foreach (['Implantes' => 'implantologia', 'Aditamentos' => 'aditamentos', 'Kits' => 'kits-quirurgicos'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaImplantologiaAb->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        $megaImplantologiaGdt = Menus::create([
            'title' => 'GDT',
            'url' => null,
            'type' => 4,
            'position' => 2,
            'parent_id' => $megaImplantologia->id,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => null,
            'image' => null,
        ]);
        foreach (['Implantes' => 'implantologia', 'Aditamentos' => 'aditamentos', 'Kits' => 'kits-quirurgicos'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaImplantologiaGdt->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        // Columna 2: Regeneración Ósea Guiada
        $megaRegeneracion = Menus::create([
            'title' => 'Regeneración Ósea Guiada',
            'url' => '/catalogo?category=regeneracion-guiada-bucal-gbr',
            'type' => 4,
            'position' => 2,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-bone',
            'image' => null,
        ]);
        foreach (['Biomateriales' => 'tag=biomaterial', 'Regeneración Guiada Bucal' => 'category=regeneracion-guiada-bucal-gbr', 'Suturas' => 'category=suturas'] as $title => $query) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?{$query}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaRegeneracion->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        // Columna 3: Osteosíntesis
        $megaOsteosintesis = Menus::create([
            'title' => 'Osteosíntesis',
            'url' => '/catalogo?category=osteosintesis',
            'type' => 4,
            'position' => 3,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-toolbox',
            'image' => null,
        ]);
        foreach (['Placas' => 'placas-osteosintesis', 'Tornillos' => 'tornillos-osteosintesis', 'Cajetín' => 'cajetin-osteosintesis'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaOsteosintesis->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        // Columna 4: Cuidado Bucal
        $megaCuidado = Menus::create([
            'title' => 'Cuidado Bucal',
            'url' => '/catalogo?category=cuidado-bucal',
            'type' => 4,
            'position' => 4,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-face-smile',
            'image' => null,
        ]);
        foreach (['Cuidados Especiales' => 'cuidados-especiales', 'Cuidados Diarios' => 'cuidados-diarios'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaCuidado->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        // Columna 5: Instrumentos y Equipos
        $megaInstrumentos = Menus::create([
            'title' => 'Instrumentos',
            'url' => '/catalogo?category=instrumentos',
            'type' => 4,
            'position' => 5,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-tools',
            'image' => null,
        ]);
        foreach (['Tijeras' => 'tijeras', 'Pinzas' => 'pinzas', 'Separadores' => 'separadores', 'Cinceles' => 'cinceles', 'Periostótomos' => 'periostomos'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaInstrumentos->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        $megaEquiposGroup = Menus::create([
            'title' => 'Equipos',
            'url' => null,
            'type' => 4,
            'position' => 6,
            'parent_id' => $megaInstrumentos->id,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-gears',
            'image' => null,
        ]);
        foreach (['Equipos odontológicos' => 'equipos-odontologicos', 'Piezas de mano' => 'piezas-de-mano', 'Motores' => 'motores-odontologicos'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaEquiposGroup->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
        }

        // Columna 6: Planificación Digital
        $megaPlanificacion = Menus::create([
            'title' => 'Planificación Digital',
            'url' => '/catalogo?category=planificacion-digital',
            'type' => 4,
            'position' => 6,
            'parent_id' => null,
            'status' => true,
            'target_blank' => false,
            'description' => null,
            'icon' => 'fa-cube',
            'image' => null,
        ]);
        foreach (['Planificación Digital' => 'planificacion-digital', 'Impresión 3D' => 'impresion-3d', 'Escaneo Intraoral' => 'escaneo-intraoral', 'PD Completa' => 'pd-completa'] as $title => $slug) {
            Menus::create([
                'title' => $title,
                'url' => "/catalogo?category={$slug}",
                'type' => 4,
                'position' => 1,
                'parent_id' => $megaPlanificacion->id,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ]);
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
