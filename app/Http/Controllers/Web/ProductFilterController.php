<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductFilterController extends Controller
{
    public function filter(Request $request)
    {
        $search     = $request->get('search', '');
        $categories = $request->get('category', []);
        $brands     = $request->get('brand', []);
        $tags       = $request->get('tag', []);
        $sortBy     = $request->get('sort', 'recent');

        $query = Product::with(['category', 'brand'])->where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        if (!empty($categories)) {
            $query->whereHas('category', fn($q) => $q->whereIn('slug', (array) $categories));
        }

        if (!empty($brands)) {
            $query->whereHas('brand', fn($q) => $q->whereIn('slug', (array) $brands));
        }

        if (!empty($tags)) {
            if (in_array('featured', $tags)) $query->where('is_featured', true);
            if (in_array('on_sale', $tags))  $query->where('is_on_sale', true);
            if (in_array('new', $tags))      $query->where('is_new', true);
        }

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(24);

        $html = view('web.partials.product-results', compact('products'))->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
            'count'   => $products->total(),
        ]);
    }
}
