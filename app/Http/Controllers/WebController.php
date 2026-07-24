<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Página principal (Home)
     */
    public function home()
    {
        // Hero section
        $heroSection = \App\Models\Sections::find(\App\Models\Sections::HERO_HOME);

        // Flow how-to section
        $howToSection = \App\Models\Sections::find(\App\Models\Sections::FLOW_HOW_TO);

        // Featured products
        $featuredProducts = \App\Models\Product::where('is_active', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Product sections
        $productSections = \App\Models\Sections::where('status', 1)
            ->whereIn('id', [\App\Models\Sections::IMPLANTOLOGY_PRODUCTS, \App\Models\Sections::GBR_PRODUCTS, \App\Models\Sections::INSTRUMENTS_PRODUCTS])
            ->orderBy('id')
            ->get();

        // Mapear secciones a categorías
        $sectionCategories = [
            \App\Models\Sections::IMPLANTOLOGY_PRODUCTS => ['name' => 'Implantes', 'slug' => 'implantes'],
            \App\Models\Sections::GBR_PRODUCTS         => ['name' => 'Regeneración Guiada Bucal (GBR)', 'slug' => 'regeneracion-guiada-bucal-gbr'],
            \App\Models\Sections::INSTRUMENTS_PRODUCTS => ['name' => 'Instrumentos', 'slug' => 'tijeras'],
        ];

        // Testimonials section
        $testimonialsSection = \App\Models\Sections::find(\App\Models\Sections::TESTIMONIALS);

        // Testimonials data
        $testimonials = \App\Models\Testimonial::where('is_active', true)
            ->orderBy('position', 'asc')
            ->take(4)
            ->get();

        return view('web.home', compact(
            'heroSection', 'howToSection', 'featuredProducts',
            'productSections', 'sectionCategories', 'testimonialsSection', 'testimonials'
        ));
    }

    /**
     * Catálogo de productos
     */
    public function catalogo()
    {
        // Get current category for metadata
        $currentCategory = null;
        $categorySlug = request('category');

        if ($categorySlug) {
            $currentCategory = \App\Models\Category::where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();
        }

        // Get products with filters
        $query = \App\Models\Product::with(['category', 'brand'])
            ->where('is_active', true);

        // Apply category filter if present
        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }

        // Apply brand filter if present
        $brandSlug = request('brand');
        if ($brandSlug) {
            $brand = \App\Models\Brand::where('slug', $brandSlug)->where('is_active', true)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Apply tag filter if present
        $tag = request('tag');
        if ($tag) {
            // Handle different tag types
            if ($tag === 'new') {
                $query->where('is_new', true);
            } elseif ($tag === 'featured') {
                $query->where('is_featured', true);
            } elseif ($tag === 'on_sale') {
                $query->where('is_on_sale', true);
            } elseif ($tag === 'biomaterial') {
                $query->where('is_biomaterial', true);
            } elseif (str_contains($tag, ':')) {
                // Handle compound tags like "category:slug" or "brand:slug"
                [$tagType, $tagValue] = explode(':', $tag, 2);

                if ($tagType === 'material') {
                    $query->where('material', 'like', '%' . $tagValue . '%');
                }
            }
        }

        // Apply material filter if present
        $material = request('material');
        if ($material) {
            $query->where('material', 'like', '%' . $material . '%');
        }


        // Apply search filter if present
        $searchTerm = request('search');
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $products = $query->orderBy('name')->get();

        return view('web.catalogo', compact('products', 'currentCategory'));
    }

    /**
     * Detalle de producto
     */
    public function producto(string $slug)
    {
        $product = \App\Models\Product::with(['category', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Obtener productos relacionados
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('web.producto', compact('product', 'relatedProducts'));
    }

    /**
     * Carrito de compras
     */
    public function carrito()
    {
        return view('web.carrito');
    }

    /**
     * Solicitud comercial (checkout)
     */
    public function solicitud()
    {
        $customerTypes   = \App\Models\CustomerType::active()->ordered()->get();
        $deliveryMethods = \App\Models\DeliveryMethod::active()->ordered()->get();
        $paymentMethods  = \App\Models\PaymentMethod::active()->ordered()->get();
        $states          = \App\Models\State::ordered()->get();
        $cities          = \App\Models\City::all(); // all for JS filter

        return view('web.solicitud', compact('customerTypes', 'deliveryMethods', 'paymentMethods', 'states', 'cities'));
    }

    /**
     * Contacto
     */
    public function contactanos()
    {
        $settings = \App\Models\Settings::getSettings();
        $contactSection = \App\Models\Sections::find(\App\Models\Sections::CONTACT_HERO);

        return view('web.contactanos', compact('settings', 'contactSection'));
    }

    /**
     * Nuestra empresa
     */
    public function nuestraEmpresa()
    {
        $companyHeroSection = \App\Models\Sections::find(\App\Models\Sections::COMPANY_HERO);
        $aboutSection   = \App\Models\Sections::find(\App\Models\Sections::ABOUT_US);
        $missionSection = \App\Models\Sections::find(\App\Models\Sections::MISSION_VISION);
        $teamSection    = \App\Models\Sections::find(\App\Models\Sections::TEAM);
        $alliesSection  = \App\Models\Sections::find(\App\Models\Sections::ALLIES);
        $ctaSection     = \App\Models\Sections::find(\App\Models\Sections::CTA_COMPANY);

        return view('web.nuestra-empresa', compact('companyHeroSection', 'aboutSection', 'missionSection', 'teamSection', 'alliesSection', 'ctaSection'));
    }

    /**
     * Políticas
     */
    public function politicas()
    {
        $sections = \App\Models\Sections::where('status', 1)
            ->whereIn('id', [\App\Models\Sections::SHIPPING_POLICIES, \App\Models\Sections::TERMS_CONDITIONS, \App\Models\Sections::PRIVACY_POLICIES])
            ->orderBy('id')
            ->get();

        return view('web.politicas', compact('sections'));
    }

    /**
     * Recursos clínicos
     */
    public function recursosClinicos()
    {
        // Hero section
        $heroSection = \App\Models\Sections::find(\App\Models\Sections::CLINICAL_RESOURCES_HERO);

        // Estadísticas (multiplicadas por el factor de duplicación del grid)
        $statsMultiplier = 4;
        $totalResources = \App\Models\Resource::where('is_active', true)->count() * $statsMultiplier;
        $totalSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->count();
        $totalPDFs = \App\Models\Resource::where('is_active', true)->where('format', 'pdf')->count() * $statsMultiplier;
        $totalCases = \App\Models\Resource::where('is_active', true)->where('type', 'case')->count() * $statsMultiplier;

        // Library section
        $librarySection = \App\Models\Sections::find(\App\Models\Sections::CLINICAL_LIBRARY);

        // Filtros
        $resourceSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->orderBy('name')->get();
        $resourceTypes = \App\Models\ResourceType::where('is_active', true)->orderBy('name')->get();

        // Contadores para filtros
        $resourceTypeCounts = \App\Models\ResourceType::where('is_active', true)
            ->withCount(['resources' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        $resourceSpecialtyCounts = \App\Models\ResourceSpecialty::where('is_active', true)
            ->withCount(['resources' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        // Formatos
        $formats = \App\Models\Resource::where('is_active', true)
            ->select('format')
            ->selectRaw('count(*) as count')
            ->groupBy('format')
            ->orderBy('format')
            ->get();

        // Recursos con filtros
        $sortBy = request('sort', 'position');
        $rawResources = \App\Models\Resource::where('is_active', true)
            ->with(['resourceType','resourceSpecialty'])
            ->orderBy($sortBy === 'recent' ? 'created_at' : 'position', $sortBy === 'recent' ? 'desc' : 'asc')
            ->get();

        $multiplier = 4;
        $duplicated = $rawResources->flatMap(fn($r) => array_fill(0, $multiplier, $r));
        $perPage = 12;
        $currentPage = (int) request('page', 1);
        $resources = new \Illuminate\Pagination\LengthAwarePaginator(
            $duplicated->forPage($currentPage, $perPage)->values(),
            $duplicated->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('web.recursos-clinicos', compact(
            'heroSection', 'totalResources', 'totalSpecialties', 'totalPDFs', 'totalCases',
            'librarySection', 'resourceSpecialties', 'resourceTypes', 'resourceTypeCounts',
            'resourceSpecialtyCounts', 'formats', 'resources'
        ));
    }

    /**
     * Búsqueda AJAX de productos para autocompletado
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $limit = min($request->get('limit', 8), 12); // Máximo 12 resultados

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $products = \App\Models\Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('sku', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $products->map(function($product, $index) {
            // Usar las mismas imágenes que la página de producto (im1.png - im6.png)
            $imagePool = ['im1.png', 'im2.png', 'im3.png', 'im4.png', 'im5.png', 'im6.png'];
            $imageIndex = $index % count($imagePool);
            $imageUrl = asset('images/' . $imagePool[$imageIndex]);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'image' => $imageUrl,
                'category' => $product->category ? $product->category->name : 'Sin categoría',
                'category_slug' => $product->category ? $product->category->slug : null,
                'brand' => $product->brand ? $product->brand->name : 'Helin',
                'url' => route('producto', ['slug' => $product->slug]),
                'is_on_sale' => $product->is_on_sale,
                'is_new' => $product->is_new,
            ];
        });

        return response()->json($results);
    }
}
