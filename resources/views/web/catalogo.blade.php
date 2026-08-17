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

@section('title', $currentCategory ? ($currentCategory->name . ' - Catálogo - Helin') : ($pageSeo?->seo_title ?? 'Catálogo de Productos - Helin'))
@section('meta-description', $currentCategory ? ($currentCategory->seo_description ?? $currentCategory->description ?? 'Explora nuestra selección de ' . $currentCategory->name . ' en Helin. Productos de alta calidad para profesionales odontológicos con garantía y envío a todo Venezuela.') : ($pageSeo?->seo_description ?? 'Explora nuestro catálogo completo de productos odontológicos. Implantes, instrumentos, biomateriales y equipos de las mejores marcas. Calidad garantizada Helin.'))
@section('meta-keywords', $currentCategory ? ($currentCategory->seo_keywords ?? ($currentCategory->name . ', ' . ($currentCategory->name . ' Venezuela') . ', productos odontológicos, helin, material dental')) : ($pageSeo?->seo_keywords ?? 'catálogo productos odontológicos, implantes dentales, instrumentos quirúrgicos, biomateriales, equipos odontológicos, helin, material dental Venezuela'))
@section('og-type', 'website')
@section('og-image', $currentCategory && $currentCategory->image ? asset('storage/' . $currentCategory->image) : ($pageSeo?->og_image ? asset('storage/' . $pageSeo->og_image) : null))

@section('content')
@php
    $sidebarCategories = \App\Models\Category::active()->ordered()->withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
    $sidebarBrands     = \App\Models\Brand::active()->ordered()->get();
    $sidebarMaterials  = \App\Models\Product::where('is_active', true)->whereNotNull('material')->where('material', '!=', '')->distinct()->orderBy('material')->pluck('material');

    $selectedCategories = (array) request()->input('category');
    $selectedBrands     = (array) request()->input('brand');
    $selectedMaterials  = (array) request()->input('material');
    $selectedTags       = (array) request()->input('tag');

    $initSearch   = request('search', '');
    $initCategory = request('category', '');

    /**
     * Get current category if filter is applied
     */
    $currentCategory = null;
    if ($initCategory) {
        $currentCategory = \App\Models\Category::where('slug', $initCategory)->first();
    }
@endphp

<hr class="hidden lg:block w-full" style="border:none;border-top:1px solid rgba(0,0,0,0.06);">

<main class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10 py-8">
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
    'attributes' => 'text-sm text-helin-text mb-10 w-full',
    'items' => $breadcrumbItems,
    'separatorAttributes' => 'class="text-helin-text mx-1"'
])

    <!-- Botón Filtros - solo móvil/tablet -->
    <div class="lg:hidden mb-4">
        <button type="button" id="mobileFiltersToggle" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 rounded-full py-2.5 text-sm font-medium text-helin-heading hover:bg-slate-50 transition-colors relative">
            <i class="fas fa-sliders-h text-sm"></i>
            <span>Filtros</span>
            <span id="mobileFiltersCount" class="hidden ml-1 bg-turquesa text-white text-[11px] font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
        </button>
    </div>

    <!-- Backdrop del panel de filtros móvil -->
    <div id="filtersOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filtros -->
        <aside id="filtersPanel" class="w-80 max-w-[85%] lg:w-64 lg:max-w-none flex-shrink-0 fixed lg:static inset-y-0 left-0 z-50 lg:z-auto bg-white lg:bg-transparent shadow-2xl lg:shadow-none transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto lg:overflow-visible flex flex-col">

            <!-- Encabezado del panel - solo móvil -->
            <div class="lg:hidden flex items-center justify-between px-4 py-4 border-b border-helin-border sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-helin-heading text-base">Filtros</h3>
                <button type="button" id="closeFiltersBtn" class="text-helin-text text-2xl leading-none p-1 hover:text-turquesa transition-colors">&times;</button>
            </div>

            <div class="space-y-6 p-4 lg:p-0 flex-1">
                <!-- Búsqueda en sidebar -->
                <div class="relative mt-4" id="catalogSearchWrapper">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-helin-text text-xs z-10"></i>
                    <input id="catalogSearch" type="text" value="{{ $initSearch }}" placeholder="Buscar productos..." autocomplete="off" class="w-full border border-helin-border rounded-lg pl-8 pr-3 py-2 text-sm outline-none focus:border-turquesa relative z-10">
                    <div id="searchAutocomplete" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-helin-border rounded-lg shadow-lg z-50 max-h-80 overflow-y-auto"></div>
                </div>

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
                                           {{ in_array($cat->slug, $selectedCategories) ? 'checked' : '' }}>
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
                                       value="{{ $brand->slug }}"
                                       {{ in_array($brand->slug, $selectedBrands) ? 'checked' : '' }}>
                                <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-helin-border">

                <!-- Materiales -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Materiales</h4>
                    <div class="space-y-2">
                        @foreach($sidebarMaterials as $material)
                            <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                                <input type="checkbox"
                                       class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border"
                                       data-filter-type="material"
                                       value="{{ strtolower($material) }}"
                                       {{ in_array(strtolower($material), $selectedMaterials) ? 'checked' : '' }}>
                                <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">{{ $material }}</span>
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
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="featured" {{ in_array('featured', $selectedTags) ? 'checked' : '' }}>
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Destacados</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="on_sale" {{ in_array('on_sale', $selectedTags) ? 'checked' : '' }}>
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Ofertas</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="new" {{ in_array('new', $selectedTags) ? 'checked' : '' }}>
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Nuevos</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors group">
                            <input type="checkbox" class="filter-checkbox w-4 h-4 accent-turquesa rounded border-helin-border" data-filter-type="tag" value="biomaterial" {{ in_array('biomaterial', $selectedTags) ? 'checked' : '' }}>
                            <span class="ml-2 text-helin-text text-sm group-hover:text-turquesa">Biomateriales</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer del panel - solo móvil -->
            <div class="lg:hidden sticky bottom-0 bg-white border-t border-helin-border p-4 flex gap-3">
                <button type="button" id="mobileClearFilters" class="flex-1 border border-helin-border text-helin-heading rounded-full py-2.5 text-sm font-medium hover:bg-slate-50 transition-colors">
                    Limpiar filtros
                </button>
                <button type="button" id="mobileApplyFilters" class="flex-1 bg-turquesa hover:bg-turquesa-dark text-white rounded-full py-2.5 text-sm font-semibold transition-colors">
                    Ver productos
                </button>
            </div>
        </aside>

        <!-- Área de Productos -->
        <div class="flex-1 min-w-0">

            <!-- Banner -->
            @if($currentCategory)
            @php
                $catalogoSettings = \App\Models\Settings::getSettings();
                $bannerBg = $catalogoSettings && $catalogoSettings->default_banner_image ? asset('storage/' . $catalogoSettings->default_banner_image) : null;
                $bannerData = [
                    'label'       => $currentCategory->banner_label ?: ('Bienvenidos al Catálogo de ' . $currentCategory->name),
                    'title'       => $currentCategory->banner_title ?: ('Todo Para Tus Procedimientos De ' . $currentCategory->name . ' En Un Solo Lugar'),
                    'description' => $currentCategory->banner_description ?: ($currentCategory->seo_description ?? $currentCategory->description ?? 'Encuentra componentes, instrumentos y soluciones especializadas para optimizar cada etapa clínica.'),
                    'bg'          => $currentCategory->banner_image ? asset('storage/' . $currentCategory->banner_image) : $bannerBg,
                ];
            @endphp
            @php
                $bannerStyle = ($bannerData && isset($bannerData['bg']))
                    ? 'min-height:160px; background-image:url(\'' . $bannerData['bg'] . '\'); background-size:cover; background-position:center;'
                    : 'min-height:160px; background:linear-gradient(135deg,#3bbfbf 0%,#2aa8a8 60%,#1a9090 100%);';
            @endphp
            <div class="rounded-2xl mb-6 relative overflow-hidden" style="{{ $bannerStyle }}">
                <div class="p-5 sm:p-7 lg:p-8 w-full sm:max-w-[58%]">
                    <p class="text-white/85 text-xs mb-2 font-medium">{{ $bannerData['label'] }}</p>
                    <h1 class="text-white font-black text-lg sm:text-xl lg:text-2xl leading-tight mb-2" style="text-shadow: 0 1px 4px rgba(0,0,0,0.2);">
                        {{ $bannerData['title'] }}
                    </h1>
                    <p class="text-white/85 text-xs sm:text-sm mb-4">{{ $bannerData['description'] }}</p>
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

            <!-- Resumen de filtros activos (fuera del AJAX para persistir) -->
            <div id="activeFilters" class="hidden flex flex-wrap items-center gap-2 mb-4">
                <span class="text-helin-text text-sm mr-1">Filtros seleccionados:</span>
                <div id="activeFiltersChips" class="flex flex-wrap items-center gap-2"></div>
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
<script src="@minAsset('helin/js/catalogo.js')"></script>
@endpush
