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
                'slug' => 'caso-clinico',
                'description' => 'Estudios de casos reales con diagnóstico, tratamiento y seguimiento',
                'icon' => 'fas fa-file-medical',
                'color' => '#3b82f6',
                'is_active' => true,
                'position' => 1,
                'config' => json_encode([
                    'allowed_formats' => ['pdf', 'doc', 'docx'],
                    'max_file_size' => '10MB',
                    'requires_images' => true,
                    'template_fields' => ['paciente', 'diagnóstico', 'tratamiento', 'evolución']
                ]),
            ],
            [
                'name' => 'Video',
                'slug' => 'video',
                'description' => 'Videos tutoriales, procedimientos quirúrgicos y demostraciones clínicas',
                'icon' => 'fas fa-video',
                'color' => '#ef4444',
                'is_active' => true,
                'position' => 2,
                'config' => json_encode([
                    'allowed_formats' => ['mp4', 'avi', 'mov'],
                    'max_file_size' => '500MB',
                    'requires_thumbnail' => true,
                    'video_quality' => 'HD'
                ]),
            ],
            [
                'name' => 'Manual',
                'slug' => 'manual',
                'description' => 'Manuales de uso, guías de procedimiento y documentación técnica',
                'icon' => 'fas fa-book',
                'color' => '#10b981',
                'is_active' => true,
                'position' => 3,
                'config' => json_encode([
                    'allowed_formats' => ['pdf', 'doc', 'docx'],
                    'max_file_size' => '20MB',
                    'requires_index' => true,
                    'printable' => true
                ]),
            ],
            [
                'name' => 'Ficha Técnica',
                'slug' => 'ficha-tecnica',
                'description' => 'Especificaciones técnicas, características y datos de productos',
                'icon' => 'fas fa-clipboard-list',
                'color' => '#f59e0b',
                'is_active' => true,
                'position' => 4,
                'config' => json_encode([
                    'allowed_formats' => ['pdf', 'doc'],
                    'max_file_size' => '5MB',
                    'requires_specifications' => true,
                    'downloadable' => true
                ]),
            ],
            [
                'name' => 'Guía Descargable',
                'slug' => 'guia-descargable',
                'description' => 'Guías prácticas, protocolos y documentos de consulta rápida',
                'icon' => 'fas fa-download',
                'color' => '#8b5cf6',
                'is_active' => true,
                'position' => 5,
                'config' => json_encode([
                    'allowed_formats' => ['pdf', 'epub'],
                    'max_file_size' => '15MB',
                    'mobile_friendly' => true,
                    'printable' => true
                ]),
            ],
            [
                'name' => 'Artículo',
                'slug' => 'articulo',
                'description' => 'Artículos científicos, investigaciones y publicaciones académicas',
                'icon' => 'fas fa-newspaper',
                'color' => '#06b6d4',
                'is_active' => true,
                'position' => 6,
                'config' => json_encode([
                    'allowed_formats' => ['pdf', 'doc', 'docx'],
                    'max_file_size' => '8MB',
                    'requires_references' => true,
                    'peer_reviewed' => false
                ]),
            ],
        ];

        foreach ($resourceTypes as $resourceType) {
            ResourceType::create($resourceType);
        }

        $this->command->info('Resource types seeded successfully!');
    }
}
