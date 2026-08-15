@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Detalle de la Solicitud - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/solicitud.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'text-sm mb-6',
        'items' => [
            ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Carrito', 'url' => route('carrito'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Detalle de la Solicitud', 'spanAttributes' => 'class="text-turquesa font-medium"']
        ],
        'separatorAttributes' => 'class="text-helin-text mx-2"'
    ])

    <form action="{{ route('solicitud.send') }}" method="POST" class="flex flex-col lg:flex-row gap-8" id="solicitud-form">
        @csrf
        <!-- Columna Izquierda (55%) - Datos del Cliente -->
        <div class="lg:w-[55%] space-y-6">

            <!-- Bloque: Detalle de la solicitud -->
            <div class="bg-gray-50/70 rounded-xl border border-helin-border/20 p-6">
                <h2 class="text-lg font-semibold text-helin-heading/80 mb-5">Detalle de la solicitud</h2>

                <!-- Tipo de cliente -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Tipo de cliente *</label>
                    <div class="custom-select" data-name="tipo_cliente" data-required="true">
                        <input type="hidden" name="tipo_cliente" required value="">
                        <div class="custom-select-trigger" data-placeholder="Selecciona una opción">Selecciona una opción</div>
                        <div class="custom-select-options">
                            @foreach($customerTypes as $type)
                                <div class="custom-select-option" data-value="{{ $type->slug }}">{{ $type->name }}</div>
                            @endforeach
                        </div>
                        <div class="custom-select-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Nombre y Apellido -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Nombre *</label>
                        <input type="text" name="nombre" required class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Tu nombre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Apellido *</label>
                        <input type="text" name="apellido" required class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Tu apellido">
                    </div>
                </div>

                <!-- Empresa (opcional) -->
                <div id="cedula-field" class="mb-5 hidden">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Cédula *</label>
                    <input type="text" name="cedula" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="V-12345678">
                </div>

                <div id="empresa-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5 hidden">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Empresa *</label>
                        <input type="text" name="empresa" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Nombre de la empresa">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">RIF *</label>
                        <input type="text" name="rif" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="J-12345678-9">
                    </div>
                </div>

                <!-- Teléfono y Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Teléfono *</label>
                        <input type="tel" name="telefono" required class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="+58 412 123 4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Correo Electrónico *</label>
                        <input type="email" name="email" required class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <!-- Estado y Ciudad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Estado *</label>
                        <div class="custom-select" data-name="estado" data-required="true" id="estado-select-custom">
                            <input type="hidden" name="estado" id="estado-input" required value="">
                            <div class="custom-select-trigger" data-placeholder="Selecciona estado">Selecciona estado</div>
                            <div class="custom-select-options">
                                @foreach($states as $state)
                                    <div class="custom-select-option" data-value="{{ $state->code }}">{{ $state->name }}</div>
                                @endforeach
                            </div>
                            <div class="custom-select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Ciudad *</label>
                        <div class="custom-select" data-name="ciudad" data-required="true" id="ciudad-select-custom" data-disabled="true">
                            <input type="hidden" name="ciudad" id="ciudad-input" required value="" disabled>
                            <div class="custom-select-trigger" data-placeholder="Selecciona ciudad">Selecciona ciudad</div>
                            <div class="custom-select-options">
                            </div>
                            <div class="custom-select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dirección completa -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Dirección completa *</label>
                    <textarea name="direccion" required rows="3" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90 resize-none" placeholder="Calle, número, urbanización, edificio, piso, apartamento..."></textarea>
                </div>
            </div>

            <!-- Bloque: Información adicional -->
            <div class="bg-gray-50/70 rounded-xl border border-helin-border/20 p-6">
                <h2 class="text-lg font-semibold text-helin-heading/80 mb-5">Información adicional</h2>

                <div>
                    <label class="block text-sm font-medium text-helin-heading mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="5" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90 resize-none" placeholder="Indica aquí cualquier detalle adicional sobre tu pedido, horario de entrega preferido, instrucciones especiales..."></textarea>
                </div>
            </div>
        </div>

        <!-- Columna Derecha (45%) - Widget de Orden -->
        <div class="lg:w-[45%]">
            <div class="bg-white/80 rounded-xl border border-helin-border/30 p-6 sticky top-24">
                <h2 class="text-lg font-semibold text-helin-heading/80 mb-5">Resumen de la orden</h2>

                <!-- Resumen de productos -->
                <div class="mb-6 -mx-6" id="cart-summary">
                    <!-- Cart items will be rendered dynamically by cart-ui.js -->
                    <div class="flex items-center justify-center py-8 text-helin-text">
                        <i class="fas fa-spinner fa-spin text-turquesa text-xl mr-2"></i>
                        <span class="text-sm">Cargando resumen del carrito...</span>
                    </div>
                </div>

                <!-- Métodos de Entrega -->
                <div class="mb-6">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Entrega *</h3>
                    <div class="custom-select" data-name="envio" data-required="true" id="envio-select-custom">
                        <input type="hidden" name="envio" id="envio-input" required value="">
                        <div class="custom-select-trigger" data-placeholder="Selecciona método de entrega">Selecciona método de entrega</div>
                        <div class="custom-select-options">
                            @forelse($deliveryMethods->where('slug', '!=', 'zoom') as $method)
                                <div class="custom-select-option" data-value="{{ $method->slug }}">{{ $method->name }}</div>
                            @empty
                                <div class="custom-select-option" data-value="">No hay métodos disponibles</div>
                            @endforelse
                            <div class="custom-select-option" data-value="otra-empresa">Otra empresa</div>
                        </div>
                        <div class="custom-select-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>
                    <div id="other-delivery-company-field" class="mt-3 hidden">
                        <label class="block text-sm font-medium text-helin-heading mb-2">Indica la empresa de entrega *</label>
                        <input type="text" name="otra_empresa_entrega" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Nombre de la empresa">
                    </div>

                    <!-- Información de punto de retiro -->
                    <div id="pickup-info" class="mt-4 hidden bg-turquesa/10 border border-turquesa/30 rounded-lg p-4">
                        <p class="text-sm font-semibold text-helin-heading mb-3">Punto de Retiro</p>
                        <div id="pickup-zone-content">
                            <p id="pickup-zone-address" class="text-sm text-helin-text"></p>
                        </div>
                        <p id="pickup-zone-empty" class="text-sm text-helin-text">Selecciona tu estado para ver tu punto de retiro asignado.</p>
                    </div>
                </div>

                <!-- Bloque: Datos del envío -->
                <div id="shipping-data-block" class="mb-6 hidden">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Datos del envío</h3>

                    <!-- Datos del destinatario -->
                    <div class="mb-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-helin-heading mb-2">¿Quién recibe? *</label>
                                <input type="text" name="destinatario_nombre" class="shipping-field w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Nombre y apellido del destinatario">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-helin-heading mb-2">Documento de identidad o RIF *</label>
                                <input type="text" name="destinatario_documento" class="shipping-field w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="V-12345678 o J-12345678-9">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-helin-heading mb-2">Teléfono de contacto *</label>
                            <input type="tel" name="destinatario_telefono" class="shipping-field w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="+58 412 123 4567">
                        </div>
                    </div>

                    <!-- Destino del envío -->
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-helin-heading mb-2">Estado *</label>
                                <div class="custom-select" data-name="envio_estado" data-required="true" id="envio-estado-select-custom">
                                    <input type="hidden" name="envio_estado" id="envio-estado-input" class="shipping-field" value="">
                                    <div class="custom-select-trigger" data-placeholder="Selecciona estado">Selecciona estado</div>
                                    <div class="custom-select-options">
                                        @foreach($states as $state)
                                            <div class="custom-select-option" data-value="{{ $state->code }}">{{ $state->name }}</div>
                                        @endforeach
                                    </div>
                                    <div class="custom-select-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-helin-heading mb-2">Ciudad *</label>
                                <div class="custom-select" data-name="envio_ciudad" data-required="true" id="envio-ciudad-select-custom" data-disabled="true">
                                    <input type="hidden" name="envio_ciudad" id="envio-ciudad-input" class="shipping-field" value="" disabled>
                                    <div class="custom-select-trigger" data-placeholder="Selecciona ciudad">Selecciona ciudad</div>
                                    <div class="custom-select-options">
                                    </div>
                                    <div class="custom-select-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-helin-heading mb-2">Agencia o sucursal de destino *</label>
                            <input type="text" name="agencia_destino" class="shipping-field w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-3 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90" placeholder="Agencia o sucursal de destino">
                        </div>
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div id="payment-methods-block" class="mb-6 opacity-50 pointer-events-none">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Pago *</h3>
                    <div class="custom-select" data-name="pago" data-required="true" id="pago-select-custom">
                        <input type="hidden" name="pago" id="pago-input" required value="">
                        <div class="custom-select-trigger" data-placeholder="Selecciona método de pago">Selecciona método de pago</div>
                        <div class="custom-select-options" id="payment-methods-list">
                            @forelse($paymentMethods as $method)
                                <div class="custom-select-option payment-method-option" data-value="{{ $method->name }}" data-description="{{ $method->description }}" data-requires-receipt="{{ $method->requires_receipt ? '1' : '0' }}">{{ $method->name }}</div>
                            @empty
                                <div class="custom-select-option" data-value="">No hay métodos disponibles</div>
                            @endforelse
                        </div>
                        <div class="custom-select-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    {{-- Description panel --}}
                    <div id="payment-description" class="mt-3 p-3 bg-turquesa/5 border border-turquesa/20 rounded-lg hidden">
                        <p class="text-xs text-helin-text leading-relaxed" id="payment-description-text"></p>
                    </div>
                </div>

                <!-- Nro de comprobante -->
                <div id="payment-receipt-block" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Nro. de comprobante de pago *</label>
                    <input type="text" name="numero_comprobante" id="payment-receipt-input" class="w-full bg-gray-50/80 border border-helin-border/30 rounded-lg px-4 py-2 focus:border-turquesa/50 focus:ring-1 focus:ring-turquesa/30 outline-none transition-colors opacity-90 text-sm" placeholder="Referencia de transferencia">
                </div>

                <!-- Aceptación de política de privacidad -->
                <div class="mb-5">
                    <label class="flex items-start gap-2 text-helin-text cursor-pointer" style="font-size: 0.65rem;">
                        <input type="checkbox" name="privacy_accepted" required class="mt-1 accent-turquesa">
                        <span>He leído y acepto la <a href="{{ route('politicas') }}" target="_blank" class="text-turquesa underline hover:no-underline font-semibold">Política de Privacidad</a> y autorizo a Helin a utilizar mis datos para gestionar mi solicitud y contactarme.</span>
                    </label>
                </div>

                <!-- Botón Enviar -->
                <button type="submit" id="submit-btn" disabled class="w-full bg-gray-400 text-white font-bold text-sm py-3 rounded-full uppercase transition-colors cursor-not-allowed">
                    Enviar Solicitud Comercial
                </button>
            </div>
        </div>
    </form>
</main>

@include('web.partials.beneficios')
@endsection

@push('scripts')
@php
    $solicitudCities = [];
    foreach ($cities as $city) {
        $code = $city->state->code;
        if (!isset($solicitudCities[$code])) {
            $solicitudCities[$code] = [];
        }
        $solicitudCities[$code][] = ['name' => $city->name, 'slug' => $city->slug];
    }
@endphp
<script>window.HelinSolicitud = { pickups: @json($pickups), cities: @json($solicitudCities) };</script>
<script src="@minAsset('helin/js/solicitud.js')"></script>
@endpush
