<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;

class StateCitySeeder extends Seeder
{
    public function run(): void
    {
        $statesData = [
            'DC' => 'Distrito Capital',
            'MI' => 'Miranda',
            'CA' => 'Carabobo',
            'AR' => 'Aragua',
            'LA' => 'Lara',
            'AN' => 'Anzoátegui',
            'AP' => 'Apure',
            'BA' => 'Barinas',
            'BO' => 'Bolívar',
            'DF' => 'Delta Amacuro',
            'FA' => 'Falcón',
            'GU' => 'Guárico',
            'ME' => 'Mérida',
            'MO' => 'Monagas',
            'NE' => 'Nueva Esparta',
            'PO' => 'Portuguesa',
            'SU' => 'Sucre',
            'TA' => 'Táchira',
            'TR' => 'Trujillo',
            'VA' => 'Vargas',
            'YA' => 'Yaracuy',
            'ZU' => 'Zulia',
        ];

        foreach ($statesData as $code => $name) {
            $state = State::updateOrCreate(
                ['code' => $code],
                ['name' => $name]
            );

            $this->seedCitiesForState($state, $code);
        }

        $this->command->info('States and cities seeded successfully!');
    }

    private function seedCitiesForState(State $state, string $stateCode): void
    {
        $citiesByState = [
            'DC' => [
                'Caracas',
            ],
            'MI' => [
                'Los Teques', 'San Antonio de Los Altos', 'Petare', 'Baruta', 'El Hatillo', 'Charallave', 'Cúa', 'Santa Teresa del Tuy', 'Ocumare del Tuy', 'Guarenas', 'Guatire', 'Higuerote', 'Paz Castillo', 'San Diego', 'Valle de la Pascua', 'San Francisco de Yare', 'San José de los Altos',
            ],
            'CA' => [
                'Valencia', 'Guacara', 'Los Guayos', 'Naguanagua', 'San Diego', 'Puerto Cabello', 'Morón', 'Bejuma', 'Güigüe', 'Tocuyito', 'Mariara', 'Montalbán', 'Canoabo', 'Patanemo',
            ],
            'AR' => [
                'Maracay', 'La Victoria', 'Cagua', 'Turmero', 'Villa de Cura', 'San Mateo', 'Santa Cruz de Aragua', 'Barbacoas', 'Palo Negro', 'El Limón', 'Ocumare de la Costa', 'Las Tejerías', 'Colonia Tovar', 'San Casimiro', 'Tocorón', 'Magdaleno',
            ],
            'LA' => [
                'Barquisimeto', 'Cabudare', 'Carora', 'Quíbor', 'El Tocuyo', 'Siquisique', 'Sanare', 'Yaritagua', 'Duaca', 'Sarare', 'Tocuyo', 'Los Rastrojos',
            ],
            'AN' => [
                'Barcelona', 'Puerto La Cruz', 'Lechería', 'El Tigre', 'Cantaura', 'Anaco', 'Puerto Píritu', 'Clarines', 'Aragua de Maturín', 'Onoto', 'Santa Ana', 'San Miguel del Punto', 'Maturín',
            ],
            'AP' => [
                'San Fernando de Apure', 'Achaguas', 'Biruaca', 'Mucuchíes', 'El Samán', 'Guasdualito', 'San Juan de Payara', 'Elorza', 'Bruzual',
            ],
            'BA' => [
                'Barinas', 'Santo Domingo', 'Ciudad Bolivia', 'Barinitas', 'Calabozo', 'Santa Bárbara', 'Sabaneta', 'Obispos', 'Pedraza', 'Los Cortijos', 'San Silvestre',
            ],
            'BO' => [
                'Ciudad Bolívar', 'Ciudad Guayana', 'Upata', 'Caicara del Orinoco', 'Guasipati', 'El Dorado', 'Santa Elena de Uairén', 'Piar', 'Gran Sabana', 'La Paragua', 'Tumeremo',
            ],
            'DF' => [
                'Tucupita', 'Pedernales', 'Múcuas', 'San José de Tucupita', 'San Francisco de Tucupita',
            ],
            'FA' => [
                'Coro', 'Punto Fijo', 'Santa Ana de Coro', 'Cabrutas', 'Churuguara', 'Maca', 'Dabajuro', 'Yaracal', 'Cumarebo', 'Capadare',
            ],
            'GU' => [
                'San Juan de los Morros', 'Calabozo', 'Altagracia de Orituco', 'Zaraza', 'Valle de la Pascua', 'El Sombrero', 'Cabruta', 'Las Mercedes', 'Sosa', 'Parapara',
            ],
            'ME' => [
                'Mérida', 'Ejido', 'Timotes', 'Santo Domingo', 'Tovar', 'Mucuchíes', 'Tabay', 'El Vigía', 'Santa Cruz de Mora', 'Lagunillas', 'Pueblo Llano', 'Acequias', 'Jají',
            ],
            'MO' => [
                'Maturín', 'Caripito', 'Maturín', 'Temblador', 'Punta de Mata', 'Caripe', 'Aragua de Maturín', 'Uracoa', 'Tucupita', 'Santa Bárbara del Tuy',
            ],
            'NE' => [
                'La Asunción', 'Juan Griego', 'Porlamar', 'San Antonio del Golfo', 'Pampatar', 'Juangriego', 'Santa Ana', 'San Francisco de Macanao', 'Boca del Río',
            ],
            'PO' => [
                'Guanare', 'Acarigua', 'Portuguesa', 'Ospino', 'Guanarito', 'Píritu', 'Biscucuy', 'Aguas Blancas', 'La Aparición', 'Santa Rosalía',
            ],
            'SU' => [
                'Cumaná', 'Cumanacoa', 'Carúpano', 'Río Caribe', 'Cumanacoa', 'Maturín', 'Yaguaraparo', 'Irapa', 'Mariguitar', 'San Antonio del Golfo', 'Guiria',
            ],
            'TA' => [
                'San Cristóbal', 'San Antonio del Táchira', 'Rubio', 'Táriba', 'La Grita', 'Abejales', 'Capacho', 'Colon', 'Michelena', 'Uribante', 'Pregonero',
            ],
            'TR' => [
                'Trujillo', 'Valera', 'Carache', 'Boconó', 'Sabana de Mendoza', 'Pampanito', 'Motatán', 'Cuicas', 'La Quebrada', 'El Dividive',
            ],
            'VA' => [
                'La Guaira', 'Maiquetía', 'Macuto', 'Catia La Mar', 'Naiguatá', 'Carayaca', 'Los Caracas', 'Caraballeda',
            ],
            'YA' => [
                'San Felipe', 'Yaritagua', 'Nirgua', 'Chivacoa', 'Cocorote', 'Boraure', 'Marín', 'Aroa', 'Guama', 'Urachiche',
            ],
            'ZU' => [
                'Maracaibo', 'San Francisco', 'Cabimas', 'Ciudad Ojeda', 'Punta de Piedras', 'Machiques', 'Lagunillas', 'Santa Rita', 'Mene Grande', 'Bobures', 'Bachaquero', 'El Vigía', 'La Concepción',
            ],
        ];

        foreach ($citiesByState[$stateCode] ?? [] as $cityName) {
            City::updateOrCreate(
                ['state_id' => $state->id, 'name' => $cityName]
            );
        }
    }
}
