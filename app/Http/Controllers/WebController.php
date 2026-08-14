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
            ->with('images')
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
        $query = \App\Models\Product::with(['category', 'brand', 'images'])
            ->where('is_active', true);

        // Apply category filter if present
        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }

        // Apply brand filter if present
        $brandSlugs = (array) request('brand');
        if (!empty($brandSlugs)) {
            $brandIds = \App\Models\Brand::whereIn('slug', $brandSlugs)->where('is_active', true)->pluck('id');
            if ($brandIds->isNotEmpty()) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        // Apply tag filter if present
        $tags = (array) request('tag');
        if (!empty($tags)) {
            foreach ($tags as $tag) {
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
        }

        // Apply material filter if present
        $materials = (array) request('material');
        if (!empty($materials)) {
            $query->where(function ($q) use ($materials) {
                foreach ($materials as $m) {
                    $q->orWhere('material', 'like', '%' . $m . '%');
                }
            });
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
        $product = \App\Models\Product::with(['category', 'brand', 'attributeValues', 'images', 'documents'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Obtener productos relacionados
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('images')
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
        $settings        = \App\Models\Settings::getSettings();

        $pickups = [];
        $offices = ['caracas' => 1, 'valencia' => 2, 'barquisimeto' => 3, 'maracaibo' => 4];
        $settings = is_object($settings) ? $settings : null;

        $officeByPhone = [];
        foreach ($offices as $city => $zone) {
            $whatsapp = $settings?->{"{$city}_whatsapp"} ?? null;
            if ($whatsapp) {
                $officeByPhone[preg_replace('/[^0-9]/', '', $whatsapp)] = ['city' => $city, 'zone' => $zone];
            }
        }

        $activeNumbers = \App\Models\WhatsAppNumber::with('state')->where('is_active', true)->get();
        foreach ($activeNumbers as $number) {
            $state = $number->state;
            if (!$state) {
                continue;
            }

            $phoneDigits = preg_replace('/[^0-9]/', '', $number->phone_number);
            $office = $officeByPhone[$phoneDigits] ?? ['city' => null, 'zone' => null];
            $city = $office['city'];

            $pickups[$state->code] = [
                'label'    => $city ? ucfirst($city) : ($number->executive_name ?? $state->name),
                'zone'     => $office['zone'],
                'phone'    => $number->formatted_number,
                'whatsapp' => $city ? ($settings?->{"{$city}_whatsapp"} ?? null) : null,
                'location' => $city ? ($settings?->{"{$city}_location"} ?? null) : null,
            ];
        }

        return view('web.solicitud', compact('customerTypes', 'deliveryMethods', 'paymentMethods', 'states', 'cities', 'settings', 'pickups'));
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

        // Estadísticas
        $totalResources = \App\Models\Resource::where('is_active', true)->count();
        $totalSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)
            ->whereHas('resources', function ($query) {
                $query->where('is_active', true);
            })
            ->count();
        $totalPDFs = \App\Models\Resource::where('is_active', true)->where('format', 'pdf')->count();
        $totalCases = \App\Models\Resource::where('is_active', true)->where('type', 'case_study')->count();

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
        $search          = request('search', '');
        $typeId          = request('type', '');
        $specialtyId     = request('specialty', '');
        $format          = request('format', []);
        $resourceType    = request('resource_type', []);
        $resourceSpecialty = request('resource_specialty', []);
        $sortBy          = request('sort', 'position');

        $resourcesQuery = \App\Models\Resource::where('is_active', true);

        if ($search) {
            $resourcesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($typeId) {
            $resourcesQuery->where('resource_type_id', $typeId);
        }

        if ($specialtyId) {
            $resourcesQuery->where('resource_specialty_id', $specialtyId);
        }

        if (!empty($resourceType)) {
            $resourcesQuery->whereIn('resource_type_id', (array) $resourceType);
        }

        if (!empty($resourceSpecialty)) {
            $resourcesQuery->whereIn('resource_specialty_id', (array) $resourceSpecialty);
        }

        if (!empty($format)) {
            $resourcesQuery->whereIn('format', (array) $format);
        }

        $baseResources = $resourcesQuery
            ->with(['resourceType','resourceSpecialty'])
            ->orderBy($sortBy === 'recent' ? 'created_at' : 'position', $sortBy === 'recent' ? 'desc' : 'asc')
            ->get();

        $perPage = 12;
        $currentPage = (int) request('page', 1);
        $resources = new \Illuminate\Pagination\LengthAwarePaginator(
            $baseResources->forPage($currentPage, $perPage)->values(),
            $baseResources->count(),
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

        $products = \App\Models\Product::with(['category', 'brand', 'images'])
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('sku', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'image' => $product->main_image_url,
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

    /**
     * Detalle de caso clínico
     */
    public function casoClinico($slug)
    {
        $resource = \App\Models\Resource::with(['resourceType', 'resourceSpecialty'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('web.caso-clinico', compact('resource'));
    }

    /**
     * Solicitud comercial enviada
     */
    public function solicitudEnviada($uuid)
    {
        $commercialRequest = \App\Models\CommercialRequest::with(['deliveryMethod', 'paymentMethod', 'whatsappNumber'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $cartItems = [];
        $subtotal = 0;

        if ($commercialRequest) {
            $cartData = json_decode($commercialRequest->cart_data, true) ?? [];
            foreach ($cartData as $item) {
                $product = \App\Models\Product::find($item['id']);
                if ($product) {
                    $cartItems[] = (object)[
                        'product' => $product,
                        'quantity' => $item['quantity']
                    ];
                    $subtotal += $product->price * $item['quantity'];
                }
            }
        }

        // Obtener tasa de conversión a Bs. desde la API CV (DolarAPI Venezuela)
        $tasa = 0;
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->get('https://ve.dolarapi.com/v1/dolares/oficial');
            if ($response->successful()) {
                $tasa = (float) ($response->json('promedio') ?? 0);
            }
        } catch (\Exception $e) {
            report($e);
        }

        $total = $subtotal * $tasa;

        return view('web.solicitud-enviada', compact('uuid', 'commercialRequest', 'cartItems', 'subtotal', 'tasa', 'total'));
    }
}
