<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

/**
 * Settings Seeder
 *
 * This seeder populates the settings table with default system settings.
 * Includes corporate information, social media links, SEO settings, and location data.
 */
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Settings::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Helin',
                'tagline' => 'Todo en Cirugía Odontológica Especializada.',
                'email' => 'hola@helin.company',
                'image' => null,
                'default_category_image' => 'categories/categoria1.png',
                'default_banner_image' => 'sections/banner_imp1.png',
                'address' => 'Venezuela',
                'contact_address' => 'Av. Principal con Calle 8, Edificio Médico, Plaza Level, Torre A, Piso 3, Caracas 1010, Venezuela',
                'phone' => '+58 424 466 9150',
                'shedule' => 'Lun-Vie: 8:00 AM - 6:00 PM',
                'facebook' => 'https://facebook.com/helinbeam',
                'instagram' => 'https://instagram.com/helinbeam',
                'youtube' => 'https://youtube.com/@helinbeam',
                'linkedin' => 'https://linkedin.com/company/helinbeam',
                'keywords' => 'implantes, cirugía odontológica, material dental, helin, productos médicos, reingeniería, cirugía guiada',
                'description' => 'Soluciones médicas de alta calidad para profesionales de la salud. Especialistas en implantología, reingeniería y cirugía guiada.',
                'copy' => '© ' . date('Y') . ' by helin.',
                'settings_description' => 'Configuración principal del sistema Helin Medical Solutions',
                'analytics_code' => null,
                'opinion_url' => 'https://forms.office.com/Pages/ResponsePage.aspx?id=ExampleSurveyLink',
                'offices' => [
                    ['name' => 'Caracas',      'zone' => 1, 'whatsapp' => 'https://wa.me/584242789481', 'url' => 'https://www.google.com/maps/place/10%C2%B029\'03.2%22N+66%C2%B051\'18.3%22W/@10.4842222,-66.8550833,1059m/data=!3m1!1e3!4m4!3m3!8m2!3d10.4842222!4d-66.8550833', 'active' => true],
                    ['name' => 'Valencia',     'zone' => 2, 'whatsapp' => 'https://wa.me/584244669150', 'url' => 'https://www.google.com/maps/place/10%C2%B012\'39.5%22N+68%C2%B000\'59.1%22W/@10.2109722,-68.0164167,1060m/data=!3m2!1e3!4b1!4m4!3m3!8m2!3d10.2109722!4d-68.0164167', 'active' => true],
                    ['name' => 'Barquisimeto', 'zone' => 3, 'whatsapp' => 'https://wa.me/584143805640', 'url' => 'https://www.google.com/maps/place/10%C2%B003\'53.2%22N+69%C2%B017\'02.9%22W/@10.0647778,-69.2841389,1061m/data=!3m2!1e3!4b1!4m4!3m3!8m2!3d10.0647778!4d-69.2841389', 'active' => true],
                    ['name' => 'Maracaibo',    'zone' => 4, 'whatsapp' => 'https://wa.me/584242550811', 'url' => 'https://www.google.com/maps/place/Centro+Comercial+Terraza+77/@10.66712,-71.6033406,1059m/data=!3m2!1e3!4b1!4m6!3m5!1s0x8e89990a82a8667f:0xb5f35dd883bdff5a!8m2!3d10.66712!4d-71.6033406!16s%2Fg%2F11gzmpqc73', 'active' => true],
                ],
                'contact_subjects' => [
                    ['value' => 'informacion-comercial', 'label' => 'Información comercial', 'active' => true],
                    ['value' => 'asesoria-productos', 'label' => 'Asesoría de productos', 'active' => true],
                    ['value' => 'cotizacion', 'label' => 'Cotización', 'active' => true],
                    ['value' => 'disponibilidad', 'label' => 'Disponibilidad de productos', 'active' => true],
                    ['value' => 'soporte-orden', 'label' => 'Soporte de orden', 'active' => true],
                    ['value' => 'recursos-clinicos', 'label' => 'Recursos clínicos', 'active' => true],
                    ['value' => 'otro', 'label' => 'Otro', 'active' => true],
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Settings seeded successfully!');
    }
}
