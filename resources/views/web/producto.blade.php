@extends('web.layouts.app')

@section('title', $product->name . ' - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'class="text-sm mb-6"',
        'items' => [
            ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Catálogo', 'url' => route('catalogo'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => $product->category->name ?? 'Categoría', 'url' => route('catalogo'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => $product->name, 'spanAttributes' => 'class="text-turquesa font-medium"']
        ],
        'separatorAttributes' => 'class="text-helin-text mx-2"'
    ])

    <div class="flex flex-col lg:flex-row gap-8 mb-12">
        <!-- Imagen del Producto -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                <img src="{{ asset('storage/products/73432-21300078.webp') }}" alt="{{ $product->name }}" class="w-full h-96 object-contain">
            </div>
            <div class="grid grid-cols-4 gap-3">
                <button class="border-2 border-turquesa rounded-lg overflow-hidden p-2">
                    <img src="{{ asset('storage/products/73432-21300078.webp') }}" class="w-full h-16 object-contain">
                </button>
                <button class="border border-helin-border rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="{{ asset('storage/products/73432-21300078.webp') }}" class="w-full h-16 object-contain">
                </button>
                <button class="border border-helin-border rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="{{ asset('storage/products/73432-21300078.webp') }}" class="w-full h-16 object-contain">
                </button>
                <button class="border border-helin-border rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="{{ asset('storage/products/73432-21300078.webp') }}" class="w-full h-16 object-contain">
                </button>
            </div>
        </div>

        <!-- Info del Producto -->
        <div class="lg:w-1/2">
            @if($product->is_new)
                <span class="bg-turquesa/10 text-turquesa text-xs font-semibold px-3 py-1 rounded-full">Nuevo</span>
            @elseif($product->is_on_sale)
                <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">Oferta</span>
            @endif
            <h1 class="text-3xl text-helin-heading mt-3 mb-6">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mb-6">
                @if($product->is_on_sale && $product->sale_price)
                    <span class="text-2xl font-bold text-turquesa" style="font-size: 24px;">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-xl text-helin-text" style="text-decoration: line-through;">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-2xl font-bold text-turquesa" style="font-size: 24px;">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="text-helin-text mb-6">{{ $product->description }}</p>

            <!-- Selector de Tamaño (Chips) -->
            <div class="mb-6">
                <h3 class="font-semibold text-helin-heading mb-3">Tamaño</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="px-2 py-1 text-xs border-2 border-turquesa bg-turquesa/10 text-turquesa rounded-full font-medium transition-all">Ø3.3 mm</button>
                    <button class="px-2 py-1 text-xs border border-helin-border text-helin-heading rounded-full hover:border-turquesa transition-all">Ø4.1 mm</button>
                    <button class="px-2 py-1 text-xs border border-helin-border text-helin-heading rounded-full hover:border-turquesa transition-all">Ø4.8 mm</button>
                </div>
            </div>

            <!-- Cantidad y Botón -->
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-6">
                <div class="flex items-center rounded-full bg-turquesa/10 overflow-hidden px-2.5" style="padding: 5px 10px;">
                    <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">-</button>
                    <input type="number" value="1" min="1" class="w-20 text-center outline-none bg-transparent" style="padding: 5px 10px;">
                    <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="this.previousElementSibling.value++">+</button>
                </div>
                <button class="bg-turquesa hover:bg-turquesa-dark text-white font-semibold py-3 px-8 rounded-full uppercase transition-colors w-full sm:w-auto">
                    <i class="fas fa-cart-plus mr-2"></i>Añadir al carrito
                </button>
            </div>
        </div>
    </div>

    <!-- Sección Central: Tabs + Widget Soporte -->
    <div class="flex flex-col lg:flex-row gap-8 mb-12">
        <!-- Tabs de Información -->
        <div class="lg:w-2/3">
            <!-- Tabs Header -->
            <div class="border-b border-helin-border mb-6">
                <nav class="flex gap-8">
                    <button class="pb-4 border-b-2 border-turquesa text-turquesa font-bold text-xl">
                        Descripción
                    </button>
                    <button class="pb-4 border-b-2 border-transparent text-helin-heading hover:text-helin-heading font-bold text-xl">
                        Especificaciones
                    </button>
                </nav>
            </div>

            <!-- Contenido Tab Descripción -->
            <div class="prose max-w-none leading-relaxed text-helin-text">
                <p class="mb-4">
                    {{ $product->description }}
                </p>
                @if($product->clinical_specs)
                    @php
                        $specs = json_decode($product->clinical_specs, true);
                    @endphp
                    @if($specs)
                        <h4 class="text-helin-heading mb-2">Especificaciones clínicas:</h4>
                        <ul class="list-disc list-inside space-y-1 mb-4">
                            @foreach($specs as $key => $value)
                                <li>{{ ucfirst($key) }}: {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>

        <!-- Widget Soporte -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-xl py-6 lg:py-12 px-12 min-h-[400px] flex flex-col justify-end lg:mx-5">
                <!-- Botón Chat -->
                <a href="https://wa.me/584241232025" target="_blank" class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-semibold py-3 rounded-full mb-3 transition-colors flex items-center justify-between px-6">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>Chatear con ejecutivo</span>
                    <span class="w-6"></span>
                </a>

                <!-- Teléfono -->
                <a href="https://wa.me/584241232025" target="_blank" class="flex items-center justify-between text-helin-heading hover:text-turquesa py-2 transition-colors border border-helin-border rounded-full hover:border-turquesa px-6">
                    <i class="fab fa-whatsapp text-turquesa text-2xl"></i>
                    <span class="font-bold text-turquesa">+ 584241232025</span>
                    <span class="w-6"></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Productos Relacionados -->
    <section class="mb-12">
        <div class="mb-6">
            <h2 class="text-xl text-helin-heading mb-1">Productos Relacionados</h2>
            <p class="text-helin-text text-sm mb-3">Conoce los productos relacionados para ti</p>
            <a href="{{ route('catalogo') }}" class="text-turquesa font-semibold border-b border-turquesa pb-0.5">Ver todos los productos <i class="fas fa-arrow-right ml-1 text-turquesa"></i></a>
        </div>
        @php
            $relatedProducts = \App\Models\Product::where('category_id', $product->category_id ?? null)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($relatedProducts->count() > 0)
                @foreach($relatedProducts as $related)
                    @php
                        $badge = '';
                        if($related->is_new) $badge = 'Nuevo';
                        elseif($related->is_on_sale) $badge = 'Oferta';
                    @endphp
                    @include('web.components.product-card', [
                        'productImage' => asset('storage/products/73432-21300078.webp'),
                        'productName' => $related->name,
                        'productBrand' => $related->brand->name ?? 'Helin',
                        'productPrice' => $related->price,
                        'productOldPrice' => $related->is_on_sale ? $related->price : null,
                        'productBadge' => $badge,
                        'productLink' => route('producto', ['slug' => $related->slug])
                    ])
                @endforeach
            @else
                @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Biomaterial Óseo Bio-Oss', 'productBrand' => 'Geistlich', 'productPrice' => 149.00, 'productBadge' => '', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Membrana Colágeno Bio-Gide', 'productBrand' => 'Geistlich', 'productPrice' => 89.00, 'productBadge' => '', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Kit de Cirugía Implantológica', 'productBrand' => 'Helin', 'productPrice' => 199.00, 'productBadge' => 'Nuevo', 'productLink' => route('catalogo')])
                @include('web.components.product-card', ['productImage' => asset('storage/products/73432-21300078.webp'), 'productName' => 'Suturas Resorbibles 4-0', 'productBrand' => 'Johnson & Johnson', 'productPrice' => 45.00, 'productBadge' => '', 'productLink' => route('catalogo')])
            @endif
        </div>
    </section>
</main>

@include('web.partials.beneficios')
@endsection
