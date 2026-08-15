<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductMedia;
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
        DB::table('product_media')->delete();

        // Obtener categorías y marcas
        $categories = Category::all();
        $brands = Brand::all();

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
                    'description' => $descriptions[array_rand($descriptions)] . " Ideal para {$category->name}. Fabricado con materiales de alta calidad para garantizar durabilidad, precisión y seguridad en cada procedimiento.",
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

                $createdProduct = Product::create($product);

                // Crear registro de media para el producto
                $imagePath = $productImages[array_rand($productImages)];
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
