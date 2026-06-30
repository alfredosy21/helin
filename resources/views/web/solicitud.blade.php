@extends('web.layouts.app')

@section('title', 'Detalle de la Solicitud - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/solicitud.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'class="text-sm mb-6"',
        'items' => [
            ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Carrito', 'url' => route('carrito'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
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
                    <div class="select-wrapper">
                        <select name="tipo_cliente" required class="w-full border border-helin-border rounded-lg pl-4 pr-10 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white appearance-none cursor-pointer">
                            <option value="">Selecciona una opción</option>
                            @foreach($customerTypes as $type)
                                <option value="{{ $type->slug }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <span class="select-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </div>
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
                        <div class="select-wrapper">
                            <select name="estado" id="estado-select" required class="w-full border border-helin-border rounded-lg pl-4 pr-10 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white appearance-none cursor-pointer">
                                <option value="">Selecciona estado</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->code }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-helin-heading mb-2">Ciudad *</label>
                        <div class="select-wrapper">
                            <select name="ciudad" id="ciudad-select" required class="w-full border border-helin-border rounded-lg pl-4 pr-10 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white appearance-none cursor-pointer" disabled>
                                <option value="">Selecciona ciudad</option>
                            </select>
                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </div>
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
                <div class="space-y-3 mb-6" id="cart-summary">
                    <!-- Cart items will be rendered dynamically by cart-ui.js -->
                    <div class="flex items-center justify-center py-8 text-helin-text">
                        <i class="fas fa-spinner fa-spin text-turquesa text-xl mr-2"></i>
                        <span class="text-sm">Cargando resumen del carrito...</span>
                    </div>
                </div>

                <!-- Métodos de Entrega -->
                <div class="mb-6">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Entrega</h3>
                    <div class="space-y-2">
                        @forelse($deliveryMethods as $method)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="{{ $method->slug }}" class="w-4 h-4 text-turquesa" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-sm text-helin-text">{{ $method->name }}</span>
                        </label>
                        @empty
                        <p class="text-xs text-helin-text italic">No hay métodos de entrega disponibles.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div class="mb-6">
                    <h3 class="font-semibold text-helin-heading mb-3 text-sm">Métodos de Pago</h3>
                    <div class="space-y-2" id="payment-methods-list">
                        @forelse($paymentMethods as $method)
                        <label class="flex items-start gap-3 cursor-pointer payment-method-label group" data-description="{{ $method->description }}">
                            <input type="radio" name="pago" value="{{ $method->name }}"
                                   class="w-4 h-4 text-turquesa mt-0.5 flex-shrink-0"
                                   {{ $loop->first ? 'checked' : '' }}>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm text-helin-text font-medium group-hover:text-turquesa transition-colors">{{ $method->name }}</span>
                            </div>
                        </label>
                        @empty
                        <p class="text-xs text-helin-text italic">No hay métodos de pago disponibles.</p>
                        @endforelse
                    </div>

                    {{-- Description panel --}}
                    <div id="payment-description" class="mt-3 p-3 bg-turquesa/5 border border-turquesa/20 rounded-lg hidden">
                        <p class="text-xs text-helin-text leading-relaxed" id="payment-description-text"></p>
                    </div>
                </div>

                <!-- Nro de comprobante -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-helin-heading mb-2">Nro. de comprobante de pago</label>
                    <input type="text" class="w-full border border-helin-border rounded-lg px-4 py-2 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors text-sm" placeholder="Referencia de transferencia">
                </div>

                <!-- Botón Enviar -->
                <button type="submit" id="submit-btn" disabled class="w-full bg-gray-400 text-white font-bold py-4 rounded-full uppercase transition-colors cursor-not-allowed">
                    Enviar Solicitud Comercial
                </button>
            </div>
        </div>
    </form>
</main>

@include('web.partials.beneficios')
@endsection

@push('scripts')
<script>
(function () {
    /**
     * Form validation for submit button
     */
    function validateForm() {
        const form = document.querySelector('form[action="#"]');
        const submitBtn = document.getElementById('submit-btn');
        if (!form || !submitBtn) return;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });

        // Check if cart has items
        const cartItems = Cart.getItems();
        if (cartItems.length === 0) {
            isValid = false;
        }

        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold py-4 rounded-full uppercase transition-colors';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'w-full bg-gray-400 text-white font-bold py-4 rounded-full uppercase transition-colors cursor-not-allowed';
        }
    }

    // Initialize validation
    document.addEventListener('DOMContentLoaded', function() {
        // Validate on load
        validateForm();

        // Validate on input change
        const form = document.querySelector('form[action="#"]');
        if (form) {
            form.addEventListener('input', validateForm);
            form.addEventListener('change', validateForm);
        }

        // Validate on cart updates
        document.addEventListener('cart:updated', validateForm);
    });

    const panel      = document.getElementById('payment-description');
    const panelText  = document.getElementById('payment-description-text');
    const list       = document.getElementById('payment-methods-list');
    if (!list || !panel || !panelText) return;

    function showDescription(label) {
        const desc = label?.dataset?.description || '';
        if (desc) {
            panelText.textContent = desc;
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
    }

    list.addEventListener('change', function (e) {
        if (e.target.type === 'radio') {
            showDescription(e.target.closest('.payment-method-label'));
        }
    });

    /**
     * Show description for pre-checked option on load
     */
    const checked = list.querySelector('input[type="radio"]:checked');
    if (checked) showDescription(checked.closest('.payment-method-label'));
})();

/**
 * State/City dynamic filter
 */
(function () {
    const estadoSelect = document.getElementById('estado-select');
    const ciudadSelect = document.getElementById('ciudad-select');
    if (!estadoSelect || !ciudadSelect) return;

    /**
     * Build cities lookup from Blade data
     */
    const citiesByState = {};
    @foreach($cities as $city)
        citiesByState['{{ $city->state->code }}'] = citiesByState['{{ $city->state->code }}'] || [];
        citiesByState['{{ $city->state->code }}'].push({
            name: '{{ $city->name }}',
            slug: '{{ $city->slug }}'
        });
    @endforeach

    function updateCiudades(stateCode) {
        ciudadSelect.innerHTML = '<option value="">Selecciona ciudad</option>';
        const cities = citiesByState[stateCode] || [];
        if (cities.length === 0) {
            ciudadSelect.disabled = true;
        } else {
            ciudadSelect.disabled = false;
            cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.slug;
                opt.textContent = city.name;
                ciudadSelect.appendChild(opt);
            });
        }
    }

    estadoSelect.addEventListener('change', function () {
        updateCiudades(this.value);
    });
})();
</script>
@endpush
