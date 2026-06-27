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

        // Recursos desde recursos-clinicos.blade.php
        $resources = [
            [
                'title' => 'Regeneración ósea guiada en zona posterior',
                'description' => 'Protocolo clínico con biomateriales, membrana y seguimiento del caso.',
                'type' => 'case_study',
                'specialty' => 'Cirugía Bucal',
                'format' => 'Artículo',
                'tags' => json_encode(['Cirugía Bucal', 'GBR']),
                'file_path' => 'resources/casos/regeneracion-osea-posterior.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/caso-regeneracion.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 1,
                'featured' => false,
            ],
            [
                'title' => 'Colocación de implante con protocolo quirúrgico',
                'description' => 'Video de referencia para planificación, inserción y control de estabilidad.',
                'type' => 'video',
                'specialty' => 'Implantología',
                'format' => 'Video',
                'tags' => json_encode(['Implantología', 'AB']),
                'file_path' => 'resources/videos/colocacion-implante.mp4',
                'url' => null,
                'thumbnail' => 'thumbnails/video-implante.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 2,
                'featured' => false,
            ],
            [
                'title' => 'Manual técnico de placas y tornillos',
                'description' => 'Guía descargable para selección, uso y consideraciones clínicas.',
                'type' => 'manual',
                'specialty' => 'Osteosíntesis',
                'format' => 'PDF',
                'tags' => json_encode(['Osteosíntesis', 'PDF']),
                'file_path' => 'resources/manuales/placas-tornillos.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/manual-placas.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 3,
                'featured' => false,
            ],
            [
                'title' => 'Ficha técnica de membrana reabsorbible',
                'description' => 'Especificaciones, indicaciones y recomendaciones de manipulación.',
                'type' => 'technical_sheet',
                'specialty' => 'Biomateriales',
                'format' => 'PDF',
                'tags' => json_encode(['Biomateriales', 'PDF']),
                'file_path' => 'resources/fichas/membrana-reabsorbible.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/ficha-membrana.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 4,
                'featured' => false,
            ],
            [
                'title' => 'Guía clínica para manejo de tejidos blandos',
                'description' => 'Recomendaciones prácticas para procedimientos regenerativos.',
                'type' => 'downloadable_guide',
                'specialty' => 'Periodoncia',
                'format' => 'Artículo',
                'tags' => json_encode(['Periodoncia', 'Soporte']),
                'file_path' => 'resources/guias/manejo-tejidos-blandos.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/guia-tejidos.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 5,
                'featured' => false,
            ],
            [
                'title' => 'Reconstrucción con sistema de osteosíntesis',
                'description' => 'Resumen del abordaje, materiales utilizados y evolución clínica.',
                'type' => 'case_study',
                'specialty' => 'Maxilofacial',
                'format' => 'Artículo',
                'tags' => json_encode(['Maxilofacial', 'Fijación']),
                'file_path' => 'resources/casos/reconstruccion-osteosintesis.pdf',
                'url' => null,
                'thumbnail' => 'thumbnails/caso-osteosintesis.jpg',
                'is_active' => true,
                'views' => 0,
                'position' => 6,
                'featured' => false,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
