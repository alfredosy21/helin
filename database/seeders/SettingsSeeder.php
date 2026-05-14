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
                'name' => 'Helin CMS',
                'email' => 'contacto@helin.com',
                'image' => null,
                'address' => 'Av. Principal #123, Ciudad',
                'phone' => '+1 234 567 8900',
                'shedule' => 'Lun-Vie: 9:00 AM - 6:00 PM',
                'facebook' => 'https://facebook.com/helincms',
                'instagram' => 'https://instagram.com/helincms',
                'youtube' => 'https://youtube.com/@helincms',
                'linkedin' => 'https://linkedin.com/company/helincms',
                'keywords' => 'helin, cms, sistema, gestión, contenido',
                'description' => 'Sistema de gestión de contenido Helin CMS - Plataforma completa para administración de sitios web',
                'copy' => '© ' . date('Y') . ' Helin CMS. Todos los derechos reservados.',
                'settings_description' => 'Configuración principal del sistema Helin CMS',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Settings seeded successfully!');
    }
}
