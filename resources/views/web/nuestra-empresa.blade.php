@extends('web.layouts.app')

@section('title', 'Nuestra Empresa - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/nuestra-empresa.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Nuestra Empresa']
        ]
    ])

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-copy">
            @if($companyHeroSection && $companyHeroSection->status == 1 && $companyHeroSection->status_content == 1)
                @if($companyHeroSection->layout_type === 'hero_buttons')
                    <h1>{{ $companyHeroSection->title }}</h1>
                    @if($companyHeroSection->description)
                        <p>{{ $companyHeroSection->description }}</p>
                    @endif
                    <div class="hero-actions">
                        @php
                            $buttons = $companyHeroSection->buttons ? json_decode($companyHeroSection->buttons, true) : [];
                        @endphp
                        @foreach($buttons as $button)
                            @php
                                $isContact = $button['url'] === 'contactanos';
                                $isCatalogo = $button['url'] === 'catalogo';
                                $btnUrl = $isCatalogo ? route('catalogo') : ($isContact ? route('contactanos') : $button['url']);
                                $btnClass = $button['style'] === 'primary' ? 'btn-primary' : 'btn-outline';
                                $btnText = $isContact ? 'Contactar a un asesor' : rtrim(str_replace(['→', '←'], '', $button['text']));
                            @endphp
                            <a href="{{ $btnUrl }}" class="{{ $btnClass }}">
                                {{ $btnText }}
                                @if($isContact)
                                    <i class="fas fa-envelope"></i>
                                @elseif($isCatalogo)
                                    <i class="fas fa-arrow-right"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    {!! $companyHeroSection->content !!}
                @endif
            @else
                <!-- Fallback hardcoded -->
                <h1>Comprometidos con la excelencia en cada solución</h1>
                <p>En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.</p>
                <div class="hero-actions">
                    <a href="{{ route('catalogo') }}" class="btn-primary">Conoce nuestro portafolio <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('contactanos') }}" class="btn-outline"><i class="fas fa-envelope"></i> Contactar a un asesor</a>
                </div>
            @endif
        </div>
    </section>

    <!-- About Section -->
    <section class="section-card about" id="quienes-somos">
        <div>
            <span class="section-label">Quiénes somos</span>
            <h2>{{ $aboutSection->title }}</h2>
            <p>Somos más que una casa comercial: un aliado con visión quirúrgica, clínica y digital, trabajando junto a especialistas, con educación sin fronteras, ética, foco en respaldo y calidad real.</p>
            <p>Seleccionamos e importamos lo mejor en odontología y trabajamos codo a codo con ustedes para que cada procedimiento sea un reflejo de la diferencia real: la sonrisa clínica.</p>

            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-shield-halved"></i>
                    <h4>Calidad comprobada</h4>
                </div>
                <div class="feature">
                    <i class="fas fa-stethoscope"></i>
                    <h4>Asesoría especializada</h4>
                </div>
                <div class="feature">
                    <i class="fas fa-table-cells-large"></i>
                    <h4>Portafolio completo</h4>
                </div>
                <div class="feature">
                    <i class="far fa-handshake"></i>
                    <h4>Respaldo y confianza</h4>
                </div>
            </div>
        </div>
        <div class="about-visual">
            <div class="implants-row">
                <div class="implant"></div>
                <div class="implant"></div>
                <div class="implant"></div>
                <div class="implant"></div>
            </div>
            <div class="kit-base"></div>
        </div>
    </section>

    <!-- Mission and Vision -->
    <section id="mision-vision">
        <span class="section-label">Misión y visión</span>
        <div class="mission-vision">
            <article class="mv-card">
                <div class="mv-icon"><i class="fas fa-crosshairs"></i></div>
                <div>
                    <h3>Misión</h3>
                    <p>Acompañar a odontólogos, implantólogos, cirujanos maxilofaciales y especialistas con soluciones integrales para sus procedimientos, combinando productos de alto valor clínico, asesoría técnica y una atención cercana que facilite su trabajo antes, durante y después de cada caso.</p>
                </div>
            </article>
            <article class="mv-card">
                <div class="mv-icon"><i class="fas fa-binoculars"></i></div>
                <div>
                    <h3>Visión</h3>
                    <p>Convertirnos en el aliado estratégico de referencia para los especialistas en odontología quirúrgica en Venezuela, ayudándolos a resolver sus casos con mayor seguridad, precisión y respaldo técnico.</p>
                </div>
            </article>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-card team" id="nuestro-team">
        <div>
            <span class="section-label">Nuestro team</span>
            <h2>{{ $teamSection->title }}</h2>
            <p>{{ $teamSection->description ?: strip_tags($teamSection->content) }}</p>
            <a href="{{ route('contactanos', ['asunto' => 'informacion-comercial']) }}" class="btn-outline">Solicitar atención comercial <i class="fas fa-comments"></i></a>
        </div>
        <div class="team-photo">
            <img src="{{ $teamSection->image ? asset('storage/' . $teamSection->image) : asset('images/team_helin_test.png') }}" alt="Team Helin">
        </div>
    </section>

    <!-- Allies Section -->
    <section class="section-card allies" id="nuestros-aliados">
        <div>
            <span class="section-label">Nuestros aliados</span>
            <h2>Trabajamos junto a marcas líderes</h2>
            <p>Aliados estratégicos de reconocimiento mundial, que comparten los valores y los mismos de ética, y calidad clínica.</p>
        </div>
        <div class="logos-grid">
            <div class="brand-card"><img src="{{ asset('images/gdt_logo.jpg') }}" alt="GDT"></div>
            <div class="brand-card"><img src="{{ asset('images/ab_logo.jpg') }}" alt="AB"></div>
            <div class="brand-card"><img src="{{ asset('images/bluem_logo.jpg') }}" alt="Bluem"></div>
            <div class="brand-card"><img src="{{ asset('images/logo_czmedietch.jpg') }}" alt="CZ Medietch"></div>
            <div class="brand-card"><img src="{{ asset('images/tealth_logo.jpg') }}" alt="Tealth"></div>
            <div class="brand-card"><img src="{{ asset('images/tissum_logo.jpg') }}" alt="Tissum"></div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div>
            <h2>¿Listo para transformar tu práctica clínica?</h2>
            <p>Somos tu aliado en cada paso hacia la excelencia de la salud bucal.</p>
        </div>
        <div class="cta-actions">
            @php
                $settings = \App\Models\Settings::getSettings();
                $companyWhatsApp = $settings && !empty($settings->valencia_whatsapp) ? preg_replace('/[^0-9]/', '', $settings->valencia_whatsapp) : '584244669150';
            @endphp
            <a href="https://wa.me/{{ $companyWhatsApp }}?text={{ urlencode('Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.') }}" target="_blank" class="btn-primary"><i class="fab fa-whatsapp"></i> Hablar con un asesor</a>
            <a href="{{ route('contactanos') }}" class="btn-outline"><i class="fas fa-envelope"></i> Enviar un correo</a>
        </div>
    </section>
</main>
@endsection
