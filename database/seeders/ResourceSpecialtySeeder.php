<?php

namespace Database\Seeders;

use App\Models\ResourceSpecialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar especialidades de recursos existentes (desactivar restricciones de clave foránea)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ResourceSpecialty::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $resourceSpecialties = [
            [
                'name' => 'Cirugía Bucal',
                'slug' => 'cirugia-bucal',
                'description' => 'Procedimientos quirúrgicos en la cavidad oral y maxilares',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'name' => 'Maxilofacial',
                'slug' => 'maxilofacial',
                'description' => 'Tratamientos complejos de cara, boca y mandíbula',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'name' => 'Periodoncia',
                'slug' => 'periodoncia',
                'description' => 'Tratamiento de enfermedades de las encías y tejidos de soporte',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'name' => 'Ortodoncia',
                'slug' => 'ortodoncia',
                'description' => 'Corrección de malposiciones dentales y faciales',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'name' => 'Endodoncia',
                'slug' => 'endodoncia',
                'description' => 'Tratamiento de conductos radiculares y patologías pulpares',
                'is_active' => true,
                'position' => 5,
            ],
            [
                'name' => 'Implantología',
                'slug' => 'implantologia',
                'description' => 'Colocación de implantes dentales y rehabilitación protésica',
                'is_active' => true,
                'position' => 6,
            ],
            [
                'name' => 'Osteosíntesis',
                'slug' => 'osteosintesis',
                'description' => 'Sistemas de fijación ósea y reconstrucción maxilofacial',
                'is_active' => true,
                'position' => 7,
            ],
            [
                'name' => 'Biomateriales',
                'slug' => 'biomateriales',
                'description' => 'Materiales de regeneración y biocompatibilidad dental',
                'is_active' => true,
                'position' => 8,
            ],
            [
                'name' => 'Odontología General',
                'slug' => 'odontologia-general',
                'description' => 'Procedimientos generales y preventivos en odontología',
                'is_active' => true,
                'position' => 9,
            ],
        ];

        foreach ($resourceSpecialties as $resourceSpecialty) {
            ResourceSpecialty::create($resourceSpecialty);
        }

        $this->command->info('Resource specialties seeded successfully!');
    }
}
