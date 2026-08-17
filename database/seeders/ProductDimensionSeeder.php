<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Product Dimension Seeder
 *
 * Asocia dimensiones (Ø3.3 mm, Ø4.1 mm, Ø4.8 mm) a productos de Implantología
 * con precios y SKUs específicos por dimensión. Al seleccionar una dimensión
 * en el detalle del producto, cambian el precio y el SKU.
 */
class ProductDimensionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar asociaciones existentes
        DB::table('attribute_value_product')->truncate();

        $dimensionValues = AttributeValue::where('attribute_id', 1)
            ->orderBy('value')
            ->get()
            ->keyBy('value');

        if ($dimensionValues->isEmpty()) {
            $this->command->warn('No se encontraron AttributeValues para el atributo Dimensión (id=1). Ejecuta AttributeValueSeeder primero.');
            return;
        }

        // Asociar dimensiones a productos de Implantología con precios escalonados
        // Ø3.3 mm = más económico, Ø4.1 mm = precio medio, Ø4.8 mm = más caro
        $priceMultiplier = [
            '3.3' => 0.85,
            '4.1' => 1.00,
            '4.8' => 1.25,
        ];

        $implantologiaProducts = Product::whereHas('category', function ($q) {
            $q->where('slug', 'implantologia');
        })->get();

        $attached = 0;
        foreach ($implantologiaProducts as $product) {
            $basePrice = (float) $product->price;
            $baseSku = $product->sku ?: 'SKU-' . $product->id;
            $isOnSale = $product->is_on_sale;
            $baseSalePrice = $isOnSale && $product->sale_price ? (float) $product->sale_price : null;

            $syncData = [];
            foreach ($dimensionValues as $value => $av) {
                $multiplier = $priceMultiplier[$value] ?? 1.0;
                $dimPrice = round($basePrice * $multiplier, 2);
                $dimSalePrice = $baseSalePrice !== null ? round($baseSalePrice * $multiplier, 2) : null;

                // SKU específico: base + sufijo numérico sin puntos
                $numPart = preg_replace('/[^0-9.]/', '', (string) $value);
                $skuSuffix = str_replace('.', '', $numPart);
                $dimSku = $baseSku . '-' . $skuSuffix;

                $syncData[$av->id] = [
                    'price' => $dimPrice,
                    'sale_price' => $dimSalePrice,
                    'sku' => $dimSku,
                    'numeric_value' => (float) $value,
                    'text_value' => $av->label,
                    'notes' => null,
                ];
                $attached++;
            }

            $product->attributeValues()->sync($syncData, false);
        }

        $this->command->info("ProductDimensionSeeder: {$attached} variantes de dimensión asociadas a {$implantologiaProducts->count()} productos de Implantología.");
    }
}
