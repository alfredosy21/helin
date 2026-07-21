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

            <!-- Selector de Tamaño (Dropdown Custom) -->
            <div class="mb-6">
                <h3 class="font-semibold text-helin-heading mb-3">Tamaño</h3>
                <div class="relative w-48" id="sizeDropdown">
                    <!-- Trigger -->
                    <button type="button" id="sizeDropdownTrigger"
                        class="w-full flex items-center justify-between border border-helin-border rounded-lg px-4 py-2.5 text-sm text-helin-heading bg-white cursor-pointer hover:border-turquesa transition-colors focus:outline-none"
                        onclick="toggleSizeDropdown()">
                        <span id="sizeDropdownLabel">Ø3.3 mm</span>
                        <svg id="sizeDropdownArrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <!-- Options -->
                    <div id="sizeDropdownMenu" class="hidden absolute z-50 w-full mt-1 bg-white border border-helin-border rounded-lg shadow-lg overflow-hidden">
                        @foreach(['Ø3.3 mm','Ø4.1 mm','Ø4.8 mm'] as $si => $size)
                        <div onclick="selectSize(this, '{{ $size }}')"
                            class="size-option px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $si === 0 ? 'bg-turquesa text-white font-semibold' : 'text-helin-heading hover:bg-turquesa/10 hover:text-turquesa' }}">
                            {{ $size }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <script>
            function toggleSizeDropdown() {
                const menu = document.getElementById('sizeDropdownMenu');
                const arrow = document.getElementById('sizeDropdownArrow');
                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
            function selectSize(el, label) {
                document.getElementById('sizeDropdownLabel').textContent = label;
                document.querySelectorAll('.size-option').forEach(o => {
                    o.classList.remove('bg-turquesa','text-white','font-semibold');
                    o.classList.add('text-helin-heading','hover:bg-turquesa/10','hover:text-turquesa');
                });
                el.classList.add('bg-turquesa','text-white','font-semibold');
                el.classList.remove('text-helin-heading','hover:bg-turquesa/10','hover:text-turquesa');
                document.getElementById('sizeDropdownMenu').classList.add('hidden');
                document.getElementById('sizeDropdownArrow').classList.remove('rotate-180');

                // Actualizar precio según el tamaño seleccionado
                updatePriceBySize(label);
            }

            function updatePriceBySize(size) {
                // Precios base según tamaño (ajustar según tus datos reales)
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
            document.addEventListener('click', function(e) {
                if (!document.getElementById('sizeDropdown').contains(e.target)) {
                    document.getElementById('sizeDropdownMenu').classList.add('hidden');
                    document.getElementById('sizeDropdownArrow').classList.remove('rotate-180');
                }
            });
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

            <!-- Tags dinámicos del producto -->
            <div class="mt-6">
                <div class="flex flex-wrap gap-2" id="productTags">
                    @php
                        $productTags = [];

                        // Tags de estado del producto
                        if($product->is_new) {
                            $productTags[] = [
                                'type' => 'status',
                                'label' => 'Nuevo',
                                'icon' => 'fas fa-star',
                                'color' => 'turquesa',
                                'filter' => 'new'
                            ];
                        }

                        if($product->is_featured) {
                            $productTags[] = [
                                'type' => 'status',
                                'label' => 'Destacado',
                                'icon' => 'fas fa-award',
                                'color' => 'yellow',
                                'filter' => 'featured'
                            ];
                        }

                        if($product->is_on_sale) {
                            $productTags[] = [
                                'type' => 'status',
                                'label' => 'Oferta',
                                'icon' => 'fas fa-tag',
                                'color' => 'red',
                                'filter' => 'on_sale'
                            ];
                        }

                        // Tags de categoría y marca
                        if($product->category) {
                            $productTags[] = [
                                'type' => 'category',
                                'label' => $product->category->name,
                                'icon' => 'fas fa-folder',
                                'color' => 'helin',
                                'filter' => 'category:' . $product->category->slug
                            ];
                        }

                        if($product->brand) {
                            $productTags[] = [
                                'type' => 'brand',
                                'label' => $product->brand->name,
                                'icon' => 'fas fa-certificate',
                                'color' => 'helin',
                                'filter' => 'brand:' . $product->brand->slug
                            ];
                        }

                        // Tags adicionales basados en atributos del producto
                        if($product->material) {
                            $productTags[] = [
                                'type' => 'attribute',
                                'label' => $product->material,
                                'icon' => 'fas fa-cube',
                                'color' => 'purple',
                                'filter' => 'material:' . strtolower($product->material)
                            ];
                        }

                        // Tag de biomateriales si aplica
                        if($product->is_biomaterial ?? false) {
                            $productTags[] = [
                                'type' => 'attribute',
                                'label' => 'Biomateriales',
                                'icon' => 'fas fa-leaf',
                                'color' => 'green',
                                'filter' => 'biomaterial'
                            ];
                        }
                    @endphp

                    @foreach($productTags as $tag)
                        <a href="{{ route('catalogo', ['tag' => $tag['filter']]) }}"
                           class="product-tag tag-{{ $tag['type'] }} tag-{{ $tag['color'] }}"
                           data-tag-type="{{ $tag['type'] }}"
                           data-tag-filter="{{ $tag['filter'] }}"
                           onclick="handleTagClick(event, '{{ $tag['filter'] }}')">
                            <i class="{{ $tag['icon'] }} tag-icon"></i>
                            <span class="tag-label">{{ $tag['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <style>
            .product-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                border: 1px solid transparent;
                position: relative;
                overflow: hidden;
            }

            .product-tag::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s ease;
            }

            .product-tag:hover::before {
                left: 100%;
            }

            .product-tag:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .tag-icon {
                font-size: 10px;
                flex-shrink: 0;
            }

            .tag-label {
                white-space: nowrap;
            }

            /* Colores de tags */
            .tag-turquesa {
                background: rgba(107, 194, 195, 0.1);
                color: #0d9488;
                border-color: rgba(107, 194, 195, 0.2);
            }

            .tag-turquesa:hover {
                background: rgba(107, 194, 195, 0.2);
                color: #0f766e;
            }

            .tag-yellow {
                background: rgba(251, 191, 36, 0.1);
                color: #d97706;
                border-color: rgba(251, 191, 36, 0.2);
            }

            .tag-yellow:hover {
                background: rgba(251, 191, 36, 0.2);
                color: #b45309;
            }

            .tag-red {
                background: rgba(239, 68, 68, 0.1);
                color: #dc2626;
                border-color: rgba(239, 68, 68, 0.2);
            }

            .tag-red:hover {
                background: rgba(239, 68, 68, 0.2);
                color: #b91c1c;
            }

            .tag-helin {
                background: rgba(71, 85, 105, 0.1);
                color: #475569;
                border-color: rgba(71, 85, 105, 0.2);
            }

            .tag-helin:hover {
                background: rgba(71, 85, 105, 0.2);
                color: #334155;
            }

            .tag-purple {
                background: rgba(147, 51, 234, 0.1);
                color: #9333ea;
                border-color: rgba(147, 51, 234, 0.2);
            }

            .tag-purple:hover {
                background: rgba(147, 51, 234, 0.2);
                color: #7c3aed;
            }

            .tag-green {
                background: rgba(34, 197, 94, 0.1);
                color: #16a34a;
                border-color: rgba(34, 197, 94, 0.2);
            }

            .tag-green:hover {
                background: rgba(34, 197, 94, 0.2);
                color: #15803d;
            }

            /* Responsive */
            @media (max-width: 640px) {
                .product-tag {
                    font-size: 10px;
                    padding: 4px 8px;
                    gap: 4px;
                }

                .tag-icon {
                    font-size: 8px;
                }
            }
            </style>

            <script>
            function handleTagClick(event, filterValue) {
                event.preventDefault();

                // Mostrar loading
                const tag = event.currentTarget;
                const originalContent = tag.innerHTML;
                tag.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Cargando...</span>';
                tag.style.pointerEvents = 'none';

                // Construir URL según el tipo de filtro
                let url = '/catalogo?';

                if(filterValue.includes(':')) {
                    const [type, value] = filterValue.split(':');
                    if(type === 'category') {
                        url += 'category=' + encodeURIComponent(value);
                    } else if(type === 'brand') {
                        url += 'brand=' + encodeURIComponent(value);
                    } else if(type === 'material') {
                        url += 'material=' + encodeURIComponent(value);
                    } else {
                        url += 'tag=' + encodeURIComponent(filterValue);
                    }
                } else {
                    url += 'tag=' + encodeURIComponent(filterValue);
                }

                // Redirigir después de un breve delay para mostrar el loading
                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            }
            </script>
        </div>
    </div>

    <!-- Sección Central: Tabs + Widget Soporte -->
    <div class="flex flex-col lg:flex-row gap-8 mb-12">
        <!-- Tabs de Información -->
        <div class="lg:w-2/3">
            <!-- Tabs Header -->
            <div class="border-b border-helin-border mb-6">
                <nav class="flex gap-8">
                    <button onclick="showTab('descripcion', this)" id="tab-btn-descripcion" class="pb-4 border-b-2 border-turquesa text-turquesa font-bold text-xl">
                        Descripción
                    </button>
                    <button onclick="showTab('especificaciones', this)" id="tab-btn-especificaciones" class="pb-4 border-b-2 border-transparent text-helin-heading hover:text-turquesa font-bold text-xl transition-colors">
                        Especificaciones
                    </button>
                </nav>
            </div>

            <!-- Contenido Tab Descripción -->
            <div id="tab-descripcion" class="prose max-w-none leading-relaxed text-helin-text">
                <p class="mb-4">Producto especializado para procedimientos de osteosíntesis odontológica, diseñado para ofrecer estabilidad, precisión y resistencia en aplicaciones quirúrgicas.</p>
                <p class="mb-4">Fabricado en titanio grado 5, cuenta con alta biocompatibilidad y resistencia a la corrosión, lo que permite un desempeño confiable en procedimientos de fijación ósea y uso clínico especializado.</p>
            </div>

            <!-- Contenido Tab Especificaciones -->
            <div id="tab-especificaciones" class="prose max-w-none leading-relaxed text-helin-text hidden">
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
                            <td class="py-3 pr-6 font-semibold text-helin-heading w-1/3">{{ $key }}</td>
                            <td class="py-3 text-helin-text">{{ $value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    <a href="{{ asset('images/ficha_test.pdf') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-white text-turquesa border border-turquesa text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-turquesa/10 transition-colors">
                        <i class="fas fa-file-pdf"></i>
                        Descargar ficha técnica
                    </a>
                </div>
            </div>

            <script>
            function showTab(tab, btn) {
                ['descripcion','especificaciones'].forEach(t => {
                    document.getElementById('tab-' + t).classList.add('hidden');
                    document.getElementById('tab-btn-' + t).classList.remove('border-turquesa','text-turquesa');
                    document.getElementById('tab-btn-' + t).classList.add('border-transparent','text-helin-heading');
                });
                document.getElementById('tab-' + tab).classList.remove('hidden');
                btn.classList.remove('border-transparent','text-helin-heading');
                btn.classList.add('border-turquesa','text-turquesa');
            }
            </script>
        </div>

        <!-- Widget Soporte -->
        <div class="lg:w-1/3 ml-auto">
            <div class="bg-white rounded-xl overflow-hidden">
                <div class="flex flex-col items-center pt-4 px-4">
                    <img src="{{ asset('images/atencion_cliente.png') }}" alt="Atención al cliente Helin" class="h-auto object-cover" style="width: 52%;">
                    <div class="w-full mt-3 mb-4" style="width: 52%;">
                        <a href="https://api.whatsapp.com/send/?phone=584244669150&text=Hola%2C+estoy+interesado+en+productos+Helin+y+me+gustar%C3%ADa+recibir+asesor%C3%ADa+de+un+ejecutivo+comercial.&type=phone_number&app_absent=0" target="_blank" rel="noopener noreferrer" class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-semibold py-1.5 rounded-full transition-colors flex items-center justify-center gap-2 text-[10px] sm:text-xs">
                            <i class="fab fa-whatsapp text-sm"></i>
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
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl text-helin-heading mb-1">Productos Relacionados</h2>
                <p class="text-helin-text text-sm">Conoce los productos relacionados para ti</p>
            </div>
            <a href="{{ route('catalogo') }}" class="text-turquesa font-semibold border-b border-turquesa pb-0.5">Ver todos los productos <i class="fas fa-arrow-right ml-1 text-turquesa"></i></a>
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
