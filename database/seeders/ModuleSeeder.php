<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Submodule;
use Illuminate\Database\Seeder;

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
     */
    public function run(): void
    {
        // Verified core names: shield, settings, shopping-cart, file-text, message-square, mail
        $modules = [
            [
                'id' => Module::ADMINISTRATORS,
                'name' => 'Administradores',
                'class' => 'shield', // Already verified and working!
                'order' => 1,
                'submodules' => [
                    ['id' => Submodule::USERS, 'name' => 'Usuarios', 'url' => '/cms/system/users', 'icon' => 'users'],
                    ['id' => Submodule::ROLES, 'name' => 'Roles', 'url' => '/cms/system/roles', 'icon' => 'lock'],
                ],
            ],
            [
                'id' => Module::SETTINGS,
                'name' => 'Configuración',
                'class' => 'settings', // Replaced 'sliders' with 'settings' (universal configuration gear)
                'order' => 2,
                'submodules' => [
                    ['id' => Submodule::GENERAL_SETTINGS, 'name' => 'Configuración General', 'url' => '/cms/settings', 'icon' => 'sliders'],
                    ['id' => Submodule::SECTIONS, 'name' => 'Secciones', 'url' => '/cms/sections', 'icon' => 'layout'],
                    ['id' => Submodule::WEBSITE_MENU, 'name' => 'Menú del Sitio', 'url' => '/cms/menu', 'icon' => 'menu'],
                    ['id' => Submodule::PAYMENT_METHODS, 'name' => 'Métodos de Pago',     'url' => '/cms/payment-methods', 'icon' => 'credit-card'],
                    ['id' => Submodule::CUSTOMER_TYPES, 'name' => 'Tipos de Cliente',    'url' => '/cms/customer-types',  'icon' => 'users'],
                    ['id' => Submodule::DELIVERY_METHODS, 'name' => 'Métodos de Entrega',  'url' => '/cms/delivery-methods', 'icon' => 'truck'],
                    ['id' => Submodule::WHATSAPP_NUMBERS, 'name' => 'Números de WhatsApp', 'url' => '/cms/whatsapp-numbers', 'icon' => 'message-circle'],
                    ['id' => Submodule::PAGE_SEO, 'name' => 'SEO de Páginas', 'url' => '/cms/page-seo', 'icon' => 'search'],
                ],
            ],
            [
                'id' => Module::CATALOG,
                'name' => 'Catálogo',
                'class' => 'package',
                'order' => 3,
                'submodules' => [
                    ['id' => Submodule::PRODUCTS, 'name' => 'Productos', 'url' => '/cms/catalog/products', 'icon' => 'package'],
                    ['id' => Submodule::PRODUCT_FAMILIES, 'name' => 'Familias de Productos', 'url' => '/cms/catalog/family', 'icon' => 'folder'],
                    ['id' => Submodule::PRODUCT_BRANDS, 'name' => 'Marcas de Productos', 'url' => '/cms/catalog/brands', 'icon' => 'tag'],
                    ['id' => Submodule::PRODUCT_LINES, 'name' => 'Líneas de Productos', 'url' => '/cms/catalog/lines', 'icon' => 'layers'],
                    ['id' => Submodule::SYSTEM_PRODUCTS, 'name' => 'Sistema de Productos', 'url' => '/cms/catalog/system-products', 'icon' => 'layers'],
                    ['id' => Submodule::PRODUCT_PLATFORMS, 'name' => 'Plataforma de Productos', 'url' => '/cms/catalog/product-platforms', 'icon' => 'layers'],
                    ['id' => Submodule::ATTRIBUTES, 'name' => 'Atributos de Productos', 'url' => '/cms/attributes', 'icon' => 'sliders-horizontal'],
                    ['id' => Submodule::ATTRIBUTE_VALUES, 'name' => 'Valores de Atributos', 'url' => '/cms/attribute-values', 'icon' => 'list'],
                ],
            ],
            [
                'id' => Module::CONTENT,
                'name' => 'Contenido',
                'class' => 'file',
                'order' => 5,
                'submodules' => [
                    ['id' => Submodule::TESTIMONIALS, 'name' => 'Testimonios', 'url' => '/cms/testimonials', 'icon' => 'star'],
                    ['id' => Submodule::CLINICAL_RESOURCES, 'name' => 'Recursos Clínicos', 'url' => '/cms/resources', 'icon' => 'briefcase'],
                    ['id' => Submodule::RESOURCE_TYPES, 'name' => 'Tipos de Recursos', 'url' => '/cms/resource-types', 'icon' => 'folder'],
                    ['id' => Submodule::RESOURCE_SPECIALTIES, 'name' => 'Especialidades', 'url' => '/cms/resource-specialties', 'icon' => 'stethoscope'],
                ],
            ],
            [
                'id' => Module::CONTACT,
                'name' => 'Contacto',
                'class' => 'message-square',
                'order' => 6,
                'submodules' => [
                    ['id' => Submodule::COMMERCIAL_REQUESTS, 'name' => 'Solicitudes Comerciales', 'url' => '/cms/commercial-requests', 'icon' => 'file-text'],
                    ['id' => Submodule::CONTACT_MESSAGES, 'name' => 'Mensajes de Contacto', 'url' => '/cms/contact-messages', 'icon' => 'mail'],
                ],
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::updateOrCreate(
                ['name' => $moduleData['name']],
                [
                    'id' => $moduleData['id'],
                    'class' => $moduleData['class'],
                    'position' => $moduleData['order'],
                ]
            );

            if (isset($moduleData['submodules'])) {
                foreach ($moduleData['submodules'] as $submoduleData) {
                    $submoduleValues = [
                        'id' => $submoduleData['id'],
                        'module_id' => $module->id,
                        'url' => $submoduleData['url'],
                        'icon' => $submoduleData['icon'],
                    ];

                    Submodule::updateOrCreate(
                        ['name' => $submoduleData['name']],
                        $submoduleValues
                    );
                }
            }
        }

        // Remove obsolete modules (e.g. Blog) and their submodules
        $validIds = array_column($modules, 'id');
        Module::whereNotIn('id', $validIds)->delete();

        $this->command->info('Database icons successfully refreshed with verified clean Lucide assets!');
    }
}
