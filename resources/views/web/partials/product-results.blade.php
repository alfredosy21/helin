<div class="flex items-center justify-between bg-helin-soft rounded-lg pl-3 sm:pl-4 py-3 sm:py-4 pr-3 mb-6">
    <span class="text-helin-text text-sm" id="productCount">
        Mostrando <strong>{{ $products->total() }}</strong> producto{{ $products->total() != 1 ? 's' : '' }}
    </span>
    <select id="sortSelect" class="border rounded-lg px-3 py-2 bg-white text-helin-heading text-sm ml-auto">
        <option value="recent">Ordenar por: Relevancia</option>
        <option value="price_asc">Precio: Menor a Mayor</option>
        <option value="price_desc">Precio: Mayor a Menor</option>
        <option value="name_asc">Nombre: A-Z</option>
    </select>
</div>

@if($products->count() === 0)
    <div class="text-center py-16">
        <i class="fas fa-search text-4xl text-helin-border mb-4 block"></i>
        <h3 class="text-helin-heading font-semibold text-lg mb-2">No se encontraron productos</h3>
        <p class="text-helin-text text-sm mb-4">Intenta con otros filtros o términos de búsqueda.</p>
        <button id="clearFiltersEmpty" class="bg-turquesa text-white px-6 py-2 rounded-full text-sm hover:bg-turquesa-dark transition">
            Ver todos los productos
        </button>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($products as $product)
            @php
                $badge = '';
                if($product->is_new) $badge = 'Nuevo';
                elseif($product->is_on_sale) $badge = 'Oferta';
            @endphp
            @include('web.components.product-card', [
                'productImage'    => asset('storage/products/73432-21300078.webp'),
                'productName'     => $product->name,
                'productBrand'    => $product->brand->name ?? 'Helin',
                'productPrice'    => $product->price,
                'productOldPrice' => $product->is_on_sale ? $product->price : null,
                'productBadge'    => $badge,
                'productLink'     => route('producto', ['slug' => $product->slug]),
                'productSlug'     => $product->slug,
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
@endif
