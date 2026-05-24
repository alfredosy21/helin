<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Line;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lines = [
            [
                'name' => 'Implantología',
                'slug' => 'implantologia',
                'description' => 'Soluciones completas para implantes dentales y procedimientos reconstructivos',
                'seo_description' => 'Catálogo de implantes dentales, aditamentos y kits quirúrgicos para rehabilitación oral',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regeneración Ósea Guiada',
                'slug' => 'regeneracion-osea-guiada',
                'description' => 'Biomateriales y membranas para regeneración tisular guiada',
                'seo_description' => 'Productos para regeneración ósea y tejidos blandos en procedimientos dentales',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Osteosíntesis',
                'slug' => 'osteosintesis',
                'description' => 'Sistemas de fijación y osteosíntesis para estabilización maxilofacial',
                'seo_description' => 'Placas, tornillos y sistemas de fijación para cirugía maxilofacial y traumatología',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuidado Bucal',
                'slug' => 'cuidado-bucal',
                'description' => 'Productos especializados para higiene y cuidado oral post-tratamiento',
                'seo_description' => 'Línea Bluem de productos para cuidado bucal e higiene oral profesional',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instrumentos',
                'slug' => 'instrumentos',
                'description' => 'Instrumental quirúrgico y dental de alta precisión',
                'seo_description' => 'Instrumentos especializados para cirugía dental, implantología y procedimientos bucales',
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Equipos',
                'slug' => 'equipos',
                'description' => 'Equipamiento y tecnología para consultorios y clínicas dentales',
                'seo_description' => 'Sistemas y equipos para planificación digital y ejecución de tratamientos dentales',
                'is_active' => true,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Planificación Digital',
                'slug' => 'planificacion-digital',
                'description' => 'Soluciones digitales para planificación y guía quirúrgica',
                'seo_description' => 'Software y sistemas digitales para planificación precisa de tratamientos dentales',
                'is_active' => true,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('lines')->insert($lines);
    }
}
