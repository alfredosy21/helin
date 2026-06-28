<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar recursos existentes (desactivar restricciones de clave foránea)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Resource::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Recursos con IDs de especialidad y tipo de recurso
        $resources = [
            [
                'title' => 'Regeneración ósea guiada en zona posterior',
                'description' => 'Protocolo clínico con biomateriales, membrana y seguimiento del caso.',
                'type' => 'case_study',
                'resource_type_id' => 1, // Caso Clínico
                'resource_specialty_id' => 1, // Cirugía Bucal
                'format' => 'article',
                'file_path' => 'resources/casos/regeneracion-osea-posterior.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/caso-regeneracion.jpg',
                'is_active' => true,
                'position' => 1,
                'featured' => false,
            ],
            [
                'title' => 'Colocación de implante con protocolo quirúrgico',
                'description' => 'Video de referencia para planificación, inserción y control de estabilidad.',
                'type' => 'video',
                'resource_type_id' => 2, // Video
                'resource_specialty_id' => 6, // Implantología
                'format' => 'video',
                'file_path' => 'resources/videos/colocacion-implante.mp4',
                'url' => null,
                'thumbnail' => 'thumbnails/video-implante.jpg',
                'is_active' => true,
                'position' => 2,
                'featured' => false,
            ],
            [
                'title' => 'Manual técnico de placas y tornillos',
                'description' => 'Guía descargable para selección, uso y consideraciones clínicas.',
                'type' => 'manual',
                'resource_type_id' => 3, // Manual
                'resource_specialty_id' => 7, // Osteosíntesis
                'format' => 'pdf',
                'file_path' => 'resources/manuales/placas-tornillos.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/manual-placas.jpg',
                'is_active' => true,
                'position' => 3,
                'featured' => false,
            ],
            [
                'title' => 'Ficha técnica de membrana reabsorbible',
                'description' => 'Especificaciones, indicaciones y recomendaciones de manipulación.',
                'type' => 'technical_sheet',
                'resource_type_id' => 4, // Ficha Técnica
                'resource_specialty_id' => 8, // Biomateriales
                'format' => 'pdf',
                'file_path' => 'resources/fichas/membrana-reabsorbible.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/ficha-membrana.jpg',
                'is_active' => true,
                'position' => 4,
                'featured' => false,
            ],
            [
                'title' => 'Guía clínica para manejo de tejidos blandos',
                'description' => 'Recomendaciones prácticas para procedimientos regenerativos.',
                'type' => 'downloadable_guide',
                'resource_type_id' => 5, // Guía Descargable
                'resource_specialty_id' => 3, // Periodoncia
                'format' => 'article',
                'file_path' => 'resources/guias/manejo-tejidos-blandos.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/guia-tejidos.jpg',
                'is_active' => true,
                'position' => 5,
                'featured' => false,
            ],
            [
                'title' => 'Reconstrucción con sistema de osteosíntesis',
                'description' => 'Resumen del abordaje, materiales utilizados y evolución clínica.',
                'type' => 'case_study',
                'resource_type_id' => 1, // Caso Clínico
                'resource_specialty_id' => 2, // Maxilofacial
                'format' => 'article',
                'file_path' => 'resources/casos/reconstruccion-osteosintesis.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/caso-osteosintesis.jpg',
                'is_active' => true,
                'position' => 6,
                'featured' => false,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
