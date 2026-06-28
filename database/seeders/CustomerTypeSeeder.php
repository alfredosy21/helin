<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerType;

class CustomerTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Doctor',            'slug' => 'doctor',    'description' => 'Profesional médico odontólogo o especialista.',       'order' => 1],
            ['name' => 'Paciente',          'slug' => 'paciente',  'description' => 'Usuario final que requiere atención odontológica.',    'order' => 2],
            ['name' => 'Empresa',           'slug' => 'empresa',   'description' => 'Entidad corporativa o institución de salud.',          'order' => 3],
        ];

        foreach ($types as $type) {
            CustomerType::updateOrCreate(
                ['slug' => $type['slug']],
                array_merge($type, ['is_active' => true])
            );
        }
    }
}
