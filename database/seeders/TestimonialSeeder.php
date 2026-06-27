<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar testimonios existentes
        Testimonial::truncate();

        // Testimonios desde home.blade.php
        $testimonials = [
            [
                'content' => 'Excelente atención y muy buen acompañamiento comercial. Encontramos los productos necesarios para implantología.',
                'name' => 'Dra. María Fernanda López',
                'specialty' => 'Odontóloga implantóloga',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'content' => 'Helin nos ha brindado soluciones confiables y un portafolio muy completo. Destaco la rapidez en la atención.',
                'name' => 'Dr. José Andrés Rivas',
                'specialty' => 'Especialista en cirugía bucal',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'content' => 'Muy buena experiencia de compra. La plataforma es fácil de usar y el equipo comercial responde con rapidez.',
                'name' => 'Clínica Sonrisa Integral',
                'specialty' => 'Centro odontológico',
                'is_active' => true,
                'position' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
