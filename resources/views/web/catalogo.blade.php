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
    $products = $productsQuery->orderBy('created_at', 'desc')->paginate(24)->withQueryString();
@endphp

<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filtros -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="space-y-6">
                @include('web.components.breadcrumb', [
                    'attributes' => 'class="text-sm text-helin-text mb-6"',
                    'items' => [
                        ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="hover:text-turquesa"'],
                        ['label' => 'Productos', 'spanAttributes' => 'class="text-turquesa font-medium"']
                    ],
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
            <div class="bg-turquesa rounded-xl p-4 sm:p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10 max-w-full sm:max-w-[65%]">
                    <h1 class="text-lg sm:text-2xl lg:text-3xl text-white mb-1 sm:mb-2">{{ $currentCategory->name }}</h1>
                    <p class="text-white/90 text-xs sm:text-base">{{ $currentCategory->seo_description ?? $currentCategory->description ?? 'Explora nuestra selección de ' . $currentCategory->name . ' de alta calidad para profesionales odontológicos.' }}</p>
                </div>
            </div>
            @endif

            <!-- Indicador de carga -->
            <div id="catalogLoading" class="hidden text-center py-4">
                <i class="fas fa-spinner fa-spin text-turquesa text-2xl"></i>
            </div>

            <!-- Contenedor AJAX -->
            <div id="productsContent">
                @include('web.partials.product-results', ['products' => $products])
            </div>

        </div>
    </div>
</main>

@include('web.partials.beneficios')
@endsection

@push('scripts')
<script src="{{ asset('helin/js/catalogo.js') }}"></script>
@endpush
