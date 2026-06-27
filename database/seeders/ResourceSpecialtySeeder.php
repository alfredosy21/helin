<?php

namespace Database\Seeders;

use App\Models\ResourceSpecialty;
use Illuminate\Database\Seeder;

class ResourceSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar especialidades de recursos existentes
        ResourceSpecialty::truncate();

        $resourceSpecialties = [
            [
                'name' => 'Cirugía Bucal',
                'slug' => 'cirugia-bucal',
                'description' => 'Procedimientos quirúrgicos en la cavidad oral y maxilares',
                'icon' => 'fas fa-procedures',
                'color' => '#dc2626',
                'is_active' => true,
                'position' => 1,
                'config' => json_encode([
                    'common_procedures' => ['extracciones', 'implantes', 'injertos óseos'],
                    'equipment_required' => ['rayos x', 'equipo quirúrgico', 'bisturí'],
                    'specialist_level' => 'cirujano maxilofacial'
                ]),
            ],
            [
                'name' => 'Maxilofacial',
                'slug' => 'maxilofacial',
                'description' => 'Tratamientos complejos de cara, boca y mandíbula',
                'icon' => 'fas fa-user-md',
                'color' => '#ea580c',
                'is_active' => true,
                'position' => 2,
                'config' => json_encode([
                    'common_procedures' => ['cirugía ortognática', 'reconstrucción facial', 'trauma facial'],
                    'equipment_required' => ['TC 3D', 'placas osteosíntesis', 'microscopio'],
                    'specialist_level' => 'cirujano maxilofacial'
                ]),
            ],
            [
                'name' => 'Periodoncia',
                'slug' => 'periodoncia',
                'description' => 'Tratamiento de enfermedades de las encías y tejidos de soporte',
                'icon' => 'fas fa-tooth',
                'color' => '#16a34a',
                'is_active' => true,
                'position' => 3,
                'config' => json_encode([
                    'common_procedures' => ['limpieza profunda', 'cirugía periodontal', 'injertos'],
                    'equipment_required' => ['ultrasonido', 'curette', 'membranas'],
                    'specialist_level' => 'periodoncista'
                ]),
            ],
            [
                'name' => 'Ortodoncia',
                'slug' => 'ortodoncia',
                'description' => 'Corrección de malposiciones dentales y faciales',
                'icon' => 'fas fa-teeth',
                'color' => '#2563eb',
                'is_active' => true,
                'position' => 4,
                'config' => json_encode([
                    'common_procedures' => ['brackets', 'alineadores invisibles', 'retenedores'],
                    'equipment_required' => ['arcadas', 'brackets', 'alambres'],
                    'specialist_level' => 'ortodoncista'
                ]),
            ],
            [
                'name' => 'Endodoncia',
                'slug' => 'endodoncia',
                'description' => 'Tratamiento de conductos radiculares y patologías pulpares',
                'icon' => 'fas fa-syringe',
                'color' => '#7c3aed',
                'is_active' => true,
                'position' => 5,
                'config' => json_encode([
                    'common_procedures' => ['tratamiento de conductos', 'apicectomías', 'retratamientos'],
                    'equipment_required' => ['localizador apical', 'microscopio', 'rotadores'],
                    'specialist_level' => 'endodoncista'
                ]),
            ],
            [
                'name' => 'Implantología',
                'slug' => 'implantologia',
                'description' => 'Colocación de implantes dentales y rehabilitación protésica',
                'icon' => 'fas fa-bone',
                'color' => '#0891b2',
                'is_active' => true,
                'position' => 6,
                'config' => json_encode([
                    'common_procedures' => ['implantes unitarios', 'prótesis sobre implantes', 'injertos'],
                    'equipment_required' => ['tomógrafo', 'fresadoras', 'kits de implantes'],
                    'specialist_level' => 'implantólogo'
                ]),
            ],
            [
                'name' => 'Osteosíntesis',
                'slug' => 'osteosintesis',
                'description' => 'Sistemas de fijación ósea y reconstrucción maxilofacial',
                'icon' => 'fas fa-bone-break',
                'color' => '#be123c',
                'is_active' => true,
                'position' => 7,
                'config' => json_encode([
                    'common_procedures' => ['fijación de fracturas', 'reconstrucción mandibular', 'distracción ósea'],
                    'equipment_required' => ['placas y tornillos', 'tornillos de cortical', 'instrumental específico'],
                    'specialist_level' => 'cirujano maxilofacial'
                ]),
            ],
            [
                'name' => 'Biomateriales',
                'slug' => 'biomateriales',
                'description' => 'Materiales de regeneración y biocompatibilidad dental',
                'icon' => 'fas fa-flask',
                'color' => '#059669',
                'is_active' => true,
                'position' => 8,
                'config' => json_encode([
                    'common_procedures' => ['injertos óseos', 'membranas barrera', 'factores de crecimiento'],
                    'equipment_required' => ['centrífuga', 'equipos de esterilización', 'conservación'],
                    'specialist_level' => 'especialista en biomateriales'
                ]),
            ],
            [
                'name' => 'Odontología General',
                'slug' => 'odontologia-general',
                'description' => 'Procedimientos generales y preventivos en odontología',
                'icon' => 'fas fa-smile',
                'color' => '#6366f1',
                'is_active' => true,
                'position' => 9,
                'config' => json_encode([
                    'common_procedures' => ['restauraciones', 'profilaxis', 'diagnóstico'],
                    'equipment_required' => ['unidad dental básica', 'rayos x', 'materiales de restauración'],
                    'specialist_level' => 'odontólogo general'
                ]),
            ],
        ];

        foreach ($resourceSpecialties as $resourceSpecialty) {
            ResourceSpecialty::create($resourceSpecialty);
        }

        $this->command->info('Resource specialties seeded successfully!');
    }
}
