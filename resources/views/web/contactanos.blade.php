@extends('web.layouts.app')

@section('title', 'Contáctanos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/contactanos.css') }}">
@endsection

@section('content')
@php
    $settings = \App\Models\Settings::getSettings();
@endphp
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
            @php
                $contactSection = \App\Models\Sections::find(\App\Models\Sections::CONTACT_HERO);
            @endphp
            @if($contactSection && $contactSection->status_content)
                @if($contactSection->layout_type === 'text_simple')
                    <h2>{{ $contactSection->title }}</h2>
                    @if($contactSection->description)
                        <p>{{ $contactSection->description }}</p>
                    @endif
                @else
                    {!! $contactSection->content !!}
                @endif
            @endif

            <div class="info-list">
                <article class="info-item">
                    <div class="info-icon">⌖</div>
                    <div>
                        <h3>Dirección</h3>
                        <p>{{ $settings->contact_address ?? 'Centro Comercial El Recreo,<br>Torre Norte, Piso 5, Oficina 5B,<br>Sabana Grande, Caracas 1050, Venezuela.' }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon">☏</div>
                    <div>
                        <h3>Teléfono central</h3>
                        <p>{{ $settings->phone ?? '+58 4242789481' }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon">✉</div>
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
                        <a href="{{ $settings->caracas_location }}" target="_blank" class="sede-pill"><span>⌖</span>Caracas</a>
                    @else
                        <span class="sede-pill"><span>⌖</span>Caracas</span>
                    @endif

                    @if($settings && $settings->valencia_location)
                        <a href="{{ $settings->valencia_location }}" target="_blank" class="sede-pill"><span>⌖</span>Valencia</a>
                    @else
                        <span class="sede-pill"><span>⌖</span>Valencia</span>
                    @endif

                    @if($settings && $settings->barquisimeto_location)
                        <a href="{{ $settings->barquisimeto_location }}" target="_blank" class="sede-pill"><span>⌖</span>Barquisimeto</a>
                    @else
                        <span class="sede-pill"><span>⌖</span>Barquisimeto</span>
                    @endif

                    @if($settings && $settings->maracay_location)
                        <a href="{{ $settings->maracay_location }}" target="_blank" class="sede-pill"><span>⌖</span>Maracay</a>
                    @else
                        <span class="sede-pill"><span>⌖</span>Maracay</span>
                    @endif
                </div>
            </div>
        </aside>

        <section class="form-card">
            <form class="form-grid">
                <div>
                    <label>Nombre <span>*</span></label>
                    <input type="text" name="nombre" placeholder="Ingresa tu nombre" required>
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
                    <select name="asunto" required>
                        <option value="">Selecciona un asunto</option>
                        <option value="informacion-comercial">Información comercial</option>
                        <option value="asesoria-productos">Asesoría de productos</option>
                        <option value="cotizacion">Cotización</option>
                        <option value="disponibilidad">Disponibilidad de productos</option>
                        <option value="soporte-orden">Soporte de orden</option>
                        <option value="recursos-clinicos">Recursos clínicos</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="field-full">
                    <label>¿Cómo podemos ayudarte? <span>*</span></label>
                    <textarea name="mensaje" placeholder="Cuéntanos más sobre tu consulta..." required></textarea>
                </div>

                <button class="submit" type="submit">➤ Contactar a Helin</button>

                <div class="secure">▵ Tu información está segura con nosotros.</div>
            </form>
        </section>
    </section>
</main>
@endsection
