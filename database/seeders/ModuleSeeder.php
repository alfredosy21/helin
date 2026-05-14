<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Submodule;

/**
 * Module Seeder
 *
 * This seeder populates the modules table with the main system modules
 * and their corresponding submodules (sections) based on the existing routes.
 * Each module includes a name (in Spanish for UI) and an icon class
 * for the sidebar navigation.
 */
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Administradores',
                'class' => 'user-check',
                'order' => 1,
                'submodules' => [
                    ['name' => 'Usuarios', 'url' => '/cms/system/users'],
                    ['name' => 'Roles', 'url' => '/cms/system/roles'],
                ]
            ],
            [
                'name' => 'Configuración',
                'class' => 'settings',
                'order' => 2,
                'submodules' => [
                    ['name' => 'Configuración General', 'url' => '/cms/settings'],
                    ['name' => 'Secciones', 'url' => '/cms/sections'],
                ]
            ],
            [
                'name' => 'Catálogo',
                'class' => 'truck',
                'order' => 3,
                'submodules' => [
                    ['name' => 'Productos', 'url' => '/cms/catalog/products'],
                    ['name' => 'Categorías', 'url' => '/cms/catalog/categories'],
                    ['name' => 'Marcas', 'url' => '/cms/catalog/brands'],
                    ['name' => 'Líneas', 'url' => '/cms/catalog/lines'],
                ]
            ],
            [
                'name' => 'Blog',
                'class' => 'edit-3',
                'order' => 4,
                'submodules' => [
                    ['name' => 'Categorías del Blog', 'url' => '/cms/blog/categories'],
                    ['name' => 'Artículos', 'url' => '/cms/blog/articles'],
                    ['name' => 'Etiquetas', 'url' => '/cms/blog/tags'],
                ]
            ],
            [
                'name' => 'Testimonios',
                'class' => 'users',
                'order' => 5,
                'submodules' => [
                    ['name' => 'Gestión de Testimonios', 'url' => '/cms/testimonials'],
                ]
            ],
            [
                'name' => 'Contacto',
                'class' => 'help-circle',
                'order' => 6,
                'submodules' => [
                    ['name' => 'Mensajes de Contacto', 'url' => '/cms/contact'],
                    ['name' => 'Gestión de Contactos', 'url' => '/cms/contacts'],
                    ['name' => 'Configuración de Formulario', 'url' => '/cms/contact/form'],
                ]
            ],
        ];

        foreach ($modules as $moduleData) {
            // Create or update module
            $module = Module::updateOrCreate(
                ['name' => $moduleData['name']],
                [
                    'class' => $moduleData['class'],
                    'position' => $moduleData['order'],
                ]
            );

            // Create submodules for this module
            if (isset($moduleData['submodules'])) {
                foreach ($moduleData['submodules'] as $submoduleData) {
                    Submodule::updateOrCreate(
                        [
                            'name' => $submoduleData['name'],
                            'module_id' => $module->id
                        ],
                        [
                            'url' => $submoduleData['url']
                        ]
                    );
                }
            }
        }

        $this->command->info('Modules and submodules seeded successfully!');
    }
}
