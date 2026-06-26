@extends('web.layouts.app')

@section('title', 'Detalle de la Solicitud - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'class="text-sm mb-6"',
        'items' => [
            ['label' => 'Inicio', 'url' => route('web.home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Carrito', 'url' => route('web.carrito'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Detalle de la Solicitud', 'spanAttributes' => 'class="text-turquesa font-medium"']
        ],
        'separatorAttributes' => 'class="text-helin-text mx-2"'
    ])

    <form action="#" method="POST" class="flex flex-col lg:flex-row gap-8">
        <!-- Columna Izquierda (70%) - Datos del Cliente -->
        <div class="lg:w-[70%] space-y-8">

            <!-- Bloque: Detalle de la solicitud -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl text-helin-heading mb-6">Detalle de la solicitud</h2>

                <!-- Tipo de cliente -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Tipo de cliente *</label>
                    <select required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                        <option value="">Selecciona una opción</option>
                        <option value="doctor">Doctor</option>
                        <option value="paciente">Paciente</option>
                        <option value="empresa">Empresa</option>
                    </select>
                </div>

                <!-- Nombre y Apellido -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Nombre *</label>
                        <input type="text" required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Tu nombre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Apellido *</label>
                        <input type="text" required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Tu apellido">
                    </div>
                </div>

                <!-- Empresa (opcional) -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Empresa (opcional)</label>
                    <input type="text" class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Nombre de la empresa">
                </div>

                <!-- Estado y Ciudad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Estado *</label>
                        <select required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                            <option value="">Selecciona estado</option>
                            <option value="distrito-capital">Distrito Capital</option>
                            <option value="miranda">Miranda</option>
                            <option value="carabobo">Carabobo</option>
                            <option value="aragua">Aragua</option>
                            <option value="lara">Lara</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Ciudad *</label>
                        <select required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                            <option value="">Selecciona ciudad</option>
                            <option value="caracas">Caracas</option>
                            <option value="valencia">Valencia</option>
                            <option value="barquisimeto">Barquisimeto</option>
                            <option value="maracay">Maracay</option>
                        </select>
                    </div>
                </div>

                <!-- Dirección completa -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Dirección completa *</label>
                    <textarea required rows="3" class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors resize-none" placeholder="Calle, número, urbanización, edificio, piso, apartamento..."></textarea>
                </div>

                <!-- Teléfono y Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Teléfono *</label>
                        <input type="tel" required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="+58 412 123 4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Correo Electrónico *</label>
                        <input type="email" required class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="correo@ejemplo.com">
                    </div>
                </div>
            </div>

            <!-- Bloque: Información adicional -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl text-helin-heading mb-6">Información adicional</h2>

                <div>
                    <label class="block text-sm font-medium text-helin-heading mb-2">Observaciones</label>
                    <textarea rows="5" class="w-full border border-helin-border rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors resize-none" placeholder="Indica aquí cualquier detalle adicional sobre tu pedido, horario de entrega preferido, instrucciones especiales..."></textarea>
                </div>
            </div>
        </div>

        <!-- Columna Derecha (30%) - Widget de Orden -->
        <div class="lg:w-[30%]">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 class="text-xl text-helin-heading mb-6">Orden</h2>

                <!-- Resumen de productos -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-helin-text">Implante Straumann BLX x 1</span>
                        <span class="font-medium">$299.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-helin-text">Biomaterial Bio-Oss x 2</span>
                        <span class="font-medium">$298.00</span>
                    </div>
                    <div class="border-t border-helin-border pt-3 mt-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-helin-text">Subtotal</span>
                            <span>$597.00</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-helin-text">Descuento</span>
                            <span class="text-green-500">-$45.00</span>
                        </div>
                        <div class="flex justify-between text-base font-bold">
                            <span class="text-helin-heading">Total</span>
                            <span class="text-turquesa">$552.00</span>
                        </div>
                    </div>
                </div>

                <!-- Métodos de Entrega -->
                <div class="mb-6">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Entrega</h3>
                    <div class="space-y-2">
                        @foreach(['MRW (Cobro destino)', 'Liberty (Cobro destino)', 'Tealca', 'Zoom', 'Pick Up (Recoger en tienda)'] as $metodo)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" class="w-4 h-4 text-turquesa" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-sm text-helin-text">{{ $metodo }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div class="mb-6">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Pago</h3>
                    <div class="space-y-2">
                        @foreach(['Acordar con Helin', 'Pago Móvil', 'Zelle', 'Binance Pay', 'Pagos Múltiples'] as $pago)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" class="w-4 h-4 text-turquesa" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-sm text-helin-text">{{ $pago }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Nro de comprobante -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Nro. de comprobante de pago</label>
                    <input type="text" class="w-full border border-helin-border rounded-lg px-4 py-2 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors text-sm" placeholder="Referencia de transferencia">
                </div>

                <!-- Botón Enviar -->
                <button type="submit" class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold py-4 rounded-full uppercase transition-colors">
                    Enviar Solicitud Comercial
                </button>
            </div>
        </div>
    </form>
</main>

@include('web.partials.beneficios')
@endsection
