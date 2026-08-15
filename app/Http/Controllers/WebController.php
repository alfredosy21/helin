<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\CommercialRequest;
use App\Models\CustomerType;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ResourceSpecialty;
use App\Models\ResourceType;
use App\Models\Sections;
use App\Models\Settings;
use App\Models\State;
use App\Models\Testimonial;
use App\Models\WhatsAppNumber;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class WebController extends Controller
{
    /**
     * Página principal (Home)
     */
    public function home()
    {
        // Hero section
        $heroSection = Sections::find(Sections::HERO_HOME);

        // Flow how-to section
        $howToSection = Sections::find(Sections::FLOW_HOW_TO);

        // Featured products
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('images')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Product sections
        $productSections = Sections::where('status', 1)
            ->whereIn('id', [Sections::IMPLANTOLOGY_PRODUCTS, Sections::GBR_PRODUCTS, Sections::INSTRUMENTS_PRODUCTS])
            ->orderBy('id')
            ->get();

        // Testimonials section
        $testimonialsSection = Sections::find(Sections::TESTIMONIALS);

        // Testimonials data
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('position', 'asc')
            ->take(4)
            ->get();

        return view('web.home', compact(
            'heroSection', 'howToSection', 'featuredProducts',
            'productSections', 'testimonialsSection', 'testimonials'
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
            $currentCategory = Category::where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();
        }

        // Get products with filters
        $query = Product::with(['category', 'brand', 'images'])
            ->where('is_active', true);

        // Apply category filter if present
        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }

        // Apply brand filter if present
        $brandSlugs = (array) request('brand');
        if (! empty($brandSlugs)) {
            $brandIds = Brand::whereIn('slug', $brandSlugs)->where('is_active', true)->pluck('id');
            if ($brandIds->isNotEmpty()) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        // Apply tag filter if present
        $tags = (array) request('tag');
        if (! empty($tags)) {
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
                        $query->where('material', 'like', '%'.$tagValue.'%');
                    }
                }
            }
        }

        // Apply featured filter if passed directly (deep link "Ver todos los productos")
        if (request('featured') === '1') {
            $query->where('is_featured', true);
        }

        // Apply material filter if present
        $materials = (array) request('material');
        if (! empty($materials)) {
            $query->where(function ($q) use ($materials) {
                foreach ($materials as $m) {
                    $q->orWhere('material', 'like', '%'.$m.'%');
                }
            });
        }

        // Apply search filter if present
        $searchTerm = request('search');
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%')
                    ->orWhere('sku', 'like', '%'.$searchTerm.'%');
            });
        }

        // Apply sort (default: recent, same as AJAX endpoint)
        $sortBy = request('sort', 'recent');
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
                break;
        }

        $products = $query->paginate(15)->withQueryString();

        return view('web.catalogo', compact('products', 'currentCategory'));
    }

    /**
     * Detalle de producto
     */
    public function producto(string $slug)
    {
        $product = Product::with(['category', 'brand', 'systemProduct', 'productPlatform', 'attributeValues', 'images', 'documents'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Obtener productos relacionados
        $relatedProducts = Product::where('category_id', $product->category_id)
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
        $customerTypes = CustomerType::active()->ordered()->get();
        $deliveryMethods = DeliveryMethod::active()->ordered()->get();
        $paymentMethods = PaymentMethod::active()->ordered()->get();
        $states = State::ordered()->get();
        $cities = City::all(); // all for JS filter
        $settings = Settings::getSettings();

        $pickups = [];
        $settings = is_object($settings) ? $settings : null;

        // Zonas por ciudad: desde Settings->offices (cast array) o fallback por convención
        $defaultOffices = ['caracas' => 1, 'valencia' => 2, 'barquisimeto' => 3, 'maracaibo' => 4, 'maracay' => 5];
        $settingsOffices = $settings && is_array($settings->offices) ? $settings->offices : [];
        $offices = [];
        $officeData = [];
        foreach ($settingsOffices as $office) {
            $cityKey = strtolower(trim((string) ($office['city'] ?? '')));
            if ($cityKey !== '') {
                $offices[$cityKey] = (int) ($office['zone'] ?? $defaultOffices[$cityKey] ?? 0);
                $officeData[$cityKey] = $office;
            }
        }
        $offices = $offices ?: $defaultOffices;

        $officeByPhone = [];
        foreach ($offices as $city => $zone) {
            $whatsapp = $officeData[$city]['whatsapp'] ?? null;
            if ($whatsapp) {
                $officeByPhone[preg_replace('/[^0-9]/', '', $whatsapp)] = ['city' => $city, 'zone' => $zone];
            }
        }

        $activeNumbers = WhatsAppNumber::with('state')->where('is_active', true)->get();
        foreach ($activeNumbers as $number) {
            $state = $number->state;
            if (! $state) {
                continue;
            }

            $phoneDigits = preg_replace('/[^0-9]/', '', $number->phone_number);
            $office = $officeByPhone[$phoneDigits] ?? ['city' => null, 'zone' => null];
            $city = $office['city'];

            $pickups[$state->code] = [
                'label' => $city ? ucfirst($city) : ($number->executive_name ?? $state->name),
                'zone' => $office['zone'],
                'phone' => $number->formatted_number,
                'whatsapp' => $city ? ($officeData[$city]['whatsapp'] ?? null) : null,
                'location' => $city ? ($officeData[$city]['location'] ?? null) : null,
            ];
        }

        return view('web.solicitud', compact('customerTypes', 'deliveryMethods', 'paymentMethods', 'states', 'cities', 'settings', 'pickups'));
    }

    /**
     * Contacto
     */
    public function contactanos()
    {
        $settings = Settings::getSettings();
        $contactSection = Sections::find(Sections::CONTACT_HERO);

        return view('web.contactanos', compact('settings', 'contactSection'));
    }

    /**
     * Nuestra empresa
     */
    public function nuestraEmpresa()
    {
        $companyHeroSection = Sections::find(Sections::COMPANY_HERO);
        $aboutSection = Sections::find(Sections::ABOUT_US);
        $missionSection = Sections::find(Sections::MISSION_VISION);
        $teamSection = Sections::find(Sections::TEAM);
        $alliesSection = Sections::find(Sections::ALLIES);
        $ctaSection = Sections::find(Sections::CTA_COMPANY);

        return view('web.nuestra-empresa', compact('companyHeroSection', 'aboutSection', 'missionSection', 'teamSection', 'alliesSection', 'ctaSection'));
    }

    /**
     * Políticas
     */
    public function politicas()
    {
        $sections = Sections::where('status', 1)
            ->whereIn('id', [Sections::SHIPPING_POLICIES, Sections::TERMS_CONDITIONS, Sections::PRIVACY_POLICIES])
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
        $heroSection = Sections::find(Sections::CLINICAL_RESOURCES_HERO);

        // Estadísticas
        $totalResources = \App\Models\Resource::where('is_active', true)->count();
        $totalSpecialties = ResourceSpecialty::where('is_active', true)
            ->whereHas('resources', function ($query) {
                $query->where('is_active', true);
            })
            ->count();
        $totalPDFs = \App\Models\Resource::where('is_active', true)->where('format', 'pdf')->count();
        $totalCases = \App\Models\Resource::where('is_active', true)->where('type', 'case_study')->count();

        // Library section
        $librarySection = Sections::find(Sections::CLINICAL_LIBRARY);

        // Stats section
        $statsSection = Sections::find(Sections::CLINICAL_STATS);

        // Featured CTA section
        $featuredSection = Sections::find(Sections::CLINICAL_CONTENT_FEATURE);

        // Filtros
        $resourceSpecialties = ResourceSpecialty::where('is_active', true)->orderBy('name')->get();
        $resourceTypes = ResourceType::where('is_active', true)->orderBy('name')->get();

        // Contadores para filtros
        $resourceTypeCounts = ResourceType::where('is_active', true)
            ->withCount(['resources' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        $resourceSpecialtyCounts = ResourceSpecialty::where('is_active', true)
            ->withCount(['resources' => function ($query) {
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
        $search = request('search', '');
        $typeId = request('type', '');
        $specialtyId = request('specialty', '');
        $format = request('format', []);
        $resourceType = request('resource_type', []);
        $resourceSpecialty = request('resource_specialty', []);
        $sortBy = request('sort', 'position');

        $resourcesQuery = \App\Models\Resource::where('is_active', true);

        if ($search) {
            $resourcesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($typeId) {
            $resourcesQuery->where('resource_type_id', $typeId);
        }

        if ($specialtyId) {
            $resourcesQuery->where('resource_specialty_id', $specialtyId);
        }

        if (! empty($resourceType)) {
            $resourcesQuery->whereIn('resource_type_id', (array) $resourceType);
        }

        if (! empty($resourceSpecialty)) {
            $resourcesQuery->whereIn('resource_specialty_id', (array) $resourceSpecialty);
        }

        if (! empty($format)) {
            $resourcesQuery->whereIn('format', (array) $format);
        }

        $baseResources = $resourcesQuery
            ->with(['resourceType', 'resourceSpecialty'])
            ->orderBy($sortBy === 'recent' ? 'created_at' : 'position', $sortBy === 'recent' ? 'desc' : 'asc')
            ->get();

        $perPage = 12;
        $currentPage = (int) request('page', 1);
        $resources = new LengthAwarePaginator(
            $baseResources->forPage($currentPage, $perPage)->values(),
            $baseResources->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('web.recursos-clinicos', compact(
            'heroSection', 'totalResources', 'totalSpecialties', 'totalPDFs', 'totalCases',
            'statsSection', 'librarySection', 'featuredSection', 'resourceSpecialties', 'resourceTypes', 'resourceTypeCounts',
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

        $products = Product::with(['category', 'brand', 'images'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%')
                    ->orWhere('sku', 'like', '%'.$query.'%');
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $products->map(function ($product) {
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
        $commercialRequest = CommercialRequest::with(['deliveryMethod', 'paymentMethod', 'whatsappNumber'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $cartItems = [];
        $subtotal = 0;

        if ($commercialRequest) {
            $cartData = is_array($commercialRequest->cart_data)
                ? $commercialRequest->cart_data
                : json_decode($commercialRequest->cart_data, true) ?? [];
            foreach ($cartData as $item) {
                $slug = explode('::', $item['id'] ?? '')[0];
                $product = $slug ? Product::where('slug', $slug)->first() : null;
                if ($product) {
                    $cartItems[] = (object) [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                    ];
                    $subtotal += $product->price * $item['quantity'];
                }
            }
        }

        // Obtener tasa de conversión a Bs. desde la API CV (DolarAPI Venezuela)
        $tasa = 0;
        try {
            $response = Http::timeout(10)
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

    /**
     * Descargar PDF de cotización comercial.
     */
    public function downloadCotizacionPdf($uuid)
    {
        $commercialRequest = CommercialRequest::with([
            'customerType',
            'state',
            'city',
            'deliveryMethod',
            'paymentMethod',
            'whatsappNumber',
        ])->where('uuid', $uuid)->firstOrFail();

        $cartItems = [];
        $subtotal = 0;

        $cartData = is_array($commercialRequest->cart_data)
            ? $commercialRequest->cart_data
            : json_decode($commercialRequest->cart_data, true) ?? [];

        foreach ($cartData as $item) {
            $slug = explode('::', $item['id'] ?? '')[0];
            $product = $slug ? Product::where('slug', $slug)->first() : null;
            if ($product) {
                $cartItems[] = (object) [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                ];
                $subtotal += $product->price * ($item['quantity'] ?? 1);
            }
        }

        $settings = Settings::getSettings();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.cotizacion', [
            'commercialRequest' => $commercialRequest,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("cotizacion-{$commercialRequest->correlative}.pdf");
    }
}
