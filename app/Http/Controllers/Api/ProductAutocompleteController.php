<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAutocompleteController extends Controller
{
    public function search(Request $request)
    {
        $term = trim($request->get('q', ''));

        if (strlen($term) < 3) {
            return response()->json(['success' => true, 'products' => []]);
        }

        $normalizedTerm = $this->normalize($term);

        $query = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where(function ($q) use ($term, $normalizedTerm) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('name', 'like', '%' . $normalizedTerm . '%')
                  ->orWhere('sku', 'like', '%' . $term . '%')
                  ->orWhere('sku', 'like', '%' . $normalizedTerm . '%')
                  ->orWhere('description', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $normalizedTerm . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $term . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $normalizedTerm . '%')
                  ->orWhereHas('category', function ($cq) use ($term, $normalizedTerm) {
                      $cq->where('name', 'like', '%' . $term . '%')
                         ->orWhere('name', 'like', '%' . $normalizedTerm . '%');
                  })
                  ->orWhereHas('brand', function ($bq) use ($term, $normalizedTerm) {
                      $bq->where('name', 'like', '%' . $term . '%')
                         ->orWhere('name', 'like', '%' . $normalizedTerm . '%');
                  });
            })
            ->limit(8)
            ->get();

        $products = $query->unique('id')->values()->map(function ($product) {
            return [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'price' => $product->price,
                'formatted_price' => '$' . number_format($product->price, 2),
                'category' => $product->category?->name ?? 'Helin',
                'image' => $product->main_image_url,
                'url' => route('producto', ['slug' => $product->slug]),
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count(),
        ]);
    }

    private function normalize(string $text): string
    {
        return Str::lower(Str::ascii($text));
    }
}
