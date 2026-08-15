@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Contáctanos - Helin')
@section('meta-description', $pageSeo?->seo_description ?? 'Contacta a Helin para asesoría especializada en productos odontológicos. Atención personalizada para implantes, instrumentos y biomateriales. Envíos a todo Venezuela.')
@section('meta-keywords', $pageSeo?->seo_keywords ?? 'contacto helin, asesoría odontológica, productos dentales, implantes Venezuela, soporte técnico, material dental')
@section('og-type', 'website')
@section('og-image', $pageSeo?->og_image ? asset('storage/' . $pageSeo->og_image) : (\App\Models\Settings::getSettings()?->image ? asset('storage/' . \App\Models\Settings::getSettings()->image) : null))

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
                        <p>{{ $settings?->contact_address }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon"><img src="{{ asset('icons/ws.svg') }}" alt="WhatsApp Comercial" width="24" height="24"></div>
                    <div>
                        <h3>WhatsApp Comercial</h3>
                        <p>{{ $settings?->phone }}</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon"><img src="{{ asset('icons/mail.svg') }}" alt="Correo electrónico" width="24" height="24"></div>
                    <div>
                        <h3>Correo electrónico</h3>
                        <p>{{ $settings?->email }}</p>
                    </div>
                </article>
            </div>

            <div class="sedes">
                <h3>Nuestras sedes</h3>
                <div class="sede-pills">
                    @php
                        $sedeOffices = $settings && is_array($settings->offices) ? $settings->offices : [];
                    @endphp
                    @foreach($sedeOffices as $office)
                        @php
                            $sedeCity = $office['city'] ?? $office['name'] ?? null;
                            $sedeLocation = $office['location'] ?? $office['url'] ?? null;
                            $sedeActive = isset($office['active']) ? (bool) $office['active'] : true;
                            if ($sedeLocation && !preg_match('~^https?://~', $sedeLocation)) {
                                $sedeLocation = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($sedeLocation);
                            }
                        @endphp
                        @if($sedeCity && $sedeActive)
                            @if($sedeLocation)
                                <a href="{{ $sedeLocation }}" target="_blank" class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="{{ ucfirst($sedeCity) }}" width="10" height="10">{{ ucfirst($sedeCity) }}</a>
                            @else
                                <span class="sede-pill"><img src="{{ asset('icons/ubicaciones.svg') }}" alt="{{ ucfirst($sedeCity) }}" width="10" height="10">{{ ucfirst($sedeCity) }}</span>
                            @endif
                        @endif
                    @endforeach
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
                            <option value="" disabled hidden {{ request('asunto') ? '' : 'selected' }}>Selecciona un asunto</option>
                            @php
                                $contactSubjects = array_filter(
                                    is_array($settings->contact_subjects ?? null) ? $settings->contact_subjects : [],
                                    fn($s) => !empty($s['active'])
                                );
                            @endphp
                            @foreach($contactSubjects as $subject)
                                <option value="{{ $subject['value'] }}" {{ request('asunto') == $subject['value'] ? 'selected' : '' }}>{{ $subject['label'] }}</option>
                            @endforeach
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
<script src="@minAsset('helin/js/contactanos.js')"></script>
@endpush
