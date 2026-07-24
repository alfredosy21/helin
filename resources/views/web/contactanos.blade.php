@extends('web.layouts.app')

@section('title', 'Contáctanos - Helin')
@section('meta-description', 'Contacta a Helin para asesoría especializada en productos odontológicos. Atención personalizada para implantes, instrumentos y biomateriales. Envíos a todo Venezuela.')
@section('meta-keywords', 'contacto helin, asesoría odontológica, productos dentales, implantes Venezuela, soporte técnico, material dental')
@section('og-type', 'website')
@section('og-image', asset('images/helin-contact-og.jpg'))

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/contactanos.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Contacto']
        ]
    ])

    <h1 class="page-title">Contáctanos</h1>

    <section class="contact-layout">
        <aside class="info-block">
                        @if($contactSection && $contactSection->status == 1 && $contactSection->status_content == 1)
                @if($contactSection->layout_type === 'text_simple')
                    <h2>¿Tienes alguna consulta?<br><span style="font-size: 69%;">Estamos para ayudarte.</span></h2>
                    @if($contactSection->description)
                        <p>{{ $contactSection->description }}</p>
                    @endif
                @else
                    {!! $contactSection->content !!}
                @endif
            @endif

            <div class="info-list">
                <article class="info-item">
                    <div class="info-icon"><img src="{{ asset('icons/location.svg') }}" alt="Dirección" width="24" height="24"></div>
                    <div>
                        <h3>Dirección</h3>
                        <p>{{ $settings->contact_address ?? 'Centro Comercial El Recreo,<br>Torre Norte, Piso 5, Oficina 5B,<br>Sabana Grande, Caracas 1050, Venezuela.' }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon"><img src="{{ asset('icons/ws.svg') }}" alt="WhatsApp Comercial" width="24" height="24"></div>
                    <div>
                        <h3>WhatsApp Comercial</h3>
                        <p>{{ $settings->phone ?? '+58 4242789481' }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon"><img src="{{ asset('icons/mail.svg') }}" alt="Correo electrónico" width="24" height="24"></div>
                    <div>
                        <h3>Correo electrónico</h3>
                        <p>{{ $settings->email ?? 'info@helinlatam.com' }}</p>
                    </div>
                </article>
            </div>

            <div class="sedes">
                <h3>Nuestras sedes</h3>
                <div class="sede-pills">
                    @if($settings && $settings->caracas_location)
                        <a href="{{ $settings->caracas_location }}" target="_blank" class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Caracas" width="10" height="10">Caracas</a>
                    @else
                        <span class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Caracas" width="10" height="10">Caracas</span>
                    @endif

                    @if($settings && $settings->valencia_location)
                        <a href="{{ $settings->valencia_location }}" target="_blank" class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Valencia" width="10" height="10">Valencia</a>
                    @else
                        <span class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Valencia" width="10" height="10">Valencia</span>
                    @endif

                    @if($settings && $settings->barquisimeto_location)
                        <a href="{{ $settings->barquisimeto_location }}" target="_blank" class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Barquisimeto" width="10" height="10">Barquisimeto</a>
                    @else
                        <span class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Barquisimeto" width="10" height="10">Barquisimeto</span>
                    @endif

                    @if($settings && $settings->maracaibo_location)
                        <a href="{{ $settings->maracaibo_location }}" target="_blank" class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Maracaibo" width="10" height="10">Maracaibo</a>
                    @else
                        <span class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="Maracaibo" width="10" height="10">Maracaibo</span>
                    @endif
                </div>
            </div>
        </aside>

        <section class="form-card">
            <form id="contact-form" class="form-grid" novalidate>
                <div>
                    <label>Nombre Completo <span>*</span></label>
                    <input type="text" name="nombre" placeholder="Ingresa tu nombre completo" required>
                </div>

                <div>
                    <label>Correo electrónico <span>*</span></label>
                    <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="Ingresa tu número de teléfono">
                </div>

                <div>
                    <label>Asunto <span>*</span></label>
                    <div class="select-wrapper">
                        <select name="asunto" required>
                            <option value="" disabled hidden selected>Selecciona un asunto</option>
                            <option value="informacion-comercial">Información comercial</option>
                            <option value="asesoria-productos">Asesoría de productos</option>
                            <option value="cotizacion">Cotización</option>
                            <option value="disponibilidad">Disponibilidad de productos</option>
                            <option value="soporte-orden">Soporte de orden</option>
                            <option value="recursos-clinicos">Recursos clínicos</option>
                            <option value="otro">Otro</option>
                        </select>
                        <span class="select-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </div>
                </div>

                <div class="field-full">
                    <label>¿Cómo podemos ayudarte? <span>*</span></label>
                    <textarea name="mensaje" placeholder="Cuéntanos más sobre tu consulta..." required></textarea>
                </div>

                <div class="field-full privacy-check">
                    <label class="privacy-label">
                        <input type="checkbox" name="privacy_accepted" required>
                        <span>He leído y acepto la <a href="{{ route('politicas') }}" target="_blank">Política de Privacidad</a> y autorizo a Helin a utilizar mis datos para gestionar mi solicitud y contactarme por WhatsApp, llamada o correo electrónico.</span>
                    </label>
                </div>

                <button id="contact-submit" class="submit" type="submit">➤ Contactar a Helin</button>

                <div class="secure"><i class="fa fa-lock secure-icon" aria-hidden="true"></i> Tu información está segura con nosotros.</div>
            </form>
        </section>
    </section>
</main>
@endsection

@push('scripts')
<script src="{{ asset('helin/js/contactanos.js') }}"></script>
@endpush
