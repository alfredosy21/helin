@extends('web.layouts.app')

@section('title', $product->name . ' - Helin')
@section('meta-description', $product->seo_description ?? $product->description ?? 'Compra ' . $product->name . ' en Helin. ' . ($product->category->name ?? '') . ' de alta calidad para profesionales odontológicos. Envíos a todo Venezuela.')
@section('meta-keywords', $product->seo_keywords ?? ($product->name . ', ' . ($product->category->name ?? '') . ', implantes dentales, material dental, helin, productos odontológicos'))
@section('og-type', 'product')
@section('og-image', $product->image ? asset('storage/' . $product->image) : asset('images/helin-product-default.jpg'))
@section('twitter-card', 'product')

@push('styles')
<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; appearance: textfield; }
.qty-btn:hover { background-color: #6BC2C3 !important; color: #ffffff !important; }
</style>
@endpush

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'text-sm mb-6',
        'items' => [
            ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => $product->category->name ?? 'Categoría', 'url' => route('catalogo', ['category' => $product->category->slug ?? '']), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => $product->name, 'spanAttributes' => 'class="text-turquesa font-medium"']
        ],
        'separatorAttributes' => 'class="text-helin-text mx-2"'
    ])

    <div class="flex flex-col lg:flex-row items-start gap-8 mt-4 mb-12">
        <!-- Imagen del Producto -->
        <div class="lg:w-1/2">
            @php
                $galleryImages = [
                    asset('images/im3.png'),
                    asset('images/im4.png'),
                    asset('images/im5.png'),
                    asset('images/im6.png'),
                ];
            @endphp
            <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
                <div class="w-full" style="aspect-ratio: 1 / 1;">
                    <img id="mainProductImage" src="{{ $galleryImages[0] }}" alt="{{ $product->name }}" class="w-full h-full object-contain" loading="eager">
                </div>
            </div>
            <div class="grid grid-cols-4 gap-3">
                @foreach($galleryImages as $i => $img)
                <button onclick="document.getElementById('mainProductImage').src='{{ $img }}'; document.querySelectorAll('.thumb-btn').forEach(b=>b.classList.replace('border-turquesa','border-helin-border')); this.classList.replace('border-helin-border','border-turquesa');" class="thumb-btn {{ $i === 0 ? 'border-2 border-turquesa' : 'border border-helin-border hover:border-turquesa' }} rounded-lg overflow-hidden p-2 bg-white transition-all">
                    <div class="w-full" style="aspect-ratio: 1 / 1;">
                        <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Info del Producto -->
        <div class="lg:w-1/2">
            <h1 class="text-3xl text-helin-heading mb-6">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mb-6" id="priceDisplay">
                @if($product->is_on_sale && $product->sale_price)
                    <span class="text-lg text-helin-text line-through opacity-70" id="oldPrice">${{ number_format($product->price, 2) }}</span>
                    <span class="text-xl font-bold text-turquesa" id="currentPrice">${{ number_format($product->sale_price, 2) }}</span>
                @else
                    <span class="text-xl font-bold text-turquesa" id="currentPrice">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="text-helin-text mb-6">{{ $product->description }}</p>

            <!-- Selector de Dimensiones -->
            <div class="mb-6">
                <h3 class="font-semibold text-helin-heading mb-1">Dimensiones</h3>
                <p class="text-[13px] font-normal mb-1" style="color: #626772;">Seleccione una dimensión</p>
                <select id="sizeSelector" aria-label="Dimensiones" onchange="updatePriceBySize(this.value)"
                    class="w-full sm:w-56 h-9 pl-3 pr-9 rounded-lg border border-gray-300 bg-white text-sm text-helin-heading outline-none cursor-pointer focus:ring-1 focus:ring-turquesa/30 focus:border-turquesa appearance-none"
                    style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%23123F4A%22><path fill-rule=%22evenodd%22 d=%22M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z%22 clip-rule=%22evenodd%22/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 14px;"
                    @foreach(['Ø3.3 mm','Ø4.1 mm','Ø4.8 mm'] as $si => $size)
                        <option value="{{ $size }}" {{ $si === 0 ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <script>
            function updatePriceBySize(size) {
                // Precios y SKU por dimensión (ajustar según tus datos reales)
                const baseSku = @json($product->sku ?? '');
                const sizePrices = {
                    'Ø3.3 mm': {
                        base: @json($product->price),
                        sale: @json($product->sale_price ?? null),
                        sku: baseSku ? baseSku + '-33' : ''
                    },
                    'Ø4.1 mm': {
                        base: @json($product->price * 1.15), // 15% más caro
                        sale: @json(($product->sale_price ?? $product->price) * 1.15),
                        sku: baseSku ? baseSku + '-41' : ''
                    },
                    'Ø4.8 mm': {
                        base: @json($product->price * 1.25), // 25% más caro
                        sale: @json(($product->sale_price ?? $product->price) * 1.25),
                        sku: baseSku ? baseSku + '-48' : ''
                    }
                };

                const priceInfo = sizePrices[size] || sizePrices['Ø3.3 mm'];
                const currentPriceEl = document.getElementById('currentPrice');
                const oldPriceEl = document.getElementById('oldPrice');
                const skuEl = document.getElementById('productSkuValue');
                const cartButton = document.querySelector('[data-cart-add]');

                // Actualizar precios con animación
                currentPriceEl.style.opacity = '0.5';
                if (oldPriceEl) oldPriceEl.style.opacity = '0.5';

                setTimeout(() => {
                    // Actualizar precio actual
                    currentPriceEl.textContent = '$' + priceInfo.sale.toFixed(2);

                    // Actualizar precio anterior si hay oferta
                    if (priceInfo.sale < priceInfo.base) {
                        if (!oldPriceEl) {
                            // Crear elemento de precio anterior si no existe
                            const priceDisplay = document.getElementById('priceDisplay');
                            const newOldPrice = document.createElement('span');
                            newOldPrice.id = 'oldPrice';
                            newOldPrice.className = 'text-lg text-helin-text line-through opacity-70';
                            priceDisplay.insertBefore(newOldPrice, currentPriceEl);
                            oldPriceEl = newOldPrice;
                        }
                        oldPriceEl.textContent = '$' + priceInfo.base.toFixed(2);
                        oldPriceEl.style.display = 'inline';
                    } else if (oldPriceEl) {
                        // Ocultar precio anterior si no hay oferta
                        oldPriceEl.style.display = 'none';
                    }

                    // Actualizar SKU mostrado según la dimensión seleccionada
                    if (skuEl && priceInfo.sku) {
                        skuEl.textContent = priceInfo.sku;
                    }

                    // Actualizar datos del botón de carrito (precio, SKU y dimensión)
                    if (cartButton) {
                        cartButton.setAttribute('data-price', priceInfo.sale.toFixed(2));
                        if (priceInfo.sku) cartButton.setAttribute('data-sku', priceInfo.sku);
                        cartButton.setAttribute('data-dimension', size);
                    }

                    // Restaurar opacidad con animación
                    currentPriceEl.style.opacity = '1';
                    if (oldPriceEl) oldPriceEl.style.opacity = '0.7';
                }, 200);
            }
            </script>

            <!-- Cantidad y Botón -->
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-6" data-cart-context>
                <div class="flex items-center rounded-full gap-1 px-1.5" style="background-color: rgba(107,194,195,0.45); height: 38px;">
                    <button class="qty-btn w-7 h-7 bg-white rounded-full flex items-center justify-center transition-all text-sm font-bold leading-none flex-shrink-0" style="color:#6BC2C3;" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">−</button>
                    <input type="number" value="1" min="1" class="w-8 text-center outline-none bg-transparent text-sm font-semibold" style="color:#9ca3af; -moz-appearance:textfield; appearance:textfield;" data-cart-qty onwheel="return false;">
                    <button class="qty-btn w-7 h-7 bg-white rounded-full flex items-center justify-center transition-all text-sm font-bold leading-none flex-shrink-0" style="color:#6BC2C3;" onclick="this.previousElementSibling.value++">+</button>
                </div>
                <button
                    class="bg-turquesa hover:bg-turquesa-dark text-white font-semibold px-6 rounded-full uppercase transition-colors w-full sm:w-auto text-xs tracking-wide" style="height: 38px;"
                    data-cart-add
                    data-slug="{{ $product->slug }}"
                    data-name="{{ $product->name }}"
                    data-brand="{{ $product->brand->name ?? 'Helin' }}"
                    data-price="{{ $product->is_on_sale && $product->sale_price ? $product->sale_price : $product->price }}"
                    data-sku="{{ $product->sku ? $product->sku . '-33' : '' }}"
                    data-dimension="Ø3.3 mm"
                    data-image="{{ asset('images/im3.png') }}">
                    <i class="fas fa-cart-plus mr-2"></i>Añadir al carrito
                </button>
            </div>

            <!-- Metadatos del producto -->
            <div class="mt-6 space-y-3">
                @if($product->sku)
                    <div class="flex flex-wrap items-center gap-1.5 text-sm">
                        <span class="font-bold text-helin-heading">SKU:</span>
                        <span class="text-helin-heading/90" id="productSkuValue">{{ $product->sku }}-33</span>
                    </div>
                @endif

                @if($product->category)
                    <div class="flex flex-wrap items-center gap-1.5 text-sm">
                        <span class="font-bold text-helin-heading">Categoría:</span>
                        <a href="{{ route('catalogo', ['category' => $product->category->slug]) }}" class="text-helin-heading/90 hover:text-helin-heading hover:underline">
                            {{ $product->category->name }}
                        </a>
                    </div>
                @endif

                @php
                    $productTags = [];

                    if($product->is_new) {
                        $productTags[] = ['label' => 'Nuevo', 'filter' => 'new'];
                    }

                    if($product->is_featured) {
                        $productTags[] = ['label' => 'Destacado', 'filter' => 'featured'];
                    }

                    if($product->is_on_sale) {
                        $productTags[] = ['label' => 'Oferta', 'filter' => 'on_sale'];
                    }

                    if($product->brand) {
                        $productTags[] = ['label' => $product->brand->name, 'filter' => 'brand:' . $product->brand->slug];
                    }

                    if($product->material) {
                        $productTags[] = ['label' => $product->material, 'filter' => 'material:' . strtolower($product->material)];
                    }

                    if($product->is_biomaterial ?? false) {
                        $productTags[] = ['label' => 'Biomateriales', 'filter' => 'biomaterial'];
                    }
                @endphp

                @if(count($productTags) > 0)
                    <div class="flex flex-wrap items-center gap-1.5 text-sm">
                        <span class="font-bold text-helin-heading">Tags:</span>
                        <span class="text-helin-text">
                            @foreach($productTags as $i => $tag)
                                @php
                                    $filter = $tag['filter'];
                                    if(str_starts_with($filter, 'category:')) {
                                        $tagUrl = route('catalogo', ['category' => substr($filter, 9)]);
                                    } elseif(str_starts_with($filter, 'brand:')) {
                                        $tagUrl = route('catalogo', ['brand' => substr($filter, 6)]);
                                    } elseif(str_starts_with($filter, 'material:')) {
                                        $tagUrl = route('catalogo', ['material' => substr($filter, 9)]);
                                    } else {
                                        $tagUrl = route('catalogo', ['tag' => $filter]);
                                    }
                                @endphp
                                <a href="{{ $tagUrl }}" class="text-helin-heading/90 hover:text-helin-heading hover:underline">{{ $tag['label'] }}</a>{{ $i < count($productTags) - 1 ? ', ' : '' }}
                            @endforeach
                        </span>
                    </div>
                @endif

                <div>
                    <a href="{{ asset('images/ficha_test.pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center h-9 px-4 rounded-full border border-helin-heading/90 bg-white text-sm font-medium text-helin-heading/90 transition-colors hover:text-helin-heading hover:border-helin-heading hover:bg-turquesa/10">
                        <i class="fas fa-file-pdf text-base mr-2"></i>
                        Descargar ficha técnica
                    </a>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-helin-border mb-12">

    <!-- Productos Relacionados -->
    <section class="mb-12">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div class="w-full">
                <h2 class="text-2xl text-helin-heading mb-3">Productos Relacionados</h2>
                <p class="text-helin-text text-sm w-full">Conoce los productos relacionados para ti</p>
            </div>
            <a href="{{ route('catalogo') }}" class="self-start sm:self-auto text-turquesa font-semibold sm:border-b sm:border-turquesa sm:pb-0.5 whitespace-nowrap">Ver todos los productos <i class="fas fa-arrow-right ml-1 text-turquesa"></i></a>
        </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($relatedProducts->count() > 0)
                @foreach($relatedProducts as $ri => $related)
                    @php
                        $badge = '';
                        if($related->is_new) $badge = 'Nuevo';
                        elseif($related->is_on_sale) $badge = 'Oferta';
                        $relatedImg = asset('images/im' . (($ri % 6) + 1) . '.png');
                    @endphp
                    @include('web.components.product-card', [
                        'productImage' => $relatedImg,
                        'productName' => $related->name,
                        'productBrand' => $related->brand->name ?? 'Helin',
                        'productPrice' => $related->price,
                        'productOldPrice' => $related->is_on_sale ? $related->price : null,
                        'productBadge' => $badge,
                        'productLink' => route('producto', ['slug' => $related->slug]),
                        'productSlug' => $related->slug,
                    ])
                @endforeach
            @else
                @include('web.components.product-card', ['productImage' => asset('images/im1.png'), 'productName' => 'Biomaterial Óseo Bio-Oss', 'productBrand' => 'Geistlich', 'productPrice' => 149.00, 'productBadge' => '', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('images/im2.png'), 'productName' => 'Membrana Colágeno Bio-Gide', 'productBrand' => 'Geistlich', 'productPrice' => 89.00, 'productBadge' => '', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('images/im3.png'), 'productName' => 'Kit de Cirugía Implantológica', 'productBrand' => 'Helin', 'productPrice' => 199.00, 'productBadge' => 'Nuevo', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('images/im4.png'), 'productName' => 'Suturas Resorbibles 4-0', 'productBrand' => 'Johnson & Johnson', 'productPrice' => 45.00, 'productBadge' => '', 'productLink' => route('catalogo')])
            @endif
        </div>
    </section>
</main>

@include('web.partials.beneficios')
@endsection
