@extends('web.layouts.app')

@section('title', 'Carrito de Compras - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('web.home') }}" class="text-gray-500 hover:text-turquesa">Inicio</a>
        <span class="text-gray-400 mx-2">></span>
        <span class="text-turquesa font-medium">Carrito</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Tabla de Productos (Izquierda - Mayor ancho) -->
        <div class="lg:w-[65%] xl:w-[70%]">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Cabecera de tabla - Fondo turquesa -->
                <div class="hidden md:grid md:grid-cols-12 bg-turquesa text-white font-semibold text-sm">
                    <div class="col-span-5 px-6 py-4">Producto</div>
                    <div class="col-span-2 px-4 py-4 text-center">Precio</div>
                    <div class="col-span-3 px-4 py-4 text-center">Cantidad</div>
                    <div class="col-span-2 px-6 py-4 text-right">Subtotal</div>
                </div>

                <!-- Producto 1 -->
                <div class="p-4 md:p-0 border-b border-gray-100">
                    <!-- Vista móvil -->
                    <div class="md:hidden flex gap-4">
                        <img src="https://via.placeholder.com/80x80/f8f9fa/15aabf?text=Implante" alt="Producto" class="w-20 h-20 object-contain rounded-lg flex-shrink-0">
                        <div class="flex-1">
                            <h3 class="font-semibold text-turquesa text-sm mb-1">Implante Dental Straumann BLX</h3>
                            <p class="text-gray-500 text-xs mb-2">SKU: STL-BLX-408</p>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-turquesa text-sm">$299.00</span>
                                <div class="flex items-center rounded-full bg-turquesa/10 overflow-hidden px-2.5" style="padding: 5px 10px;">
                                    <button class="w-8 h-8 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center text-sm transition-colors" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">-</button>
                                    <input type="number" value="1" min="1" class="w-20 text-center font-medium text-sm bg-transparent outline-none" style="padding: 5px 10px;">
                                    <button class="w-8 h-8 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center text-sm transition-colors" onclick="this.previousElementSibling.value++">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-turquesa">$299.00</span>
                                <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Vista desktop -->
                    <div class="hidden md:grid md:grid-cols-12 items-center">
                        <div class="col-span-5 px-6 py-5 flex items-center gap-4">
                            <img src="https://via.placeholder.com/80x80/f8f9fa/15aabf?text=Implante" alt="Producto" class="w-16 h-16 object-contain rounded-lg">
                            <div>
                                <h3 class="font-semibold text-turquesa text-sm">Implante Dental Straumann BLX</h3>
                                <p class="text-gray-500 text-xs">SKU: STL-BLX-408</p>
                            </div>
                        </div>
                        <div class="col-span-2 px-4 py-5 text-center">
                            <span class="text-turquesa font-medium">$299.00</span>
                        </div>
                        <div class="col-span-3 px-4 py-5 text-center">
                            <div class="flex items-center justify-center rounded-full bg-turquesa/10 overflow-hidden px-2.5" style="padding: 5px 10px;">
                                <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">-</button>
                                <input type="number" value="1" min="1" class="w-20 text-center font-medium bg-transparent outline-none" style="padding: 5px 10px;">
                                <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="this.previousElementSibling.value++">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="font-bold text-turquesa">$299.00</span>
                                <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Producto 2 -->
                <div class="p-4 md:p-0 border-b border-gray-100">
                    <!-- Vista móvil -->
                    <div class="md:hidden flex gap-4">
                        <img src="https://via.placeholder.com/80x80/f8f9fa/15aabf?text=Biomaterial" alt="Producto" class="w-20 h-20 object-contain rounded-lg flex-shrink-0">
                        <div class="flex-1">
                            <h3 class="font-semibold text-turquesa text-sm mb-1">Biomaterial Óseo Bio-Oss</h3>
                            <p class="text-gray-500 text-xs mb-2">SKU: GEO-BOSS-05</p>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-500 line-through text-xs">$149.00</span>
                                <span class="text-turquesa font-medium text-sm">$149.00</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">$149.00</span>
                                <div class="flex items-center rounded-full bg-turquesa/10 overflow-hidden px-2.5" style="padding: 5px 10px;">
                                    <button class="w-8 h-8 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center text-sm transition-colors" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">-</button>
                                    <input type="number" value="2" min="1" class="w-20 text-center font-medium text-sm bg-transparent outline-none" style="padding: 5px 10px;">
                                    <button class="w-8 h-8 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center text-sm transition-colors" onclick="this.previousElementSibling.value++">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-turquesa">$298.00</span>
                                <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Vista desktop -->
                    <div class="hidden md:grid md:grid-cols-12 items-center">
                        <div class="col-span-5 px-6 py-5 flex items-center gap-4">
                            <img src="https://via.placeholder.com/80x80/f8f9fa/15aabf?text=Biomaterial" alt="Producto" class="w-16 h-16 object-contain rounded-lg">
                            <div>
                                <h3 class="font-semibold text-turquesa text-sm">Biomaterial Óseo Bio-Oss</h3>
                                <p class="text-gray-500 text-xs">SKU: GEO-BOSS-05</p>
                            </div>
                        </div>
                        <div class="col-span-2 px-4 py-5 text-center">
                            <div>
                                <span class="text-gray-400 line-through text-xs block">$149.00</span>
                                <span class="text-turquesa font-medium">$149.00</span>
                            </div>
                        </div>
                        <div class="col-span-3 px-4 py-5 text-center">
                            <div class="flex items-center justify-center rounded-full bg-turquesa/10 overflow-hidden px-2.5" style="padding: 5px 10px;">
                                <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="if(this.nextElementSibling.value > 1) this.nextElementSibling.value--">-</button>
                                <input type="number" value="2" min="1" class="w-20 text-center font-medium bg-transparent outline-none" style="padding: 5px 10px;">
                                <button class="w-10 h-10 bg-turquesa-dark hover:bg-turquesa text-white rounded-full flex items-center justify-center transition-colors" onclick="this.previousElementSibling.value++">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="font-bold text-turquesa">$298.00</span>
                                <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget de Resumen (Derecha) -->
        <div class="lg:w-[35%] xl:w-[30%]">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Resumen</h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>$597.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Descuento</span>
                        <span class="text-green-500">-$45.00</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg font-semibold text-gray-800">Total</span>
                        <span class="text-2xl font-bold text-turquesa">$552.00</span>
                    </div>
                </div>

                <!-- Conversión a Bolívares -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-xs text-gray-500 mb-1">Tasa de conversión a Bs.</p>
                    <p class="text-sm text-gray-600 mb-2">1 USD = 36.50 Bs.</p>
                    <div class="flex justify-between items-center border-t border-gray-300 pt-2">
                        <span class="font-semibold text-gray-800">Total en Bs.</span>
                        <span class="text-lg font-bold text-turquesa">20,148.00 Bs.</span>
                    </div>
                </div>

                <a href="{{ route('web.solicitud') }}" class="block w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold py-4 rounded-full uppercase transition-colors text-center">
                    Continuar Solicitud
                </a>
            </div>
        </div>
    </div>
</main>

@include('web.partials.beneficios')
@endsection
