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
                'caracas_whatsapp' => 'https://wa.me/584242789481',
                'caracas_location' => 'Centro Ciudad Comercial Tamanaco, Caracas.',
                'valencia_whatsapp' => 'https://wa.me/584244669150',
                'valencia_location' => 'Av. Andrés Eloy Blanco, Urb. Prebo, CCP Prebo, Piso 3. Valencia, Carabobo.',
                'barquisimeto_whatsapp' => 'https://wa.me/584143805640',
                'barquisimeto_location' => 'Barquisimeto.',
                'maracaibo_whatsapp' => 'https://wa.me/584242550811',
                'maracaibo_location' => 'Terraza 77, Piso 1, local de helin. Maracaibo.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Settings seeded successfully!');
    }
}
