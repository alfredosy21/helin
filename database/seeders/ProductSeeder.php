<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->delete();

        // Obtener categorías y marcas
        $categories = Category::all();
        $brands = Brand::all();

        // Datos base para generar productos
        $productNames = [
            'Implante' => ['Implante BLX', 'Implante SLA', 'Implante T3', 'Implante Roxolid', 'Implante Regular', 'Implante Wide', 'Implante Narrow', 'Implante Short', 'Implante Standard', 'Implante Plus', 'Implante Active', 'Implante Active BLX', 'Implante Active SLA', 'Implante Active T3', 'Implante Active Roxolid', 'Implante Bone Level', 'Implante Tissue Level', 'Implante CrossFit', 'Implante SynOcta', 'Implante ITI'],
            'Aditamentos' => ['Pilar Cónico', 'Pilar Cónico Angulado', 'Pilar Cónico Estético', 'Pilar UCLA', 'Pilar Multi-Unit', 'Pilar Ball Abutment', 'Pilar Locator', 'Pilar Gold Adaptor', 'Pilar Titanium Adaptor', 'Pilar Zirconia Adaptor', 'Pilar Temporary', 'Pilar Healing', 'Pilar Gingival Former', 'Pilar Custom Abutment', 'Pilar Screw Retained', 'Pilar Cement Retained', 'Pilar Overdenture', 'Pilar Bar', 'Pilar Telescopic', 'Pilar Attachment'],
            'Kits Quirúrgicos' => ['Kit Básico', 'Kit Avanzado', 'Kit Premium', 'Kit Estándar', 'Kit Completo', 'Kit Especializado', 'Kit Pro', 'Kit Expert', 'Kit Master', 'Kit Starter', 'Kit Essential', 'Kit Professional', 'Kit Ultimate', 'Kit Deluxe', 'Kit Elite', 'Kit Prime', 'Kit Select', 'Kit Core', 'Kit Basic Plus', 'Kit Advanced Plus'],
            'Biomateriales' => ['Injerto Óseo', 'Membrana Colágena', 'Matriz Ósea', 'Sustituto Óseo', 'Biomaterial Porcino', 'Biomaterial Bovino', 'Biomaterial Sintético', 'Cemento Óseo', 'Pasta Ósea', 'Bloque Óseo', 'Lámina Cortical', 'Esponja Ósea', 'Gel Hemostático', 'Plasma Rico en Plaquetas', 'Factores de Crecimiento', 'Matriz Derivada', 'Membrana Reabsorbible', 'Membrana No Reabsorbible', 'Parche Óseo', 'Cemento de Fosfato'],
            'Regeneración Guiada Bucal (GBR)' => ['Membrana GBR', 'Injerto GBR', 'Kit GBR', 'Membrana Colágena GBR', 'Injerto Porcino GBR', 'Injerto Bovino GBR', 'Membrana PTFE', 'Membrana Tiempo', 'Membrana Crosslink', 'Parche GBR', 'Bloque GBR', 'Lámina GBR', 'Esponja GBR', 'Gel GBR', 'Matrix GBR', 'Sistema GBR', 'Kit Completo GBR', 'Kit Básico GBR', 'Kit Avanzado GBR', 'Kit Premium GBR'],
            'Suturas' => ['Sutura 4-0', 'Sutura 5-0', 'Sutura 3-0', 'Sutura 6-0', 'Sutura 2-0', 'Sutura 7-0', 'Sutura 8-0', 'Sutura Nylon', 'Sutura Seda', 'Sutura Vicryl', 'Sutura PDS', 'Sutura Monocryl', 'Sutura Chromic', 'Sutura Gut', 'Sutura Poliglecaprone', 'Sutura Poliglactin', 'Sutura Polipropileno', 'Sutura Acero', 'Sutura Seda Negra', 'Sutura Seda Blanca'],
            'Placas' => ['Placa 1.0mm', 'Placa 1.3mm', 'Placa 1.5mm', 'Placa 2.0mm', 'Placa 2.4mm', 'Placa Mini', 'Placa Micro', 'Placa Mandibular', 'Placa Maxilar', 'Placa Reconstrucción', 'Placa Trauma', 'Placa Ortopédica', 'Placa Adaptativa', 'Placa Universal', 'Placa Especializada', 'Placa 3D', 'Placa Anatómica', 'Placa Preformada', 'Placa Personalizada', 'Placa Híbrida'],
            'Tornillos' => ['Tornillo 2.0mm', 'Tornillo 2.4mm', 'Tornillo 2.7mm', 'Tornillo 3.0mm', 'Tornillo 3.5mm', 'Tornillo 4.0mm', 'Tornillo Mini', 'Tornillo Micro', 'Tornillo Corto', 'Tornillo Largo', 'Tornillo Auto-perforante', 'Tornillo Cortante', 'Tornillo Bicortical', 'Tornillo Monocortical', 'Tornillo Cónico', 'Tornillo Paralelo', 'Tornillo Especializado', 'Tornillo Universal', 'Tornillo Adaptativo', 'Tornillo Híbrido'],
            'Cajetín' => ['Cajetín Básico', 'Cajetín Estándar', 'Cajetín Premium', 'Cajetín Compacto', 'Cajetín Amplio', 'Cajetín Modular', 'Cajetín Personalizado', 'Cajetín Universal', 'Cajetín Especializado', 'Cajetín Pro', 'Cajetín Expert', 'Cajetín Master', 'Cajetín Elite', 'Cajetín Prime', 'Cajetín Select', 'Cajetín Core', 'Cajetín Basic', 'Cajetín Advanced', 'Cajetín Deluxe', 'Cajetín Ultimate'],
            'Cuidados Especiales (Quirúrgicos)' => ['Gel Antibacterial', 'Spray Cicatrizante', 'Enjuague Post-Op', 'Compresa Fría', 'Compresa Caliente', 'Manta Térmica', 'Protector Bucal', 'Venda Elástica', 'Gasas Estériles', 'Esponjas Hemostáticas', 'Sellador de Heridas', 'Pomada Antibiótica', 'Crema Cicatrizante', 'Gel Anestésico', 'Spray Anestésico', 'Compresas de Gasas', 'Aftercare Kit', 'Post-Op Kit', 'Recovery Kit', 'Healing Kit'],
            'Cuidados Diarios (Paciente)' => ['Cepillo Dental', 'Pasta Dental', 'Hilo Dental', 'Enjuague Bucal', 'Cepillo Interdental', 'Raspador Lingual', 'Limpiador Lingual', 'Irrigador Bucal', 'Spray Bucal', 'Chicles Dentales', 'Pastillas Masticables', 'Esterilla Dental', 'Discos Limpiadores', 'Cepillo Sónico', 'Cepillo Eléctrico', 'Hilo Ceroso', 'Hilo Encerado', 'Cinta Dental', 'Enjuague Sin Alcohol'],
            'Tijeras' => ['Tijera Curva', 'Tijera Recta', 'Tijera Metzenbaum', 'Tijera Mayo', 'Tijera Iris', 'Tijera Stitch', 'Tijera Lister', 'Tijera Gum', 'Tijera Suture', 'Tijera Dissecting', 'Tijera Operating', 'Tijera Bandage', 'Tijera Castroviejo', 'Tijera Stevens', 'Tijera Tenotomy', 'Tijera Potts-Smith', 'Tijera Lahey', 'Tijera Vannas', 'Tijera Noyes'],
            'Pinzas' => ['Pinza Hemostática', 'Pinza Allis', 'Pinza Babcock', 'Pinza Kocher', 'Pinza Mosquito', 'Pinza Kelly', 'Pinza Adson', 'Pinza Brown-Adson', 'Pinza DeBakey', 'Pinza Russian', 'Pinza Foerster', 'Pinza Hartmann', 'Pinza Lane', 'Pinza Mixter', 'Pinza Ochsner', 'Pinza Rochester', 'Pinza Carmalt', 'Pinza Doyen', 'Pinza Payr', 'Pinza Satinsky'],
            'Separadores' => ['Separador Farabeuf', 'Separador Senn', 'Separador Gelpi', 'Separador Weitlaner', 'Separador Balfour', 'Separador Finochietto', 'Separador Gelpi Pequeño', 'Separador Hohmann', 'Separador Hoke', 'Separador Jansen', 'Separador Langenbeck', 'Separador Morris', 'Separador Parker', 'Separator Ragnell', 'Separador Richardson', 'Separador Travers', 'Separador Volkmann', 'Separador Wylie', 'Separador Young'],
            'Cinceles' => ['Cincel Recto', 'Cincel Curvo', 'Cincel Monobein', 'Cincel Bein', 'Cincel Wedge', 'Cincel Fissure', 'Cincel Osteotome', 'Cincel Chisel', 'Cincel Mallet', 'Cincel Rongeur', 'Cincel Bone', 'Cincel Cortical', 'Cincel Cancellous', 'Cincel Curvo Fino', 'Cincel Recto Fino', 'Cincel Especializado', 'Cincel Universal', 'Cincel Adaptativo', 'Cincel Híbrido', 'Cincel Premium'],
            'Periostótomos' => ['Periostótomos Curvo', 'Periostótomos Recto', 'Periostótomos Molt', 'Periostótomos No. 9', 'Periostótomos No. 4', 'Periostótomos Freer', 'Periostótomos Howarth', 'Periostótomos Cottle', 'Periostótomos Joseph', 'Periostótomos Converse', 'Periostótomos Blair', 'Periostótomos Dingman', 'Periostótomos Aufricht', 'Periostótomos Peer', 'Periostótomos Goldman-Fox', 'Periostótomos Smith', 'Periostótomos Bovie', 'Periostótomos Coleman', 'Periostótomos Bard-Parker'],
            'Equipos odontológicos' => ['Motor Eléctrico', 'Motor Neumático', 'Pieza de Mano', 'Contraángulo', 'Fresa', 'Lámpara Curing', 'Sistema de Aspiración', 'Sistema de Riego', 'Unidad Dental', 'Sillón Dental', 'Lámpara Dental', 'Escritorio Dental', 'Mesa Auxiliar', 'Carro Instrumental', 'Compresor', 'Autoclave', 'Esterilizador', 'Rayos X', 'Camera Intraoral', 'Scanner Intraoral'],
            'Planificación Digital' => ['Software Planificación', 'Guía Quirúrgica', 'Splint Digital', 'Modelo 3D', 'Scanner Intraoral', 'Impresora 3D', 'Máquina CNC', 'Sistema CAD/CAM', 'Software CAD', 'Software CAM', 'Planificador Virtual', 'Simulador Quirúrgico', 'Navegador Quirúrgico', 'Sistema de Imagenología', 'Cone Beam', 'Tomografía', 'Software de Diseño', 'Sistema de Prototipado', 'Software de Análisis', 'Plataforma Cloud'],
        ];

        $descriptions = [
            'Producto de alta calidad diseñado para procedimientos odontológicos especializados.',
            'Solución profesional para cirugía dental con precisión y seguridad.',
            'Material biocompatible optimizado para regeneración tisular y ósea.',
            'Instrumental quirúrgico de precisión para procedimientos especializados.',
            'Sistema completo para rehabilitación oral con máxima eficiencia.',
            'Producto innovador con tecnología avanzada para odontología moderna.',
            'Material certificado para procedimientos quirúrgicos seguros.',
            'Solución integral para cirugía maxilofacial y reconstrucción.',
            'Producto desarrollado con estándares internacionales de calidad.',
            'Herramienta esencial para el profesional odontológico moderno.',
        ];

        $units = ['Unidad', 'Caja', 'Paquete', 'Kit', 'Set', 'Par', 'Blister', 'Frasco', 'Tubo', 'Sobre'];

        // Categorías con más volumen para que los contadores y filtros se vean representativos
        $categoryVolume = [
            'Implantes'                           => 50,
            'Aditamentos'                         => 45,
            'Biomateriales'                       => 40,
            'Kits Quirúrgicos'                    => 35,
            'Regeneración Guiada Bucal (GBR)'     => 30,
            'Suturas'                             => 30,
            'Placas'                              => 25,
            'Tornillos'                           => 25,
            'Equipos odontológicos'               => 20,
            'Planificación Digital'               => 20,
        ];

        $skuCounter = 1;
        foreach ($categories as $category) {
            $categoryName = $category->name;
            $baseNames = $productNames[$categoryName] ?? $this->generateGenericNames(20);
            $total = $categoryVolume[$categoryName] ?? 20;

            for ($i = 1; $i <= $total; $i++) {
                $brand = $brands->random();
                $baseName = $baseNames[($i - 1) % count($baseNames)];
                $price = rand(25, 500) + (rand(0, 99) / 100);
                $isOnSale = rand(0, 10) > 7; // 30% de productos en oferta
                $isNew = rand(0, 10) > 8; // 20% de productos nuevos
                $isFeatured = rand(0, 10) > 8; // 20% de productos destacados

                // Generar SKU único con timestamp para evitar duplicados
                $sku = strtoupper(substr($brand->slug, 0, 3)) . '-' . strtoupper(substr($category->slug, 0, 3)) . '-' . str_pad($skuCounter++, 4, '0', STR_PAD_LEFT) . '-' . time() % 1000;

                $product = [
                    'name' => "{$baseName} {$brand->name} - {$categoryName}",
                    'slug' => Str::slug("{$baseName}-{$brand->name}-{$categoryName}-{$i}"),
                    'sku' => $sku,
                    'brand_id' => $brand->id,
                    'description' => $descriptions[array_rand($descriptions)] . " Ideal para {$category->name}.",
                    'clinical_specs' => json_encode([
                        'material' => 'Titanio Grado 5',
                        'esterilizacion' => 'Autoclave 134°C',
                        'certificacion' => 'ISO 13485',
                        'origen' => 'Importado',
                    ]),
                    'price' => $price,
                    'currency' => 'USD',
                    'stock' => rand(10, 100),
                    'unit' => $units[array_rand($units)],
                    'meta_title' => "{$baseName} {$brand->name} - {$category->name} | Helin",
                    'meta_description' => "Compra {$baseName} {$brand->name} para {$category->name}. Alta calidad y garantía en Helin.",
                    'meta_keywords' => "{$category->slug}, {$brand->slug}, {$baseName}, odontología, cirugía dental",
                    'category_id' => $category->id,
                    'is_active' => true,
                    'is_featured' => $isFeatured,
                    'is_new' => $isNew,
                    'is_on_sale' => $isOnSale,
                    'sale_price' => $isOnSale ? $price * 0.85 : null,
                    'sale_start_date' => $isOnSale ? now()->subDays(rand(1, 30)) : null,
                    'sale_end_date' => $isOnSale ? now()->addDays(rand(30, 90)) : null,
                    'view_count' => rand(0, 500),
                    'search_count' => rand(0, 200),
                    'rating' => rand(35, 50) / 10,
                    'review_count' => rand(0, 50),
                    'published_at' => now()->subDays(rand(1, 365)),
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now(),
                ];

                Product::create($product);
            }
        }
    }

    /**
     * Generar nombres genéricos si no hay nombres específicos para la categoría
     */
    private function generateGenericNames(int $count): array
    {
        $names = [];
        for ($i = 1; $i <= $count; $i++) {
            $names[] = "Producto Profesional {$i}";
        }
        return $names;
    }
}
