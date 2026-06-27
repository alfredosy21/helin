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
            <span class="hero-badge">Nuestra empresa</span>
            <h1>Comprometidos con la excelencia en cada solución</h1>
            <p>
                En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.
            </p>
            <div class="hero-actions">
                <a href="{{ route('catalogo') }}" class="btn-primary">Conoce nuestro portafolio →</a>
                <a href="{{ route('contactanos') }}" class="btn-outline">☏ Háblale con un asesor</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-card about" id="quienes-somos">
        @php
            $aboutSection = \App\Models\Sections::find(\App\Models\Sections::ABOUT_US);
        @endphp
        @if($aboutSection && $aboutSection->status_content)
            <div>
                <span class="section-label">Quiénes somos</span>
                <h2>{{ $aboutSection->title }}</h2>
                {!! $aboutSection->content !!}
            </div>
        @endif

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
        @php
            $missionSection = \App\Models\Sections::find(\App\Models\Sections::MISSION_VISION);
        @endphp
        @if($missionSection && $missionSection->status_content)
            <span class="section-label">Misión y visión</span>
            {!! $missionSection->content !!}
        @endif
    </section>

    <!-- Team Section -->
    <section class="section-card team" id="nuestro-team">
        @php
            $teamSection = \App\Models\Sections::find(\App\Models\Sections::TEAM);
        @endphp
        @if($teamSection && $teamSection->status_content)
            <div>
                <span class="section-label">Nuestro team</span>
                <h2>{{ $teamSection->title }}</h2>
                @if($teamSection->description)
                    <p>{{ $teamSection->description }}</p>
                @else
                    {!! $teamSection->content !!}
                @endif

                @php
                    $buttons = $teamSection->buttons ? json_decode($teamSection->buttons, true) : [];
                @endphp
                @if(!empty($buttons))
                    @foreach($buttons as $button)
                        <a href="{{ $button['url'] ?: route('contactanos') }}"
                           class="btn-outline {{ $button['style'] ?? '' }}"
                           @if(isset($button['target']) && $button['target'] === '_blank') target="_blank" @endif>
                           {{ $button['text'] ?? '☏ Conoce al equipo' }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ $teamSection->url_button ?: route('contactanos') }}" class="btn-outline">{{ $teamSection->name_button ?: '☏ Conoce al equipo' }}</a>
                @endif
            </div>
        @endif

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
        @php
            $alliesSection = \App\Models\Sections::find(\App\Models\Sections::ALLIES);
        @endphp
        @if($alliesSection && $alliesSection->status_content)
            <div>
                <span class="section-label">Nuestros aliados</span>
                <h2>{{ $alliesSection->title }}</h2>
                {!! $alliesSection->content !!}
            </div>
            <div>
                {!! $alliesSection->content !!}
            </div>
        @endif
    </section>

    <!-- CTA Section -->
    <section class="cta">
        @php
            $ctaSection = \App\Models\Sections::find(\App\Models\Sections::CTA_COMPANY);
        @endphp
        @if($ctaSection && $ctaSection->status_content)
            <div>
                <h2>{{ $ctaSection->title }}</h2>
                @if($ctaSection->description)
                    <p>{{ $ctaSection->description }}</p>
                @else
                    {!! $ctaSection->content !!}
                @endif
            </div>
            <div class="cta-actions">
                @php
                    $buttons = $ctaSection->buttons ? json_decode($ctaSection->buttons, true) : [];
                @endphp
                @if(!empty($buttons))
                    @foreach($buttons as $button)
                        <a href="{{ $button['url'] }}"
                           class="btn-{{ $button['style'] ?? 'primary' }}"
                           @if(isset($button['target']) && $button['target'] === '_blank') target="_blank" @endif>
                           {{ $button['text'] }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ $ctaSection->url_button ?: '#' }}" class="btn-primary">{{ $ctaSection->name_button ?: 'Contactar' }}</a>
                @endif
            </div>
        @endif
    </section>
</main>
@endsection
