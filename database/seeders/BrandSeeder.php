<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'AB',
                'slug' => 'ab',
                'description' => 'Línea completa de implantes, aditamentos y kits quirúrgicos de alta precisión',
                'seo_description' => 'Productos AB para implantología dental: implantes, aditamentos y kits quirúrgicos',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GDT',
                'slug' => 'gdt',
                'description' => 'Soluciones avanzadas para implantes, aditamentos, kits quirúrgicos y biomateriales',
                'seo_description' => 'Productos GDT: implantes dentales, aditamentos, kits quirúrgicos y biomateriales de calidad',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tissum',
                'slug' => 'tissum',
                'description' => 'Línea Porcino especializada en biomateriales para regeneración tisular',
                'seo_description' => 'Biomateriales Tissum línea porcina para regeneración ósea y tisular guiada',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vera Derm Cellular',
                'slug' => 'vera-derm-cellular',
                'description' => 'Biomateriales avanzados para regeneración y reparación tisular',
                'seo_description' => 'Productos Vera Derm Cellular para biomateriales y regeneración tisular dental',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cytoplast',
                'slug' => 'cytoplast',
                'description' => 'Membranas y biomateriales especializados para regeneración guiada',
                'seo_description' => 'Productos Cytoplast: membranas y biomateriales para regeneración tisular guiada',
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skil',
                'slug' => 'skil',
                'description' => 'Línea de Seda especializada en suturas de alta calidad para procedimientos dentales',
                'seo_description' => 'Suturas Skil línea de seda para procedimientos quirúrgicos y dentales',
                'is_active' => true,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bluem',
                'slug' => 'bluem',
                'description' => 'Línea completa de productos para cuidado bucal e higiene oral profesional',
                'seo_description' => 'Productos Bluem para cuidado bucal, higiene oral y mantenimiento post-tratamiento',
                'is_active' => true,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}
