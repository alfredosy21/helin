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
            [
                'name' => 'Implantología',
                'slug' => 'implantologia',
                'description' => 'Sistema completo de implantes dentales para rehabilitación oral',
                'seo_description' => 'Descubre nuestra selección de implantes dentales de alta calidad. Implantes de titanio, cónicos y cerámicos para rehabilitación oral con garantía Helin. Envíos a todo Venezuela.',
                'seo_keywords' => 'implantes dentales, implantes de titanio, implantes cónicos, rehabilitación oral, implantes Venezuela, helin implantes',
                'is_featured' => true,
                'banner_label' => 'Bienvenidos al Catálogo de Implantología',
                'banner_title' => 'Todo Para Tus Procedimientos De Implantología En Un Solo Lugar',
                'banner_description' => 'Encuentra componentes, instrumentos y soluciones especializadas para optimizar cada etapa clínica.',
                'banner_image' => 'sections/banner_imp.png',
                'image' => 'categories/categoria1.png',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regeneración',
                'slug' => 'regeneracion',
                'description' => 'Biomateriales y membranas para regeneración ósea y tisular guiada',
                'seo_description' => 'Biomateriales dentales de última generación. Membranas, injertos óseos y materiales para regeneración tisular con certificación internacional. Calidad Helin.',
                'seo_keywords' => 'regeneración guiada bucal, GBR dental, membranas reabsorbibles, regeneración ósea, biomateriales dentales, helin regeneración',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Regeneración',
                'banner_title' => 'Soluciones Completas En Regeneración Ósea Guiada',
                'banner_description' => 'Encuentra los mejores productos para procedimientos de regeneración ósea y tisular.',
                'banner_image' => 'sections/banner_rc_clinic.png',
                'image' => 'categories/cat5.png',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Osteosíntesis',
                'slug' => 'osteosintesis',
                'description' => 'Sistemas de placas, tornillos y fijación para cirugía maxilofacial',
                'seo_description' => 'Sistemas de osteosíntesis maxilofacial. Placas, tornillos y fijación quirúrgica de titanio con precisión milimétrica para traumatología y reconstrucción. Calidad Helin.',
                'seo_keywords' => 'placas osteosíntesis, tornillos fijación, fijación maxilofacial, placas titanio, traumatología dental, helin osteosíntesis',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Osteosíntesis',
                'banner_title' => 'Placas Y Sistemas De Fijación Para Cirugía Maxilofacial',
                'banner_description' => 'Sistemas de osteosíntesis de titanio de alta precisión para reconstrucción ósea.',
                'banner_image' => null,
                'image' => 'categories/cat7.png',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuidado Bucal',
                'slug' => 'cuidado-bucal',
                'description' => 'Productos para higiene oral, cuidado post-tratamiento y suturas',
                'seo_description' => 'Productos especializados para cuidado bucal e higiene oral profesional. Suturas, cuidados post-quirúrgicos y productos para el paciente. Línea Helin.',
                'seo_keywords' => 'cuidado bucal, suturas dentales, higiene oral, cuidados post-operatorio, productos cuidado dental, helin cuidado',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Cuidado Bucal',
                'banner_title' => 'Cuidados Especiales Para La Salud Bucal',
                'banner_description' => 'Productos especializados para el cuidado diario y postoperatorio del paciente.',
                'banner_image' => null,
                'image' => 'categories/cat6.png',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instrumentos',
                'slug' => 'instrumentos',
                'description' => 'Instrumental quirúrgico y dental de alta precisión',
                'seo_description' => 'Instrumentos especializados para cirugía dental, implantología y procedimientos bucales. Tijeras, pinzas, separadores, cinceles y kits quirúrgicos. Calidad Helin.',
                'seo_keywords' => 'instrumentos dentales, instrumental quirúrgico, kits quirúrgicos, tijeras, pinzas, separadores, helin instrumentos',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Instrumentos',
                'banner_title' => 'Instrumentos De Precisión Para Procedimientos Odontológicos',
                'banner_description' => 'Instrumental especializado para cirugía dental y procedimientos bucales.',
                'banner_image' => null,
                'image' => 'categories/cat3.png',
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Equipos',
                'slug' => 'equipos',
                'description' => 'Equipamiento y tecnología para consultorios y clínicas dentales',
                'seo_description' => 'Equipos odontológicos de última tecnología. Equipamiento completo para clínicas y consultorios con garantía y servicio técnico. Línea Helin.',
                'seo_keywords' => 'equipos odontológicos, tecnología dental, consultorios dentales, equipos clínicos, instrumental odontológico, helin equipos',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Equipos',
                'banner_title' => 'Equipos Odontológicos De Alta Tecnología',
                'banner_description' => 'Equipos especializados para optimizar cada procedimiento en tu consultorio.',
                'banner_image' => null,
                'image' => null,
                'is_active' => true,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Planificación Digital',
                'slug' => 'planificacion-digital',
                'description' => 'Soluciones digitales para planificación y guía quirúrgica',
                'seo_description' => 'Soluciones de planificación digital dental. Software y tecnología para planificación precisa de tratamientos con soporte técnico. Especialistas Helin.',
                'seo_keywords' => 'planificación digital, tecnología dental, software odontológico, guías quirúrgicas, planificación implantes, helin digital',
                'is_featured' => false,
                'banner_label' => 'Catálogo de Planificación Digital',
                'banner_title' => 'Soluciones Digitales Para Tu Práctica Odontológica',
                'banner_description' => 'Herramientas de planificación digital, impresión 3D y escaneo intraoral de última generación.',
                'banner_image' => null,
                'image' => 'categories/cat4.png',
                'is_active' => true,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
