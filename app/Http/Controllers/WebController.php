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

        // Apply tag filters
        $tag = request('tag');
        if ($tag === 'featured') {
            $query->where('is_featured', true);
        } elseif ($tag === 'new') {
            $query->where('is_new', true);
        } elseif ($tag === 'on_sale') {
            $query->where('is_on_sale', true);
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

        // Estadísticas
        $totalResources = \App\Models\Resource::where('is_active', true)->count();
        $totalSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->count();
        $totalPDFs = \App\Models\Resource::where('is_active', true)->where('format', 'pdf')->count();
        $totalCases = \App\Models\Resource::where('is_active', true)->where('type', 'case')->count();

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
        $resources = \App\Models\Resource::where('is_active', true)
            ->with(['resourceType','resourceSpecialty'])
            ->orderBy($sortBy === 'recent' ? 'created_at' : 'position', $sortBy === 'recent' ? 'desc' : 'asc')
            ->paginate(12);

        return view('web.recursos-clinicos', compact(
            'heroSection', 'totalResources', 'totalSpecialties', 'totalPDFs', 'totalCases',
            'librarySection', 'resourceSpecialties', 'resourceTypes', 'resourceTypeCounts',
            'resourceSpecialtyCounts', 'formats', 'resources'
        ));
    }
}
