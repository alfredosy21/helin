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
                <h3 class="font-semibold text-helin-heading mb-3">Dimensiones</h3>
                <select id="sizeSelector" aria-label="Dimensiones" onchange="updatePriceBySize(this.value)"
                    class="w-full sm:w-56 h-9 px-3 rounded-lg border border-gray-300 bg-white text-sm text-helin-heading outline-none cursor-pointer focus:ring-1 focus:ring-turquesa/30 focus:border-turquesa">
                    @foreach(['Ø3.3 mm','Ø4.1 mm','Ø4.8 mm'] as $si => $size)
                        <option value="{{ $size }}" {{ $si === 0 ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <script>
            function updatePriceBySize(size) {
                // Precios base según dimensión (ajustar según tus datos reales)
                const sizePrices = {
                    'Ø3.3 mm': {
                        base: @json($product->price),
                        sale: @json($product->sale_price ?? null)
                    },
                    'Ø4.1 mm': {
                        base: @json($product->price * 1.15), // 15% más caro
                        sale: @json(($product->sale_price ?? $product->price) * 1.15)
                    },
                    'Ø4.8 mm': {
                        base: @json($product->price * 1.25), // 25% más caro
                        sale: @json(($product->sale_price ?? $product->price) * 1.25)
                    }
                };

                const priceInfo = sizePrices[size] || sizePrices['Ø3.3 mm'];
                const currentPriceEl = document.getElementById('currentPrice');
                const oldPriceEl = document.getElementById('oldPrice');
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

                    // Actualizar data-price del botón de carrito
                    if (cartButton) {
                        cartButton.setAttribute('data-price', priceInfo.sale.toFixed(2));
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
                    data-price="{{ $product->price }}"
                    data-image="{{ asset('images/im3.png') }}">
                    <i class="fas fa-cart-plus mr-2"></i>Añadir al carrito
                </button>
            </div>

            <!-- Metadatos del producto -->
            <div class="mt-6 space-y-3">
                @if($product->sku)
                    <div class="flex flex-wrap items-center gap-1.5 text-sm">
                        <span class="font-bold text-helin-heading">SKU:</span>
                        <span class="text-helin-heading/90">{{ $product->sku }}</span>
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

    <!-- Sección de Especificaciones + Widget Soporte -->
    <div class="flex flex-col lg:flex-row gap-8 mb-12">
        <!-- Especificaciones -->
        <div class="lg:w-2/3">
            <h2 class="text-2xl font-bold text-helin-heading mb-6 pb-4 border-b border-helin-border">Especificaciones</h2>
            <div class="prose max-w-none leading-relaxed text-helin-text">
                <table class="w-full text-sm border-collapse">
                    <tbody>
                        @foreach([
                            'Material'          => 'Titanio Grado 5',
                            'Uso clínico'       => 'Osteosíntesis y fijación ósea odontológica',
                            'Compatibilidad'    => 'Placas quirúrgicas y sistemas de fijación',
                            'Esterilización'    => 'Autoclave 134°C',
                            'Propiedades'       => 'Alta resistencia mecánica, estabilidad estructural y biocompatibilidad',
                            'Certificación'     => 'ISO 13485',
                            'Origen'            => 'Importado',
                        ] as $key => $value)
                        <tr class="border-b border-helin-border">
                            <td class="py-3 pr-6 font-medium text-helin-heading w-1/3">{{ $key }}</td>
                            <td class="py-3 text-helin-text">{{ $value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Widget Soporte -->
        <div class="lg:w-1/3 ml-auto">
            <div class="bg-white rounded-xl overflow-hidden">
                <div class="flex flex-col items-center pt-4 px-4">
                    <img src="{{ asset('images/atencion_cliente.png') }}" alt="Atención al cliente Helin" class="h-auto object-cover" style="width: 60%;">
                    <div class="w-full mt-3 mb-4" style="width: 60%;">
                        <a href="https://api.whatsapp.com/send/?phone=584244669150&text=Hola%2C+estoy+interesado+en+productos+Helin+y+me+gustar%C3%ADa+recibir+asesor%C3%ADa+de+un+ejecutivo+comercial.&type=phone_number&app_absent=0" target="_blank" rel="noopener noreferrer" class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-semibold py-2 rounded-full transition-colors flex items-center justify-center gap-2 text-[11px] sm:text-sm">
                            <i class="fab fa-whatsapp text-base"></i>
                            <span>Chatear con ejecutivo</span>
                        </a>
                    </div>
                </div>
                <div class="h-3"></div>
            </div>
        </div>
    </div>

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
