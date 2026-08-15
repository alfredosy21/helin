<?php

namespace Database\Seeders;

use App\Models\PageSeo;
use Illuminate\Database\Seeder;

/**
 * PageSeo Seeder
 *
 * This seeder populates the page_seo table with SEO metadata overrides
 * for public site pages. Each record corresponds to a static page that
 * can have its SEO customised via the CMS.
 */
class PageSeoSeeder extends Seeder
{
    public function run(): void
    {
        PageSeo::updateOrCreate(
            ['page_slug' => 'home'],
            [
                'seo_title' => 'Helin - Cirugía Odontológica Especializada',
                'seo_description' => 'Soluciones médicas de alta calidad para profesionales de la salud. Especialistas en implantología, reingeniería y cirugía guiada.',
                'seo_keywords' => 'implantes, cirugía odontológica, material dental, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'catalogo'],
            [
                'seo_title' => 'Catálogo de productos Helin',
                'seo_description' => 'Explora nuestro catálogo de productos para implantes, instrumentos y materiales dentales.',
                'seo_keywords' => 'catálogo, productos dentales, implantes, instrumentos odontológicos, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'producto'],
            [
                'seo_title' => 'Producto Helin',
                'seo_description' => 'Ficha detallada del producto con especificaciones técnicas y precio.',
                'seo_keywords' => 'producto, ficha técnica, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'solicitud'],
            [
                'seo_title' => 'Solicitud enviada - Helin',
                'seo_description' => 'Tu solicitud ha sido procesada exitosamente. Gracias por confiar en Helin.',
                'seo_keywords' => 'solicitud, confirmación, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'solicitud-enviada'],
            [
                'seo_title' => 'Solicitud Enviada - Helin',
                'seo_description' => 'Gracias por tu solicitud. Pronto nos pondremos en contacto contigo.',
                'seo_keywords' => 'solicitud enviada, confirmación, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'contactanos'],
            [
                'seo_title' => 'Contacto - Helin',
                'seo_description' => 'Contacta con nuestro equipo comercial o de soporte técnico.',
                'seo_keywords' => 'contacto, ayuda, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'nuestra-empresa'],
            [
                'seo_title' => 'Nuestra Empresa - Helin',
                'seo_description' => 'Conoce nuestra historia, misión y valores en Helin Medical Solutions.',
                'seo_keywords' => 'nuestra empresa, sobre nosotros, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'politicas'],
            [
                'seo_title' => 'Políticas - Helin',
                'seo_description' => 'Condiciones de envío, garantías y políticas comerciales de Helin.',
                'seo_keywords' => 'políticas, garantías, envío, helin',
                'og_image' => null,
            ]
        );

        PageSeo::updateOrCreate(
            ['page_slug' => 'recursos-clinicos'],
            [
                'seo_title' => 'Recursos Clínicos - Helin',
                'seo_description' => 'Casos clínicos, videos, manuales y recursos para profesionales odontológicos.',
                'seo_keywords' => 'recursos clínicos, casos clínicos, videos, helin',
                'og_image' => null,
            ]
        );

        $this->command->info('PageSeo seeded successfully!');
    }
}