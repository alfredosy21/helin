@php
    $formattedPrice = is_numeric($productPrice ?? null) ? '$' . number_format($productPrice, 2) : ($productPrice ?? '');
    $formattedOldPrice = is_numeric($productOldPrice ?? null) ? '$' . number_format($productOldPrice, 2) : '';
@endphp

<div class="bg-white rounded-xl p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-transform flex flex-col h-full cursor-pointer">
    <!-- Imagen -->
    <a href="{{ $productLink ?? '#' }}" class="relative block w-full mb-4">
        @if(!empty($productBadge))
            <span class="absolute top-2.5 left-2.5 bg-turquesa text-white text-xs font-semibold uppercase px-3 py-1 rounded-full z-10">
                {{ $productBadge }}
            </span>
        @endif
        <div class="w-full aspect-square flex items-center justify-center overflow-hidden">
            <img data-src="{{ $productImage ?? '' }}"
                 data-fallback="{{ asset('images/placeholder-product.webp') }}"
                 alt="{{ $productName ?? '' }}"
                 title="{{ $productName ?? '' }}"
                 class="w-full h-full object-contain lazy-image">
        </div>
    </a>

    <!-- Contenido -->
    <a href="{{ $productLink ?? '#' }}" class="hover:text-turquesa transition-colors" title="{{ $productName ?? '' }}">
        <h3 class="text-helin-heading text-base mb-1 text-center line-clamp-2 leading-5">
            {{ $productName ?? '' }}
        </h3>
    </a>

    <p class="text-helin-text text-sm font-normal mb-3 text-center">{{ $productBrand ?? '' }}</p>

    <!-- Precio -->
    <div class="flex items-center justify-center gap-2 mb-4 mt-auto">
        @if(!empty($formattedOldPrice))
            <span class="text-helin-text text-sm line-through">{{ $formattedOldPrice }}</span>
        @endif
        <span class="text-turquesa font-bold text-base">{{ $formattedPrice }}</span>
    </div>

    <!-- Botón -->
    <div data-cart-context>
        <button
            class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-semibold text-sm py-3 rounded-[30px] transition-colors"
            data-cart-add
            data-slug="{{ $productSlug ?? '' }}"
            data-name="{{ $productName ?? '' }}"
            data-brand="{{ $productBrand ?? '' }}"
            data-price="{{ $productPrice ?? 0 }}"
            data-image="{{ $productImage ?? '' }}">
            Añadir al carrito +
        </button>
    </div>
</div>
