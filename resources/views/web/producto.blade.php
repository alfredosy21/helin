@extends('web.layouts.app')

@section('title', 'Implante Dental Straumann BLX - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('web.home') }}" class="text-gray-500 hover:text-turquesa">Inicio</a>
        <span class="text-gray-400 mx-2">></span>
        <a href="{{ route('web.catalogo') }}" class="text-gray-500 hover:text-turquesa">Catálogo</a>
        <span class="text-gray-400 mx-2">></span>
        <a href="{{ route('web.catalogo') }}" class="text-gray-500 hover:text-turquesa">Implantología</a>
        <span class="text-gray-400 mx-2">></span>
        <span class="text-turquesa font-medium">Implante Straumann BLX</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8 mb-12">
        <!-- Imagen del Producto -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                <img src="https://via.placeholder.com/500x500/f8f9fa/15aabf?text=Implante" alt="Implante Dental" class="w-full h-96 object-contain">
            </div>
            <div class="grid grid-cols-4 gap-3">
                <button class="border-2 border-turquesa rounded-lg overflow-hidden p-2">
                    <img src="https://via.placeholder.com/100x100/f8f9fa/15aabf?text=1" class="w-full h-16 object-contain">
                </button>
                <button class="border border-gray-200 rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="https://via.placeholder.com/100x100/f8f9fa/15aabf?text=2" class="w-full h-16 object-contain">
                </button>
                <button class="border border-gray-200 rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="https://via.placeholder.com/100x100/f8f9fa/15aabf?text=3" class="w-full h-16 object-contain">
                </button>
                <button class="border border-gray-200 rounded-lg overflow-hidden p-2 hover:border-turquesa">
                    <img src="https://via.placeholder.com/100x100/f8f9fa/15aabf?text=4" class="w-full h-16 object-contain">
                </button>
            </div>
        </div>

        <!-- Info del Producto -->
        <div class="lg:w-1/2">
            <span class="bg-turquesa/10 text-turquesa text-xs font-semibold px-3 py-1 rounded-full">Nuevo</span>
            <h1 class="text-3xl font-bold text-gray-800 mt-3 mb-6">Implante Dental Straumann BLX</h1>

            <div class="flex items-center gap-3 mb-6">
                <span class="text-2xl font-bold text-[#15aabf]" style="font-size: 24px;">$299.00</span>
                <span class="text-xl text-gray-400" style="text-decoration: line-through;">$350.00</span>
            </div>

            <p class="text-gray-600 mb-6">El implante Straumann BLX representa la última generación en implantología dental. Diseñado para ofrecer máxima estabilidad primaria y osteointegración óptima en todos los tipos de hueso.</p>

            <!-- Selector de Tamaño (Chips) -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Tamaño</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="px-2 py-1 text-xs border-2 border-turquesa bg-turquesa/10 text-turquesa rounded-full font-medium transition-all">Ø3.3 mm</button>
                    <button class="px-2 py-1 text-xs border border-gray-300 text-gray-700 rounded-full hover:border-turquesa transition-all">Ø4.1 mm</button>
                    <button class="px-2 py-1 text-xs border border-gray-300 text-gray-700 rounded-full hover:border-turquesa transition-all">Ø4.8 mm</button>
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
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex gap-8">
                    <button class="pb-4 border-b-2 border-turquesa text-turquesa font-bold text-xl">
                        Descripción
                    </button>
                    <button class="pb-4 border-b-2 border-transparent text-gray-800 hover:text-gray-900 font-bold text-xl">
                        Especificaciones
                    </button>
                </nav>
            </div>

            <!-- Contenido Tab Descripción -->
            <div class="prose max-w-none leading-relaxed text-[#4a5568]">
                <p class="mb-4">
                    El implante Straumann BLX representa la última generación en implantología dental. Diseñado para ofrecer máxima estabilidad primaria y osteointegración óptima en todos los tipos de hueso.
                </p>
                <p class="mb-4">
                    Su diseño roscado optimizado y superficie SLActive proporcionan una integración ósea rápida y predecible, reduciendo los tiempos de espera entre la colocación del implante y la carga protésica.
                </p>
                <h4 class="font-bold text-gray-800 mb-2">Características principales:</h4>
                <ul class="list-disc list-inside space-y-1 mb-4">
                    <li>Superficie SLActive para osteointegración acelerada</li>
                    <li>Diseño de rosca optimizado para alta estabilidad primaria</li>
                    <li>Plataforma Switching para reducción de pérdida ósea crestal</li>
                    <li>Conexión CrossFit compatible con todo el sistema Pro</li>
                    <li>Disponible en múltiples diámetros y longitudes</li>
                </ul>
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
                <a href="https://wa.me/584241232025" target="_blank" class="flex items-center justify-between text-gray-700 hover:text-turquesa py-2 transition-colors border border-gray-200 rounded-full hover:border-turquesa px-6">
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
            <h2 class="text-xl font-bold text-gray-800 mb-1">Productos Relacionados</h2>
            <p class="text-gray-500 text-sm mb-3">Conoce los productos relacionados para ti</p>
            <a href="{{ route('web.catalogo') }}" class="text-turquesa font-semibold border-b border-turquesa pb-0.5">Ver todos los productos <i class="fas fa-arrow-right ml-1 text-[#00A3A0]"></i></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Biomaterial', 'productName' => 'Biomaterial Óseo Bio-Oss', 'productBrand' => 'Geistlich', 'productPrice' => 149.00, 'productBadge' => '', 'productLink' => route('web.producto')])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Membrana', 'productName' => 'Membrana Colágeno Bio-Gide', 'productBrand' => 'Geistlich', 'productPrice' => 89.00, 'productBadge' => ''])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit', 'productName' => 'Kit de Cirugía Implantológica', 'productBrand' => 'Helin', 'productPrice' => 199.00, 'productBadge' => 'Nuevo'])
            @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Suturas', 'productName' => 'Suturas Resorbibles 4-0', 'productBrand' => 'Johnson & Johnson', 'productPrice' => 45.00, 'productBadge' => ''])
        </div>
    </section>
</main>

@include('web.partials.beneficios')
@endsection
