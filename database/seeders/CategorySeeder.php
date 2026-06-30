<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->delete();

        $categories = [
            // Implantes
            [
                'name' => 'Implantes',
                'slug' => 'implantes',
                'description' => 'Sistema completo de implantes dentales para rehabilitación oral',
                'seo_description' => 'Descubre nuestra selección de implantes dentales de alta calidad. Implantes de titanio, cónicos y cerámicos para rehabilitación oral con garantía Helin. Envíos a todo Venezuela.',
                'seo_keywords' => 'implantes dentales, implantes de titanio, implantes cónicos, rehabilitación oral, implantes Venezuela, helin implantes',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Aditamentos
            [
                'name' => 'Aditamentos',
                'slug' => 'aditamentos',
                'description' => 'Componentes y accesorios para prótesis sobre implantes',
                'seo_description' => 'Explora nuestros aditamentos protésicos de precisión. Pilares, muñones y componentes para rehabilitación sobre implantes con la calidad Helin. Tiempo de entrega garantizado.',
                'seo_keywords' => 'aditamentos dentales, pilares protésicos, muñones implantes, componentes protésicos, rehabilitación implantes, helin aditamentos',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Kits Quirúrgicos
            [
                'name' => 'Kits Quirúrgicos',
                'slug' => 'kits-quirurgicos',
                'description' => 'Conjuntos completos para procedimientos de implantología',
                'seo_description' => 'Kits quirúrgicos completos para implantología. Instrumental especializado y componentes para cirugía dental con esterilización garantizada. Productos Helin.',
                'seo_keywords' => 'kits quirúrgicos, instrumental implantología, kits dental, cirugía dental, instrumental odontológico, helin kits',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Biomateriales
            [
                'name' => 'Biomateriales',
                'slug' => 'biomateriales',
                'description' => 'Materiales biocompatibles para regeneración y reparación tisular',
                'seo_description' => 'Biomateriales dentales de última generación. Membranas, injertos óseos y materiales para regeneración tisular con certificación internacional. Calidad Helin.',
                'seo_keywords' => 'biomateriales dentales, membranas dentales, injertos óseos, regeneración tisular, biomateriales Venezuela, helin biomateriales',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Regeneración Guiada Bucal (GBR)
            [
                'name' => 'Regeneración Guiada Bucal (GBR)',
                'slug' => 'regeneracion-guiada-bucal-gbr',
                'description' => 'Técnicas y materiales para regeneración ósea y tisular guiada',
                'seo_description' => 'Técnicas GBR de regeneración guiada bucal. Membranas reabsorbibles y biomateriales para regeneración ósea con resultados clínicos comprobados. Especialistas Helin.',
                'seo_keywords' => 'regeneración guiada bucal, GBR dental, membranas reabsorbibles, regeneración ósea, técnicas GBR, helin regeneración',
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Suturas
            [
                'name' => 'Suturas',
                'slug' => 'suturas',
                'description' => 'Materiales de sutura para procedimientos quirúrgicos bucales',
                'seo_description' => 'Suturas dentales de alta calidad. Hilos de seda, materiales absorbibles y no absorbibles para cirugía oral con garantía de esterilización. Productos Helin.',
                'seo_keywords' => 'suturas dentales, hilos de seda, suturas absorbibles, materiales sutura, cirugía oral, helin suturas',
                'is_active' => true,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Placas
            [
                'name' => 'Placas',
                'slug' => 'placas',
                'description' => 'Sistemas de placas para osteosíntesis y fijación maxilofacial',
                'seo_description' => 'Sistemas de placas para osteosíntesis maxilofacial. Fijación quirúrgica de titanio con precisión milimétrica para traumatología y reconstrucción. Calidad Helin.',
                'seo_keywords' => 'placas osteosíntesis, fijación maxilofacial, placas titanio, traumatología dental, osteosíntesis Venezuela, helin placas',
                'is_active' => true,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tornillos
            [
                'name' => 'Tornillos',
                'slug' => 'tornillos',
                'description' => 'Tornillos especializados para fijación y osteosíntesis',
                'seo_description' => 'Tornillos de osteosíntesis de precisión. Sistemas de fijación para cirugía maxilofacial y reconstrucción con garantía de calidad. Productos Helin.',
                'seo_keywords' => 'tornillos osteosíntesis, fijación quirúrgica, tornillos titanio, cirugía maxilofacial, osteosíntesis dental, helin tornillos',
                'is_active' => true,
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Cajetín
            [
                'name' => 'Cajetín',
                'slug' => 'cajetin',
                'description' => 'Organizadores y cajas para instrumental dental y quirúrgico',
                'seo_description' => 'Cajetines y organizadores profesionales. Sistemas de almacenamiento para instrumental dental y quirúrgico con diseño ergonómico. Productos Helin.',
                'seo_keywords' => 'cajetines dentales, organizadores instrumental, cajas quirúrgicas, almacenamiento dental, instrumental organizado, helin cajetines',
                'is_active' => true,
                'order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Cuidados Especiales (Quirúrgicos)
            [
                'name' => 'Cuidados Especiales (Quirúrgicos)',
                'slug' => 'cuidados-especiales-quirurgicos',
                'description' => 'Productos para cuidado post-quirúrgico y recuperación dental',
                'seo_description' => 'Productos especializados para cuidado post-quirúrgico. Soluciones para recuperación dental con ingredientes clínicamente probados. Línea Helin.',
                'seo_keywords' => 'cuidados quirúrgicos, post-operatorio dental, recuperación bucal, cuidados especiales, productos post-cirugía, helin cuidados',
                'is_active' => true,
                'order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Cuidados Diarios (Paciente)
            [
                'name' => 'Cuidados Diarios (Paciente)',
                'slug' => 'cuidados-diarios-paciente',
                'description' => 'Productos para higiene y cuidado bucal diario del paciente',
                'seo_description' => 'Productos para higiene bucal diaria. Cepillos, pastas y enjuagues bucales para cuidado dental en casa con ingredientes de calidad. Línea Helin.',
                'seo_keywords' => 'cuidados diarios bucales, higiene dental, cepillos dentales, pasta dental, enjuague bucal, helin cuidados',
                'is_active' => true,
                'order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tijeras
            [
                'name' => 'Tijeras',
                'slug' => 'tijeras',
                'description' => 'Tijeras especializadas para procedimientos quirúrgicos bucales',
                'seo_description' => 'Tijeras quirúrgicas de precisión. Instrumental especializado para cirugía oral y procedimientos bucales con acero inoxidable. Calidad Helin.',
                'seo_keywords' => 'tijeras quirúrgicas, instrumental dental, tijeras bucales, cirugía oral, instrumental quirúrgico, helin tijeras',
                'is_active' => true,
                'order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pinzas
            [
                'name' => 'Pinzas',
                'slug' => 'pinzas',
                'description' => 'Pinzas especializadas para manipulación dental y quirúrgica',
                'seo_description' => 'Pinzas dentales de alta precisión. Instrumental especializado para procedimientos odontológicos con mango ergonómico. Productos Helin.',
                'seo_keywords' => 'pinzas dentales, instrumental quirúrgico, pinzas bucales, manipulación dental, instrumental odontológico, helin pinzas',
                'is_active' => true,
                'order' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Separadores
            [
                'name' => 'Separadores',
                'slug' => 'separadores',
                'description' => 'Instrumentos para separación y retracción tisular',
                'seo_description' => 'Separadores dentales profesionales. Instrumental para retracción gingival y separación tisular con diseño ergonómico. Calidad Helin.',
                'seo_keywords' => 'separadores dentales, retracción gingival, separadores tisulares, instrumental quirúrgico, cirugía bucal, helin separadores',
                'is_active' => true,
                'order' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Cinceles
            [
                'name' => 'Cinceles',
                'slug' => 'cinceles',
                'description' => 'Cinceles para osteotomía y preparación ósea dental',
                'seo_description' => 'Cinceles para osteotomía de precisión. Instrumental para preparación ósea en implantología con filo duradero. Productos Helin.',
                'seo_keywords' => 'cinceles dentales, osteotomía dental, preparación ósea, instrumental implantología, cinceles quirúrgicos, helin cinceles',
                'is_active' => true,
                'order' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Periostótomos
            [
                'name' => 'Periostótomos',
                'slug' => 'periostomos',
                'description' => 'Instrumentos para disección y manipulación del periostio',
                'seo_description' => 'Periostótomos de precisión quirúrgica. Instrumental para disección del periostio y procedimientos bucales con acero de alta calidad. Productos Helin.',
                'seo_keywords' => 'periostótomos dentales, instrumental quirúrgico, disección periostio, instrumental bucal, cirugía periodontal, helin periostótomos',
                'is_active' => true,
                'order' => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Equipos odontológicos
            [
                'name' => 'Equipos odontológicos',
                'slug' => 'equipos-odontologicos',
                'description' => 'Equipamiento y tecnología para consultorios dentales',
                'seo_description' => 'Equipos odontológicos de última tecnología. Equipamiento completo para clínicas y consultorios con garantía y servicio técnico. Línea Helin.',
                'seo_keywords' => 'equipos odontológicos, tecnología dental, consultorios dentales, equipos clínicos, instrumental odontológico, helin equipos',
                'is_active' => true,
                'order' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Planificación Digital
            [
                'name' => 'Planificación Digital',
                'slug' => 'planificacion-digital',
                'description' => 'Soluciones digitales para planificación y guía quirúrgica',
                'seo_description' => 'Soluciones de planificación digital dental. Software y tecnología para planificación precisa de tratamientos con soporte técnico. Especialistas Helin.',
                'seo_keywords' => 'planificación digital, tecnología dental, software odontológico, guías quirúrgicas, planificación implantes, helin digital',
                'is_active' => true,
                'order' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
