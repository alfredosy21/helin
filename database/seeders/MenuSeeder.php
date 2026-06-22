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

        $menus = [
            // Header Menu Items
            [
                'title' => 'Inicio',
                'url' => '/',
                'type' => 1, // Header
                'position' => 1,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Cirugía Bucal - Parent (with dropdown)
            [
                'title' => 'Cirugía Bucal',
                'url' => null,
                'type' => 1, // Header
                'position' => 2,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Maxilofacial - Parent (with dropdown)
            [
                'title' => 'Maxilofacial',
                'url' => null,
                'type' => 1, // Header
                'position' => 3,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Periodoncia - Parent (with dropdown)
            [
                'title' => 'Periodoncia',
                'url' => null,
                'type' => 1, // Header
                'position' => 4,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Ortodoncia - Parent (with dropdown)
            [
                'title' => 'Ortodoncia',
                'url' => null,
                'type' => 1, // Header
                'position' => 5,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Endodoncia - Parent (with dropdown)
            [
                'title' => 'Endodoncia',
                'url' => null,
                'type' => 1, // Header
                'position' => 6,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Nuestra Empresa - Parent
            [
                'title' => 'Nuestra Empresa',
                'url' => null,
                'type' => 2, // Footer
                'position' => 1,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Quiénes somos - Child of Nuestra Empresa
            [
                'title' => 'Quiénes somos',
                'url' => '/nuestra-empresa#quienes-somos',
                'type' => 2, // Footer
                'position' => 1,
                'parent_id' => 1, // Nuestra Empresa
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Misión y visión - Child of Nuestra Empresa
            [
                'title' => 'Misión y visión',
                'url' => '/nuestra-empresa#mision-vision',
                'type' => 2, // Footer
                'position' => 2,
                'parent_id' => 1, // Nuestra Empresa
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Nuestro Team - Child of Nuestra Empresa
            [
                'title' => 'Nuestro Team',
                'url' => '/nuestra-empresa#nuestro-team',
                'type' => 2, // Footer
                'position' => 3,
                'parent_id' => 1, // Nuestra Empresa
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Nuestro Alianza - Child of Nuestra Empresa
            [
                'title' => 'Nuestro Alianza',
                'url' => '/nuestra-empresa#nuestros-aliados',
                'type' => 2, // Footer
                'position' => 4,
                'parent_id' => 1, // Nuestra Empresa
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Contáctanos - Standalone
            [
                'title' => 'Contáctanos',
                'url' => '/contactanos',
                'type' => 2, // Footer
                'position' => 2,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Políticas - Parent
            [
                'title' => 'Políticas',
                'url' => null,
                'type' => 2, // Footer
                'position' => 3,
                'parent_id' => null,
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Políticas de envío y garantías - Child of Políticas
            [
                'title' => 'Políticas de envío y garantías',
                'url' => '/politicas#envio-garantias',
                'type' => 2, // Footer
                'position' => 1,
                'parent_id' => 7, // Políticas
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Términos y condiciones - Child of Políticas
            [
                'title' => 'Términos y condiciones',
                'url' => '/politicas#terminos-condiciones',
                'type' => 2, // Footer
                'position' => 2,
                'parent_id' => 7, // Políticas
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],

            // Política de privacidad - Child of Políticas
            [
                'title' => 'Política de privacidad',
                'url' => '/politicas#privacidad',
                'type' => 2, // Footer
                'position' => 3,
                'parent_id' => 7, // Políticas
                'status' => true,
                'target_blank' => false,
                'description' => null,
                'icon' => null,
                'image' => null,
            ],
        ];

        foreach ($menus as $data) {
            Menus::create($data);
        }

        $this->command->info('Menu seeded successfully!');
    }
}
