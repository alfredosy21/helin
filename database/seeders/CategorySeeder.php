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
                'seo_description' => 'Catálogo de implantes dentales: implantes de titanio, cerámicos y soluciones de rehabilitación',
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
                'seo_description' => 'Aditamentos protésicos: pilares, muñones y componentes para rehabilitación sobre implantes',
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
                'seo_description' => 'Kits quirúrgicos de implantología: instrumental y componentes para cirugía dental',
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
                'seo_description' => 'Biomateriales dentales: membranas, injertos y materiales para regeneración ósea y tisular',
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
                'seo_description' => 'Regeneración guiada bucal GBR: membranas, biomateriales y técnicas de regeneración tisular',
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
                'seo_description' => 'Suturas dentales: hilos de seda, materiales absorbibles y no absorbibles para cirugía oral',
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
                'seo_description' => 'Placas de osteosíntesis: sistemas de fijación para cirugía maxilofacial y traumatología',
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
                'seo_description' => 'Tornillos de osteosíntesis: sistemas de fijación para cirugía maxilofacial y reconstrucción',
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
                'seo_description' => 'Cajetines y organizadores: sistemas de almacenamiento para instrumental dental y quirúrgico',
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
                'seo_description' => 'Cuidados quirúrgicos: productos especializados para post-operatorio y recuperación dental',
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
                'seo_description' => 'Cuidados diarios: productos de higiene bucal para mantenimiento y cuidado dental en casa',
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
                'seo_description' => 'Tijeras quirúrgicas dentales: instrumental especializado para cirugía oral y procedimientos bucales',
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
                'seo_description' => 'Pinzas dentales y quirúrgicas: instrumental especializado para procedimientos odontológicos',
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
                'seo_description' => 'Separadores dentales: instrumental para retracción gingival y separación tisular',
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
                'seo_description' => 'Cinceles dentales: instrumental para osteotomía y preparación ósea en implantología',
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
                'seo_description' => 'Periostótomos dentales: instrumental para disección del periostio y procedimientos quirúrgicos',
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
                'seo_description' => 'Equipos odontológicos: tecnología y equipamiento para clínicas y consultorios dentales',
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
                'seo_description' => 'Planificación digital dental: software y tecnología para planificación precisa de tratamientos',
                'is_active' => true,
                'order' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
