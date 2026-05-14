<?php

namespace Database\Seeders;

use App\Models\Sections;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de sembrar para evitar duplicados si se corre varias veces
        DB::table('sections')->truncate();

        $sections = [
            [
                'title' => 'Innovación en Dispositivos Médicos',
                'content' => 'En Helin, nos especializamos en la distribución de implantes clínicos e instrumentos quirúrgicos de alta precisión. Cumplimos con los más altos estándares de calidad internacional para garantizar el éxito de cada procedimiento.',
                'image' => 'hero-medical-devices.jpg',
                'name_button' => 'Ver Catálogo',
                'url_button' => 'https://helin.com/catalogo',
                'status' => 1,
                'status_content' => 1,
            ],
            [
                'title' => 'Nuestros Servicios Especializados',
                'content' => 'Ofrecemos soporte técnico presencial, logística de entrega inmediata para emergencias quirúrgicas y asesoría en la selección de materiales osteosíntesis.',
                'image' => 'services-bg.jpg',
                'name_button' => 'Conocer Más',
                'url_button' => 'www.helin.com/servicios',
                'status' => 1,
                'status_content' => 1,
            ],
            [
                'title' => 'Compromiso con la Excelencia',
                'content' => 'Nuestra misión es facilitar el acceso a tecnología médica avanzada en toda la región, trabajando de la mano con instituciones de salud líderes.',
                'image' => 'about-us.jpg',
                'name_button' => '¿Quiénes Somos?',
                'url_button' => 'http://helin.com/nosotros',
                'status' => 1,
                'status_content' => 1,
            ],
            [
                'title' => 'Sección de Promociones (Borrador)',
                'content' => 'Contenido temporal sobre descuentos en instrumental básico para clínicas nuevas.',
                'image' => 'promo.jpg',
                'name_button' => 'Ver Ofertas',
                'url_button' => 'https://helin.com/ofertas',
                'status' => 0, // Se crea como borrador
                'status_content' => 0,
            ],
        ];

        foreach ($sections as $data) {
            Sections::create($data);
        }
    }
}