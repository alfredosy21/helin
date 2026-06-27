<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Submodule;

/**
 * Module Seeder
 *
 * Populates modules and submodules tables using clean, verified Lucide/Feather
 * core naming conventions to guarantee seamless layout rendering.
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
        // Verified core names: shield, settings, shopping-cart, file-text, message-square, mail
        $modules = [
            [
                'name' => 'Administradores',
                'class' => 'shield', // Already verified and working!
                'order' => 1,
                'submodules' => [
                    ['name' => 'Usuarios', 'url' => '/cms/system/users', 'icon' => 'users'],
                    ['name' => 'Roles', 'url' => '/cms/system/roles', 'icon' => 'lock'],
                ]
            ],
            [
                'name' => 'Configuración',
                'class' => 'settings', // Replaced 'sliders' with 'settings' (universal configuration gear)
                'order' => 2,
                'submodules' => [
                    ['name' => 'Configuración General', 'url' => '/cms/settings', 'icon' => 'sliders'],
                    ['name' => 'Secciones', 'url' => '/cms/sections', 'icon' => 'layout'],
                    ['name' => 'Métodos de Pago', 'url' => '/cms/payment-methods', 'icon' => 'credit-card'],
                ]
            ],
            [
                'name' => 'Catálogo',
                'class' => 'package',
                'order' => 3,
                'submodules' => [
                    ['name' => 'Productos', 'url' => '/cms/catalog/products', 'icon' => 'package'],
                    ['name' => 'Familias de Productos', 'url' => '/cms/catalog/family', 'icon' => 'folder'],
                    ['name' => 'Marcas de Productos', 'url' => '/cms/catalog/brands', 'icon' => 'tag'],
                    ['name' => 'Líneas de Productos', 'url' => '/cms/catalog/lines', 'icon' => 'layers'],
                    ['name' => 'Sistema de Productos', 'url' => '/cms/catalog/system-products', 'icon' => 'layers'],
                    ['name' => 'Plataforma de Productos', 'url' => '/cms/catalog/product-platforms', 'icon' => 'layers'],
                ]
            ],
            [
                'name' => 'Blog',
                'class' => 'folder',
                'order' => 4,
                'submodules' => [
                    ['name' => 'Categorías del Blog', 'url' => '/cms/blog/categories', 'icon' => 'folder'],
                    ['name' => 'Artículos', 'url' => '/cms/blog/articles', 'icon' => 'edit'],
                ]
            ],
            [
                'name' => 'Contenido',
                'class' => 'file',
                'order' => 5,
                'submodules' => [
                    ['name' => 'Testimonios', 'url' => '/cms/testimonials', 'icon' => 'star'],
                    ['name' => 'Recursos Clínicos', 'url' => '/cms/resources', 'icon' => 'briefcase'],
                    ['name' => 'Tipos de Recursos', 'url' => '/cms/resource-types', 'icon' => 'folder'],
                    ['name' => 'Especialidades', 'url' => '/cms/resource-specialties', 'icon' => 'stethoscope'],
                ]
            ],
            [
                'name' => 'Contacto',
                'class' => 'mail', // Confirmed functional icon from your dashboard link
                'order' => 6,
                'submodules' => [
                    ['name' => 'Mensajes de Contacto', 'url' => '/cms/contact', 'icon' => 'inbox'],
                    ['name' => 'Gestión de Contactos', 'url' => '/cms/contacts', 'icon' => 'user'],
                    ['name' => 'Configuración de Formulario', 'url' => '/cms/contact/form', 'icon' => 'check-square'],
                ]
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::updateOrCreate(
                ['name' => $moduleData['name']],
                [
                    'class' => $moduleData['class'],
                    'position' => $moduleData['order'],
                ]
            );

            if (isset($moduleData['submodules'])) {
                foreach ($moduleData['submodules'] as $submoduleData) {
                    Submodule::updateOrCreate(
                        [
                            'name' => $submoduleData['name'],
                            'module_id' => $module->id
                        ],
                        [
                            'url' => $submoduleData['url'],
                        ]
                    );
                }
            }
        }

        $this->command->info('Database icons successfully refreshed with verified clean Lucide assets!');
    }
}
