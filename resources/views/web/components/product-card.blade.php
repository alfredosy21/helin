@php
    $formattedPrice = is_numeric($productPrice ?? null) ? '$' . number_format($productPrice, 2) : ($productPrice ?? '');
    $formattedOldPrice = is_numeric($productOldPrice ?? null) ? '$' . number_format($productOldPrice, 2) : '';
@endphp

<div class="bg-white rounded-xl p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-transform flex flex-col h-full cursor-pointer">
    <!-- Imagen -->
    <div class="relative flex-1 flex items-center justify-center min-h-[200px] mb-4">
        @if(!empty($productBadge))
            <span class="absolute top-2.5 left-2.5 bg-turquesa text-white text-xs font-semibold uppercase px-3 py-1 rounded-full z-10">
                {{ $productBadge }}
            </span>
        @endif
        <img src="{{ $productImage ?? '' }}"
             alt="{{ $productName ?? '' }}"
             class="max-h-[180px] object-contain">
    </div>

    <!-- Contenido -->
    <h3 class="font-bold text-[#1a202c] text-base mb-1 text-center">
        {{ $productName ?? '' }}
    </h3>

    <p class="text-[#718096] text-sm font-normal mb-3 text-center">{{ $productBrand ?? '' }}</p>

    <!-- Precio -->
    <div class="flex items-center justify-center gap-2 mb-4 mt-auto">
        @if(!empty($formattedOldPrice))
            <span class="text-[#a0aec0] text-sm line-through">{{ $formattedOldPrice }}</span>
        @endif
        <span class="text-[#15aabf] font-bold text-lg">{{ $formattedPrice }}</span>
    </div>

    <!-- Botón -->
    <button class="w-full bg-[#15aabf] hover:bg-[#0e8c9c] text-white font-semibold uppercase py-3 rounded-[30px] transition-colors">
        Añadir al carrito +
    </button>
</div>
