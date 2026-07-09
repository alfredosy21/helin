@extends('web.layouts.app')

@php
    /**
     * Get current category for metadata
     */
    $initCategory = request('category', '');
    $currentCategory = null;
    if ($initCategory) {
        $currentCategory = \App\Models\Category::where('slug', $initCategory)->first();
    }
@endphp

@section('title', $currentCategory ? ($currentCategory->name . ' - Catálogo - Helin') : 'Catálogo de Productos - Helin')
@section('meta-description', $currentCategory ? ($currentCategory->seo_description ?? $currentCategory->description ?? 'Explora nuestra selección de ' . $currentCategory->name . ' en Helin. Productos de alta calidad para profesionales odontológicos con garantía y envío a todo Venezuela.') : 'Explora nuestro catálogo completo de productos odontológicos. Implantes, instrumentos, biomateriales y equipos de las mejores marcas. Calidad garantizada Helin.')
@section('meta-keywords', $currentCategory ? ($currentCategory->seo_keywords ?? ($currentCategory->name . ', ' . ($currentCategory->name . ' Venezuela') . ', productos odontológicos, helin, material dental')) : 'catálogo productos odontológicos, implantes dentales, instrumentos quirúrgicos, biomateriales, equipos odontológicos, helin, material dental Venezuela')
@section('og-type', 'website')
@section('og-image', $currentCategory && $currentCategory->image ? asset('storage/' . $currentCategory->image) : asset('images/helin-catalog-og.jpg'))

@section('content')
@php
    $sidebarCategories = \App\Models\Category::active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
    $sidebarBrands     = \App\Models\Brand::active()->ordered()->get();

    $initSearch   = request('search', '');
    $initCategory = request('category', '');

    /**
     * Get current category if filter is applied
     */
    $currentCategory = null;
    if ($initCategory) {
        $currentCategory = \App\Models\Category::where('slug', $initCategory)->first();
    }

    $productsQuery = \App\Models\Product::with(['category', 'brand'])->where('is_active', true);
    if ($initSearch)   $productsQuery->where(fn($q) => $q->where('name','like',"%$initSearch%")->orWhere('description','like',"%$initSearch%")->orWhere('sku','like',"%$initSearch%"));
    if ($initCategory) $productsQuery->whereHas('category', fn($q) => $q->where('slug', $initCategory));
    $products = $productsQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
@endphp

<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filtros -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="space-y-6">
                @php
    $breadcrumbItems = [
        ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="hover:text-turquesa"']
    ];
    
    if ($currentCategory) {
        $breadcrumbItems[] = ['label' => 'Productos', 'url' => route('catalogo'), 'linkAttributes' => 'class="hover:text-turquesa"'];
        $breadcrumbItems[] = ['label' => $currentCategory->name, 'spanAttributes' => 'class="text-turquesa font-medium"'];
    } else {
        $breadcrumbItems[] = ['label' => 'Productos', 'spanAttributes' => 'class="text-turquesa font-medium"'];
    }
@endphp

@include('web.components.breadcrumb', [
    'attributes' => 'class="text-sm text-helin-text mb-6"',
    'items' => $breadcrumbItems,
    'separatorAttributes' => 'class="text-helin-text mx-1"'
])

                <!-- Búsqueda en sidebar -->
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-helin-text text-xs"></i>
                    <input id="catalogSearch" type="text" value="{{ $initSearch }}" placeholder="Buscar productos..." class="w-full border border-helin-border rounded-lg pl-8 pr-3 py-2 text-sm outline-none focus:border-turquesa">
                </div>

                <!-- Botón limpiar -->
                <button id="clearFilters" class="hidden items-center gap-1 text-xs text-turquesa hover:underline">
                    <i class="fas fa-times-circle"></i> Limpiar filtros
                </button>

                <hr class="border-helin-border">

                <!-- Categorías -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Categorías</h4>
                    <div class="space-y-2">
                        @foreach($sidebarCategories as $cat)
                            <label class="flex items-center justify-between cursor-pointer hover:text-turquesa transition-colors group">
                                <span class="flex items-center gap-2">
                                    <input type="checkbox"
                                           class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border"
                                           data-filter-type="category"
                                           value="{{ $cat->slug }}"
                                           {{ $initCategory === $cat->slug ? 'checked' : '' }}>
                                    <span class="text-helin-text text-sm group-hover:text-turquesa">{{ $cat->name }}</span>
                                </span>
                                <span class="w-6 h-6 rounded-full bg-helin-soft text-helin-text text-xs flex items-center justify-center flex-shrink-0">{{ $cat->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-helin-border">

                <!-- Marcas -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Marcas</h4>
                    <div class="space-y-2">
                        @foreach($sidebarBrands as $brand)
                            <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                                <input type="checkbox"
                                       class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border"
                                       data-filter-type="brand"
                                       value="{{ $brand->slug }}">
                                <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-helin-border">

                <!-- Tags -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Filtros rápidos</h4>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="featured">
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Destacados</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="on_sale">
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Ofertas</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="new">
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Nuevos</span>
                        </label>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Área de Productos -->
        <div class="flex-1 min-w-0">

            <!-- Banner -->
            @if($currentCategory)
            @php
                $bannerBg = asset('images/banner_imp1.png');
                $categoryBanners = [
                    'implantes' => [
                        'label'       => 'Bienvenidos al Catálogo de Implantología',
                        'title'       => 'Todo Para Tus Procedimientos De Implantología En Un Solo Lugar',
                        'description' => 'Encuentra componentes, instrumentos y soluciones especializadas para optimizar cada etapa clínica.',
                        'bg'          => $bannerBg,
                    ],
                    'aditamentos' => [
                        'label'       => 'Catálogo de Aditamentos',
                        'title'       => 'Aditamentos Para Tus Procedimientos En Un Solo Lugar',
                        'description' => 'Encuentra aditamentos de alta calidad para complementar tus procedimientos clínicos.',
                        'bg'          => $bannerBg,
                    ],
                    'kits-quirurgicos' => [
                        'label'       => 'Catálogo de Kits Quirúrgicos',
                        'title'       => 'Kits Quirúrgicos Completos Para Cada Procedimiento',
                        'description' => 'Todo lo que necesitas en un solo kit para tus procedimientos quirúrgicos.',
                        'bg'          => $bannerBg,
                    ],
                    'biomateriales' => [
                        'label'       => 'Catálogo de Biomateriales',
                        'title'       => 'Biomateriales De Alta Calidad Para Tu Práctica Clínica',
                        'description' => 'Selección de biomateriales especializados para procedimientos de regeneración.',
                        'bg'          => $bannerBg,
                    ],
                    'regeneracion-guiada-bucal-gbr' => [
                        'label'       => 'Catálogo de Regeneración Ósea Guiada',
                        'title'       => 'Soluciones Completas En Regeneración Ósea Guiada',
                        'description' => 'Encuentra los mejores productos para procedimientos de regeneración ósea y tisular.',
                        'bg'          => $bannerBg,
                    ],
                    'suturas' => [
                        'label'       => 'Catálogo de Suturas',
                        'title'       => 'Suturas De Alta Calidad Para Procedimientos Odontológicos',
                        'description' => 'Amplia variedad de suturas especializadas para cirugías bucales y maxilofaciales.',
                        'bg'          => $bannerBg,
                    ],
                    'placas' => [
                        'label'       => 'Catálogo de Osteosíntesis',
                        'title'       => 'Placas Y Sistemas De Fijación Para Cirugía Maxilofacial',
                        'description' => 'Sistemas de osteosíntesis de titanio de alta precisión para reconstrucción ósea.',
                        'bg'          => $bannerBg,
                    ],
                    'tornillos' => [
                        'label'       => 'Catálogo de Osteosíntesis',
                        'title'       => 'Tornillos Y Fijaciones Para Cirugía Maxilofacial',
                        'description' => 'Tornillos de osteosíntesis de alta resistencia para procedimientos de fijación ósea.',
                        'bg'          => $bannerBg,
                    ],
                    'cajetin' => [
                        'label'       => 'Catálogo de Osteosíntesis',
                        'title'       => 'Cajetines Y Accesorios Para Cirugía Maxilofacial',
                        'description' => 'Sistemas completos de cajetines para almacenamiento y uso de implantes quirúrgicos.',
                        'bg'          => $bannerBg,
                    ],
                    'cuidados-especiales-quirurgicos' => [
                        'label'       => 'Catálogo de Cuidado Bucal',
                        'title'       => 'Cuidados Especiales Para El Postoperatorio Quirúrgico',
                        'description' => 'Productos especializados para el cuidado y recuperación en el postoperatorio.',
                        'bg'          => $bannerBg,
                    ],
                    'cuidados-diarios-paciente' => [
                        'label'       => 'Catálogo de Cuidado Bucal',
                        'title'       => 'Cuidados Diarios Para La Salud Bucal De Tu Paciente',
                        'description' => 'Soluciones para el cuidado diario y mantenimiento de la salud bucal.',
                        'bg'          => $bannerBg,
                    ],
                    'tijeras' => [
                        'label'       => 'Catálogo de Instrumentos',
                        'title'       => 'Instrumentos De Precisión Para Procedimientos Odontológicos',
                        'description' => 'Tijeras y herramientas de corte de alta precisión para cirugía bucal.',
                        'bg'          => $bannerBg,
                    ],
                    'pinzas' => [
                        'label'       => 'Catálogo de Instrumentos',
                        'title'       => 'Pinzas Y Herramientas Para Procedimientos Odontológicos',
                        'description' => 'Pinzas de alta calidad diseñadas para procedimientos odontológicos especializados.',
                        'bg'          => $bannerBg,
                    ],
                    'separadores' => [
                        'label'       => 'Catálogo de Instrumentos',
                        'title'       => 'Separadores Para Cirugía Bucal Y Maxilofacial',
                        'description' => 'Separadores de tejidos de alta precisión para una mejor visibilidad quirúrgica.',
                        'bg'          => $bannerBg,
                    ],
                    'cinceles' => [
                        'label'       => 'Catálogo de Instrumentos',
                        'title'       => 'Cinceles Y Escoplos Para Cirugía Ósea',
                        'description' => 'Cinceles de acero quirúrgico de alta calidad para procedimientos de osteotomía.',
                        'bg'          => $bannerBg,
                    ],
                    'periostomos' => [
                        'label'       => 'Catálogo de Instrumentos',
                        'title'       => 'Periostótomos Para Cirugía Bucal Especializada',
                        'description' => 'Periostótomos de precisión para el despegamiento y manejo de tejidos periósticos.',
                        'bg'          => $bannerBg,
                    ],
                    'equipos-odontologicos' => [
                        'label'       => 'Catálogo de Equipos',
                        'title'       => 'Equipos Odontológicos De Alta Tecnología',
                        'description' => 'Equipos especializados para optimizar cada procedimiento en tu consultorio.',
                        'bg'          => $bannerBg,
                    ],
                    'planificacion-digital' => [
                        'label'       => 'Catálogo de Planificación Digital',
                        'title'       => 'Soluciones Digitales Para Tu Práctica Odontológica',
                        'description' => 'Herramientas de planificación digital, impresión 3D y escaneo intraoral de última generación.',
                        'bg'          => $bannerBg,
                    ],
                ];
                $bannerData = $categoryBanners[$currentCategory->slug] ?? null;
            @endphp
            @php
                $bannerStyle = ($bannerData && isset($bannerData['bg']))
                    ? 'min-height:160px; background-image:url(\'' . $bannerData['bg'] . '\'); background-size:cover; background-position:center;'
                    : 'min-height:160px; background:linear-gradient(135deg,#3bbfbf 0%,#2aa8a8 60%,#1a9090 100%);';
            @endphp
            <div class="rounded-2xl mb-6 relative overflow-hidden" style="{{ $bannerStyle }}">
                <div class="p-5 sm:p-7 lg:p-8 w-full sm:max-w-[58%]">
                    <p class="text-white/85 text-xs mb-2 font-medium">{{ $bannerData['label'] ?? 'Bienvenidos al Catálogo de ' . $currentCategory->name }}</p>
                    <h1 class="text-white font-black text-lg sm:text-xl lg:text-2xl leading-tight mb-2" style="text-shadow: 0 1px 4px rgba(0,0,0,0.2);">
                        {{ $bannerData['title'] ?? 'Todo Para Tus Procedimientos De ' . $currentCategory->name . ' En Un Solo Lugar' }}
                    </h1>
                    <p class="text-white/85 text-xs sm:text-sm mb-4">{{ $bannerData['description'] ?? ($currentCategory->seo_description ?? $currentCategory->description ?? 'Encuentra componentes, instrumentos y soluciones especializadas para optimizar cada etapa clínica.') }}</p>
                </div>
            </div>
            @endif

            <!-- Skeleton Loader mientras carga -->
            <div id="productsSkeleton" class="skeleton-grid skeleton-grid-responsive">
                @for($i = 1; $i <= 9; $i++)
                    @include('web.components.skeleton-product')
                @endfor
            </div>

            <!-- Indicador de carga AJAX -->
            <div id="catalogLoading" class="hidden text-center py-4">
                <i class="fas fa-spinner fa-spin text-turquesa text-2xl"></i>
            </div>

            <!-- Contenedor AJAX -->
            <div id="productsContent" class="hidden">
                @include('web.partials.product-results', ['products' => $products])
            </div>

        </div>
    </div>
</main>

@include('web.partials.beneficios')
@endsection

@push('scripts')
<script src="{{ asset('helin/js/catalogo.js') }}"></script>
<script>
// Ocultar skeleton y mostrar contenido cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const skeleton = document.getElementById('productsSkeleton');
        const content = document.getElementById('productsContent');
        
        if (skeleton && content) {
            skeleton.style.display = 'none';
            content.classList.remove('hidden');
        }
    }, 500); // Pequeño delay para simular carga inicial
});

// Mostrar skeleton durante cargas AJAX
function showProductsSkeleton() {
    const skeleton = document.getElementById('productsSkeleton');
    const content = document.getElementById('productsContent');
    const loading = document.getElementById('catalogLoading');
    
    if (skeleton) skeleton.style.display = 'grid';
    if (content) content.classList.add('hidden');
    if (loading) loading.classList.add('hidden');
}

// Ocultar skeleton y mostrar contenido
function hideProductsSkeleton() {
    const skeleton = document.getElementById('productsSkeleton');
    const content = document.getElementById('productsContent');
    
    if (skeleton) skeleton.style.display = 'none';
    if (content) content.classList.remove('hidden');
}
</script>
@endpush
