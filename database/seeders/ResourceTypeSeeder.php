<?php

namespace Database\Seeders;

use App\Models\ResourceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar tipos de recursos existentes (desactivar restricciones de clave foránea)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ResourceType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $resourceTypes = [
            [
                'name' => 'Caso Clínico',
                'description' => 'Estudios de casos reales con diagnóstico, tratamiento y seguimiento',
                'icon' => '→',
                'format_label' => '▣ Artículo',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'name' => 'Video',
                'description' => 'Videos tutoriales, procedimientos quirúrgicos y demostraciones clínicas',
                'icon' => '▶',
                'format_label' => '▶ Video',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'name' => 'Manual',
                'description' => 'Manuales de uso, guías de procedimiento y documentación técnica',
                'icon' => '↓',
                'format_label' => '▤ PDF',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'name' => 'Ficha Técnica',
                'description' => 'Especificaciones técnicas, características y datos de productos',
                'icon' => '↓',
                'format_label' => '▤ PDF',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'name' => 'Guía Descargable',
                'description' => 'Guías prácticas, protocolos y documentos de consulta rápida',
                'icon' => '→',
                'format_label' => '▣ Artículo',
                'is_active' => true,
                'position' => 5,
            ],
            [
                'name' => 'Artículo',
                'description' => 'Artículos científicos, investigaciones y publicaciones académicas',
                'icon' => '→',
                'format_label' => '▣ Artículo',
                'is_active' => true,
                'position' => 6,
            ],
        ];

        foreach ($resourceTypes as $resourceType) {
            ResourceType::create($resourceType);
        }

        $this->command->info('Resource types seeded successfully!');
    }
}
