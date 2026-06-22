@extends('web.layouts.app')

@section('title', 'Catálogo de Productos - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filtros -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="space-y-6">
                @include('web.components.breadcrumb', [
                    'attributes' => 'class="text-sm text-gray-500 mb-6"',
                    'items' => [
                        ['label' => 'Inicio', 'url' => route('web.home'), 'linkAttributes' => 'class="hover:text-turquesa"'],
                        ['label' => 'Categorías', 'url' => route('web.catalogo'), 'linkAttributes' => 'class="hover:text-turquesa"'],
                        ['label' => 'Implantología', 'spanAttributes' => 'class="text-turquesa font-medium"']
                    ],
                    'separatorAttributes' => 'class="text-gray-400 mx-1"'
                ])

                <!-- Especialidad -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Especialidad</h4>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Cirugía Bucal</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Cirugía Maxilofacial</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Periodoncia</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Ortodoncia</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Endodoncia</span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200 my-4">

                <!-- Categorías con badges -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Categorías</h4>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                            <span class="flex items-center text-gray-600 text-sm">
                                <span class="w-2 h-2 rounded-full bg-turquesa mr-2"></span>
                                <span class="text-turquesa font-medium">Implantología</span>
                            </span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center">50</span>
                        </a>
                        <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                            <span class="text-gray-600 text-sm">Instrumental</span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center">18</span>
                        </a>
                        <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                            <span class="text-gray-600 text-sm">Biomateriales</span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center">22</span>
                        </a>
                        <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                            <span class="text-gray-600 text-sm">Equipos</span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center">12</span>
                        </a>
                        <a href="#" class="flex items-center justify-between py-1 hover:text-turquesa transition-colors group">
                            <span class="text-gray-600 text-sm">Suturas</span>
                            <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs flex items-center justify-center">8</span>
                        </a>
                    </div>
                </div>

                <hr class="border-gray-200 my-4">

                <!-- Marcas -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Marcas</h4>
                    <div class="space-y-2">
                        @foreach(['Straumann', 'Nobel Biocare', 'Dentsply', 'Kavo', 'NSK'] as $marca)
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">{{ $marca }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200 my-4">

                <!-- Tags -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Tags</h4>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Destacados</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">Ofertas</span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200 my-4">

                <!-- Filtros de categoría -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Filtros de categoría</h4>
                    <div class="space-y-2">
                        @foreach(['Nuevo', 'Stock', 'Premium', 'Básico'] as $filtro)
                        <label class="flex items-center cursor-pointer hover:text-turquesa transition-colors">
                            <input type="checkbox" class="w-4 h-4 text-turquesa rounded border-gray-300">
                            <span class="ml-2 text-gray-600 text-sm">{{ $filtro }}</span>
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
                    <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-white mb-1 sm:mb-2">Catálogo por Categoría</h1>
                    <p class="text-white/90 text-xs sm:text-base">Encuentra los mejores productos odontológicos organizados por especialidad</p>
                </div>
            </div>

            <!-- Barra de Ordenamiento -->
            <div class="bg-gray-50 rounded-lg pl-3 sm:pl-4 py-3 sm:py-4 pr-0 mb-6">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">Mostrando <strong>24</strong> productos</span>
                    <select class="border rounded-lg px-3 py-2 bg-white text-gray-700 text-sm ml-auto">
                        <option>Ordenar por: Relevancia</option>
                        <option>Precio: Menor a Mayor</option>
                        <option>Precio: Mayor a Menor</option>
                    </select>
                </div>
            </div>

            <!-- Grid Productos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Implante', 'productName' => 'Implante Dental Straumann BLX', 'productBrand' => 'Straumann', 'productPrice' => 299.00, 'productBadge' => 'Nuevo', 'productLink' => route('web.producto')])
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Biomaterial', 'productName' => 'Biomaterial Óseo Bio-Oss', 'productBrand' => 'Geistlich', 'productPrice' => 149.00, 'productBadge' => ''])
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Suturas', 'productName' => 'Suturas Resorbibles 4-0', 'productBrand' => 'Johnson & Johnson', 'productPrice' => 45.00, 'productOldPrice' => 60.00, 'productBadge' => 'Oferta'])
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Membrana', 'productName' => 'Membrana Colágeno Bio-Gide', 'productBrand' => 'Geistlich', 'productPrice' => 89.00, 'productBadge' => ''])
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Kit', 'productName' => 'Kit de Cirugía Implantológica', 'productBrand' => 'Helin', 'productPrice' => 199.00, 'productBadge' => 'Nuevo'])
                @include('web.components.product-card', ['productImage' => 'https://via.placeholder.com/300x250/f8f9fa/15aabf?text=Tornillos', 'productName' => 'Tornillos de Osteosíntesis', 'productBrand' => 'Stryker', 'productPrice' => 75.00, 'productBadge' => ''])
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                <nav class="flex items-center gap-1 sm:gap-2">
                    <button class="px-3 sm:px-4 py-2 border rounded-full text-gray-600 hover:bg-gray-100 disabled:opacity-50 text-sm" disabled>
                        <i class="fas fa-chevron-left mr-1"></i><span class="hidden sm:inline">Anterior</span>
                    </button>
                    <div class="flex items-center gap-1 sm:gap-2">
                        <button class="w-10 h-10 sm:px-4 sm:py-2 bg-turquesa text-white rounded-full text-sm font-medium">1</button>
                        <button class="w-10 h-10 sm:px-4 sm:py-2 border rounded-full text-gray-600 hover:bg-gray-100 text-sm hidden sm:block">2</button>
                        <button class="w-10 h-10 sm:px-4 sm:py-2 border rounded-full text-gray-600 hover:bg-gray-100 text-sm hidden sm:block">3</button>
                        <span class="text-gray-400 px-1 hidden sm:inline">...</span>
                        <button class="w-10 h-10 sm:px-4 sm:py-2 border rounded-full text-gray-600 hover:bg-gray-100 text-sm">10</button>
                    </div>
                    <button class="px-3 sm:px-4 py-2 border rounded-full text-gray-600 hover:bg-gray-100 text-sm">
                        <span class="hidden sm:inline">Siguiente</span><i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</main>

@include('web.partials.beneficios')
@endsection
