<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Line;
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
        DB::table('product_media')->delete();

        // Obtener categorías y marcas
        $categories = Category::all();
        $brands = Brand::all();
        $lines = Line::all()->keyBy('slug');

        // Mapear categoría → línea (por slug de línea)
        $categoryToLine = [
            'implantologia' => 'implantologia',
            'regeneracion-guiada-bucal-gbr' => 'regeneracion-osea-guiada',
            'osteosintesis' => 'osteosintesis',
            'cuidado-bucal' => 'cuidado-bucal',
            'instrumentos' => 'instrumentos',
            'equipos-odontologicos' => 'equipos',
            'planificacion-digital' => 'planificacion-digital',
        ];

        // Sinónimos en español por categoría
        $spanishNames = [
            'Implantología' => ['Implante Dental', 'Pilar Protésico', 'Kit Quirúrgico', 'Aditamento Implantológico'],
            'Regeneración' => ['Injerto Óseo', 'Membrana de Regeneración', 'Biomaterial Regenerativo'],
            'Osteosíntesis' => ['Placa de Fijación', 'Tornillo de Osteosíntesis', 'Sistema de Fijación'],
            'Cuidado Bucal' => ['Sutura Quirúrgica', 'Producto de Cuidado', 'Material de Higiene'],
            'Instrumentos' => ['Instrumental Quirúrgico', 'Tijera Quirúrgica', 'Pinza Hemostática'],
            'Equipos' => ['Equipo Odontológico', 'Motor Quirúrgico', 'Unidad Dental'],
            'Planificación Digital' => ['Software de Planificación', 'Guía Quirúrgica Digital', 'Escáner Intraoral'],
        ];

        // Dimensiones por categoría
        $dimensionsByCategory = [
            'Implantología' => ['Ø3.3mm L8mm', 'Ø4.1mm L10mm', 'Ø4.8mm L12mm', 'Ø3.5mm L6mm'],
            'Regeneración' => ['25x25mm',  '30x40mm', '15x20mm', '10x10mm'],
            'Osteosíntesis' => ['L50mm 1.0mm', 'L60mm 1.3mm', 'L70mm 2.0mm', 'L80mm 2.4mm'],
            'Cuidado Bucal' => ['L75cm Ø0.3mm', 'L70cm Ø0.4mm', 'L45cm Ø0.5mm', 'L60cm Ø0.2mm'],
            'Instrumentos' => ['L140mm', 'L160mm', 'L180mm', 'L120mm'],
            'Equipos' => ['220V 50Hz', '110V 60Hz', '24V DC', '380V 50Hz'],
            'Planificación Digital' => ['Licencia 1 año', 'Licencia 3 años', 'Hardware+Software', 'Cloud 12 meses'],
        ];

        // Datos base para generar productos
        $productNames = [
            'Implantología' => ['Implante BLX', 'Implante SLA', 'Implante T3', 'Implante Roxolid', 'Implante Regular', 'Implante Wide', 'Implante Narrow', 'Implante Short', 'Implante Standard', 'Implante Plus', 'Implante Active', 'Implante Active BLX', 'Implante Active SLA', 'Implante Active T3', 'Implante Active Roxolid', 'Implante Bone Level', 'Implante Tissue Level', 'Implante CrossFit', 'Implante SynOcta', 'Implante ITI', 'Pilar Cónico', 'Pilar UCLA', 'Pilar Multi-Unit', 'Pilar Locator', 'Pilar Healing', 'Pilar Gingival Former', 'Kit Básico', 'Kit Avanzado', 'Kit Premium', 'Kit Completo'],
            'Regeneración' => ['Injerto Óseo', 'Membrana Colágena', 'Matriz Ósea', 'Sustituto Óseo', 'Biomaterial Porcino', 'Biomaterial Bovino', 'Biomaterial Sintético', 'Cemento Óseo', 'Pasta Ósea', 'Bloque Óseo', 'Lámina Cortical', 'Esponja Ósea', 'Gel Hemostático', 'Membrana Reabsorbible', 'Membrana No Reabsorbible', 'Parche Óseo', 'Membrana GBR', 'Injerto GBR', 'Kit GBR', 'Membrana PTFE'],
            'Osteosíntesis' => ['Placa 1.0mm', 'Placa 1.3mm', 'Placa 1.5mm', 'Placa 2.0mm', 'Placa 2.4mm', 'Placa Mini', 'Placa Micro', 'Placa Mandibular', 'Placa Maxilar', 'Placa Reconstrucción', 'Tornillo 2.0mm', 'Tornillo 2.4mm', 'Tornillo 2.7mm', 'Tornillo 3.0mm', 'Tornillo Mini', 'Tornillo Corto', 'Tornillo Largo', 'Tornillo Bicortical', 'Tornillo Monocortical', 'Cajetín Básico'],
            'Cuidado Bucal' => ['Sutura 4-0', 'Sutura 5-0', 'Sutura 3-0', 'Sutura 6-0', 'Sutura Seda', 'Sutura Vicryl', 'Sutura PDS', 'Sutura Monocryl', 'Gel Antibacterial', 'Spray Cicatrizante', 'Enjuague Post-Op', 'Compresa Fría', 'Gasas Estériles', 'Pomada Antibiótica', 'Crema Cicatrizante', 'Cepillo Dental', 'Pasta Dental', 'Hilo Dental', 'Enjuague Bucal', 'Irrigador Bucal'],
            'Instrumentos' => ['Kit Básico', 'Kit Avanzado', 'Kit Premium', 'Kit Estándar', 'Tijera Curva', 'Tijera Metzenbaum', 'Tijera Mayo', 'Pinza Hemostática', 'Pinza Allis', 'Pinza Kocher', 'Pinza Adson', 'Separador Farabeuf', 'Separador Senn', 'Separador Gelpi', 'Separador Weitlaner', 'Cincel Recto', 'Cincel Curvo', 'Periostótomos Curvo', 'Periostótomos Recto', 'Periostótomos Molt'],
            'Equipos' => ['Motor Eléctrico', 'Motor Neumático', 'Pieza de Mano', 'Contraángulo', 'Fresa', 'Lámpara Curing', 'Sistema de Aspiración', 'Sistema de Riego', 'Unidad Dental', 'Sillón Dental', 'Lámpara Dental', 'Mesa Auxiliar', 'Carro Instrumental', 'Compresor', 'Autoclave', 'Esterilizador', 'Rayos X', 'Camera Intraoral', 'Scanner Intraoral', 'Lámpara Dental LED'],
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
            'Implantología'         => 50,
            'Regeneración'          => 40,
            'Osteosíntesis'         => 30,
            'Cuidado Bucal'         => 30,
            'Instrumentos'          => 35,
            'Equipos'               => 20,
            'Planificación Digital' => 20,
        ];

        // Imágenes disponibles en storage/products/
        $productImages = ['products/im1.png', 'products/im2.png', 'products/im3.png', 'products/im4.png', 'products/im5.png', 'products/im6.png'];

        // Seed fijo para que los slugs/SKUs sean determinísticos entre ejecuciones
        mt_srand(20260817);

        $skuCounter = 1;
        foreach ($categories as $category) {
            $categoryName = $category->name;
            $baseNames = $productNames[$categoryName] ?? $this->generateGenericNames(20);
            $total = $categoryVolume[$categoryName] ?? 20;

            for ($i = 1; $i <= $total; $i++) {
                // Marca determinística por categoría + índice
                $brand = $brands[$i % $brands->count()];
                $baseName = $baseNames[($i - 1) % count($baseNames)];
                $price = mt_rand(25, 500) + (mt_rand(0, 99) / 100);
                $isOnSale = mt_rand(0, 10) > 7; // 30% de productos en oferta
                $isNew = mt_rand(0, 10) > 8; // 20% de productos nuevos
                $isFeatured = mt_rand(0, 10) > 8; // 20% de productos destacados

                // Generar SKU único (sin timestamp para que sea determinístico)
                $sku = strtoupper(substr($brand->slug, 0, 3)) . '-' . strtoupper(substr($category->slug, 0, 3)) . '-' . str_pad($skuCounter++, 4, '0', STR_PAD_LEFT) . '-001';

                // Referencia de proveedor
                $supplierReference = 'REF-' . strtoupper($brand->slug) . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

                // Sinónimo en español (determinístico)
                $spanishOptions = $spanishNames[$categoryName] ?? ['Producto Odontológico'];
                $spanishName = $spanishOptions[$i % count($spanishOptions)] . ' ' . $brand->name;

                // Dimensiones (determinísticas)
                $dimOptions = $dimensionsByCategory[$categoryName] ?? ['Estándar'];
                $dimensions = $dimOptions[$i % count($dimOptions)];

                // Línea asociada
                $lineSlug = $categoryToLine[$category->slug] ?? null;
                $lineId = $lineSlug && isset($lines[$lineSlug]) ? $lines[$lineSlug]->id : null;

                $product = [
                    'name' => "{$baseName} {$brand->name} - {$categoryName}",
                    'spanish_name' => $spanishName,
                    'slug' => Str::slug("{$baseName}-{$brand->name}-{$categoryName}-{$i}"),
                    'sku' => $sku,
                    'supplier_reference' => $supplierReference,
                    'brand_id' => $brand->id,
                    'description' => $descriptions[$i % count($descriptions)] . " Ideal para {$category->name}. Fabricado con materiales de alta calidad para garantizar durabilidad, precisión y seguridad en cada procedimiento.",
                    'clinical_specs' => json_encode([
                        'material' => 'Titanio Grado 5',
                        'esterilizacion' => 'Autoclave 134°C',
                        'certificacion' => 'ISO 13485',
                        'origen' => 'Importado',
                    ]),
                    'price' => $price,
                    'currency' => 'USD',
                    'stock' => mt_rand(10, 100),
                    'unit' => $units[$i % count($units)],
                    'meta_title' => "{$baseName} {$brand->name} - {$category->name} | Helin",
                    'meta_description' => "Compra {$baseName} {$brand->name} para {$category->name}. Alta calidad y garantía en Helin.",
                    'meta_keywords' => "{$category->slug}, {$brand->slug}, {$baseName}, odontología, cirugía dental",
                    'material' => ['Titanio Grado 5', 'Acero Inoxidable', 'Colágeno', 'Cerámica', 'PEEK'][$i % 5],
                    'dimensions' => $dimensions,
                    'category_id' => $category->id,
                    'line_id' => $lineId,
                    'is_active' => true,
                    'is_featured' => $isFeatured,
                    'is_new' => $isNew,
                    'is_on_sale' => $isOnSale,
                    'sale_price' => $isOnSale ? $price * 0.85 : null,
                    'sale_start_date' => $isOnSale ? now()->subDays(mt_rand(1, 30)) : null,
                    'sale_end_date' => $isOnSale ? now()->addDays(mt_rand(30, 90)) : null,
                    'view_count' => mt_rand(0, 500),
                    'search_count' => mt_rand(0, 200),
                    'rating' => mt_rand(35, 50) / 10,
                    'review_count' => mt_rand(0, 50),
                    'published_at' => now()->subDays(mt_rand(1, 365)),
                    'created_at' => now()->subDays(mt_rand(1, 365)),
                    'updated_at' => now(),
                ];

                $createdProduct = Product::create($product);

                // Crear registro de media para el producto
                $imagePath = $productImages[$i % count($productImages)];
                ProductMedia::create([
                    'product_id' => $createdProduct->id,
                    'file_path' => $imagePath,
                    'file_name' => basename($imagePath),
                    'mime_type' => 'image/png',
                    'type' => 'image',
                    'alt_text' => $product['name'],
                    'title' => $product['name'],
                    'is_main' => true,
                    'position' => 0,
                ]);
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
