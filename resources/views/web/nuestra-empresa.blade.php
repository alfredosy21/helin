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
                    <span class="hero-badge">{{ $companyHeroSection->subtitle ?? 'Nuestra empresa' }}</span>
                    <h1>{{ $companyHeroSection->title }}</h1>
                    @if($companyHeroSection->description)
                        <p>{{ $companyHeroSection->description }}</p>
                    @endif
                    <div class="hero-actions">
                        @php
                            $buttons = $companyHeroSection->buttons ? json_decode($companyHeroSection->buttons, true) : [];
                        @endphp
                        @foreach($buttons as $button)
                            <a href="{{ $button['url'] === 'catalogo' ? route('catalogo') : ($button['url'] === 'contactanos' ? route('contactanos') : $button['url']) }}"
                               class="{{ $button['style'] === 'primary' ? 'btn-primary' : 'btn-outline' }}">
                                {{ $button['text'] }}
                            </a>
                        @endforeach
                    </div>
                @else
                    {!! $companyHeroSection->content !!}
                @endif
            @else
                <!-- Fallback hardcoded -->
                <span class="hero-badge">Nuestra empresa</span>
                <h1>Comprometidos con la excelencia en cada solución</h1>
                <p>En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.</p>
                <div class="hero-actions">
                    <a href="{{ route('catalogo') }}" class="btn-primary">Conoce nuestro portafolio →</a>
                    <a href="{{ route('contactanos') }}" class="btn-outline">☏ Háblale con un asesor</a>
                </div>
            @endif
        </div>
    </section>

    <!-- About Section -->
    <section class="section-card about" id="quienes-somos">
        <div>
            <span class="section-label">Quiénes somos</span>
            <h2>{{ $aboutSection->title }}</h2>
            {!! $aboutSection->content !!}
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
        {!! $missionSection->content !!}
    </section>

    <!-- Team Section -->
    <section class="section-card team" id="nuestro-team">
        <div>
            <span class="section-label">Nuestro team</span>
            <h2>{{ $teamSection->title }}</h2>
            <p>{{ $teamSection->description ?: strip_tags($teamSection->content) }}</p>
            <a href="{{ $teamSection->url_button }}" class="btn-outline">{{ $teamSection->name_button }}</a>
        </div>
        <div class="team-photo">
            <div class="people">
                @for($i = 0; $i < 8; $i++)
                    <div class="person"></div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Allies Section -->
    <section class="section-card allies" id="nuestros-aliados">
        <div>
            <span class="section-label">Nuestros aliados</span>
            <h2>{{ $alliesSection->title }}</h2>
            {!! $alliesSection->content !!}
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div>
            <h2>{{ $ctaSection->title }}</h2>
            {!! $ctaSection->content !!}
        </div>
        <div class="cta-actions">
            <a href="https://wa.me/584241232025" target="_blank" class="btn-primary">☏ Háblale con WhatsApp</a>
            <a href="{{ route('contactanos') }}" class="btn-outline">✉ Escríbenos por correo</a>
        </div>
    </section>
</main>
@endsection
