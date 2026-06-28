@extends('web.layouts.app')

@section('title', 'Catálogo de Productos - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filtros -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="space-y-6">
                @include('web.components.breadcrumb', [
                    'attributes' => 'class="text-sm text-helin-text mb-6"',
                    'items' => [
                        ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="hover:text-turquesa"'],
                        ['label' => 'Categorías', 'url' => route('catalogo'), 'linkAttributes' => 'class="hover:text-turquesa"'],
                        ['label' => 'Todos los productos', 'spanAttributes' => 'class="text-turquesa font-medium"']
                    ],
                    'separatorAttributes' => 'class="text-helin-text mx-1"'
                ])

                <!-- Especialidad -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Especialidad</h4>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Cirugía Bucal</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Cirugía Maxilofacial</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Periodoncia</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Ortodoncia</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Endodoncia</span>
                        </label>
                    </div>
                </div>

                <hr class="border-helin-border my-4">

                <!-- Categorías con badges -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Categorías</h4>
                    <div class="space-y-2">
                        @php
                            $categories = \App\Models\Category::active()->ordered()->get();
                        @endphp
                        @foreach($categories as $cat)
                            @php
                                $productCount = $cat->products()->where('is_active', true)->count();
                            @endphp
                            <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                                <span class="text-helin-text text-sm">{{ $cat->name }}</span>
                                <span class="w-6 h-6 rounded-full bg-helin-soft text-helin-text text-xs flex items-center justify-center">{{ $productCount }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <hr class="border-helin-border my-4">

                <!-- Marcas -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Marcas</h4>
                    <div class="space-y-2">
                        @php
                            $brands = \App\Models\Brand::active()->ordered()->get();
                        @endphp
                        @foreach($brands as $brand)
                            <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                                <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                                <span class="ml-2 text-helin-text text-sm">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-helin-border my-4">

                <!-- Tags -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Tags</h4>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Destacados</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">Ofertas</span>
                        </label>
                    </div>
                </div>

                <hr class="border-helin-border my-4">

                <!-- Filtros de categoría -->
                <div class="mb-4">
                    <h4 class="font-semibold text-helin-heading mb-3 text-sm">Filtros de categoría</h4>
                    <div class="space-y-2">
                        @foreach(['Nuevo', 'Stock', 'Premium', 'Básico'] as $filtro)
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-helin-border">
                            <span class="ml-2 text-helin-text text-sm">{{ $filtro }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <!-- Grid Productos -->
        <div class="flex-1">
            <!-- Banner -->
            <div class="bg-turquesa rounded-xl p-4 sm:p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="relative z-10 max-w-full sm:max-w-[65%]">
                    <h1 class="text-lg sm:text-2xl lg:text-3xl text-white mb-1 sm:mb-2">Catálogo por Categoría</h1>
                    <p class="text-white/90 text-xs sm:text-base">Encuentra los mejores productos odontológicos organizados por especialidad</p>
                </div>
            </div>

            <!-- Barra de Ordenamiento -->
            <div class="bg-helin-soft rounded-lg pl-3 sm:pl-4 py-3 sm:py-4 pr-0 mb-6">
                <div class="flex items-center justify-between">
                    @php
                        $totalProducts = \App\Models\Product::where('is_active', true)->count();
                    @endphp
                    <span class="text-helin-text text-sm">Mostrando <strong>{{ $totalProducts }}</strong> productos</span>
                    <select class="border rounded-lg px-3 py-2 bg-white text-helin-heading text-sm ml-auto">
                        <option>Ordenar por: Relevancia</option>
                        <option>Precio: Menor a Mayor</option>
                        <option>Precio: Mayor a Menor</option>
                    </select>
                </div>
            </div>

            <!-- Grid Productos -->
            @php
                $products = \App\Models\Product::with(['category', 'brand'])
                    ->where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->paginate(24);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($products as $product)
                    @php
                        $badge = '';
                        if($product->is_new) $badge = 'Nuevo';
                        elseif($product->is_on_sale) $badge = 'Oferta';
                    @endphp
                    @include('web.components.product-card', [
                        'productImage' => asset('storage/products/73432-21300078.webp'),
                        'productName' => $product->name,
                        'productBrand' => $product->brand->name ?? 'Helin',
                        'productPrice' => $product->price,
                        'productOldPrice' => $product->is_on_sale ? $product->price : null,
                        'productBadge' => $badge,
                        'productLink' => route('producto', ['id' => $product->id])
                    ])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                <nav class="flex items-center gap-1 sm:gap-2">
                    @if($products->hasPages())
                        @if($products->onFirstPage())
                            <button class="px-3 sm:px-4 py-2 border rounded-full text-helin-text hover:bg-helin-soft disabled:opacity-50 text-sm" disabled>
                                <i class="fas fa-chevron-left mr-1"></i><span class="hidden sm:inline">Anterior</span>
                            </button>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 sm:px-4 py-2 border rounded-full text-helin-text hover:bg-helin-soft text-sm">
                                <i class="fas fa-chevron-left mr-1"></i><span class="hidden sm:inline">Anterior</span>
                            </a>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <button class="w-10 h-10 sm:px-4 sm:py-2 bg-turquesa text-white rounded-full text-sm font-medium">{{ $page }}</button>
                            @elseif($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 2)
                                <a href="{{ $url }}" class="w-10 h-10 sm:px-4 sm:py-2 border rounded-full text-helin-text hover:bg-helin-soft text-sm">{{ $page }}</a>
                            @elseif(abs($page - $products->currentPage()) == 3)
                                <span class="text-helin-text px-1 hidden sm:inline">...</span>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 sm:px-4 py-2 border rounded-full text-helin-text hover:bg-helin-soft text-sm">
                                <span class="hidden sm:inline">Siguiente</span><i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        @else
                            <button class="px-3 sm:px-4 py-2 border rounded-full text-helin-text hover:bg-helin-soft text-sm disabled:opacity-50" disabled>
                                <span class="hidden sm:inline">Siguiente</span><i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        @endif
                    @endif
                </nav>
            </div>
        </div>
    </div>
</main>

@include('web.partials.beneficios')
@endsection
