@extends('web.layouts.app')

@section('title', $resource->title . ' - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/caso-clinico.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
@php
    $shareSection = \App\Models\Sections::find(\App\Models\Sections::CASE_SHARE);
    $advisorSection = \App\Models\Sections::find(\App\Models\Sections::CASE_ADVISOR);
    $bottomCtaSection = \App\Models\Sections::find(\App\Models\Sections::CASE_BOTTOM_CTA);

    $settings = \App\Models\Settings::getSettings();
    $caseWhatsApp = $settings && !empty($settings->valencia_whatsapp) ? preg_replace('/[^0-9]/', '', $settings->valencia_whatsapp) : null;
@endphp
<main class="page">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Recursos Clínicos', 'url' => route('recursos-clinicos')],
            ['label' => 'Caso Clínico'],
            ['label' => $resource->title ?? 'Detalle', 'spanAttributes' => 'class="text-turquesa font-medium"']
        ]
    ])

    <section class="hero">
        <div class="hero-copy">
            <span class="badge">Caso Clínico</span>
            <h1>{{ $resource->title ?? 'Caso Clínico' }}</h1>
            <p>{{ $resource->description ?? 'Descripción del caso clínico...' }}</p>

            <div class="meta-row">
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-heartbeat"></i></div>
                    <div>
                        <small>Especialidad</small>
                        <strong>{{ $resource->resourceSpecialty->name ?? 'General' }}</strong>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <small>Formato</small>
                        <strong>{{ $resource->resourceType->name ?? 'Artículo' }}</strong>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <small>Fecha</small>
                        <strong style="color:#172b49">{{ $resource->created_at->format('d M, Y') }}</strong>
                    </div>
                </div>
            </div>

            <div class="hero-actions">
                @if($resource->video_url)
                <a href="{{ $resource->video_url }}" target="_blank" class="primary-btn">
                    <i class="fas fa-play"></i> Ver video completo
                </a>
                @endif
                @if($resource->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($resource->file_path))
                    <a href="{{ asset('storage/' . $resource->file_path) }}" download class="outline-btn">
                        <i class="fas fa-download"></i> Descargar {{ $resource->format === 'video' ? 'Video' : 'PDF' }}
                    </a>
                @endif
            </div>
        </div>

        <div class="clinical-photo" @if($resource->image_url) style="background-image: url('{{ $resource->image_url }}'); background-size: cover; background-position: center;" @endif></div>
    </section>

    <div class="tabs-wrapper">
        <nav class="tabs" id="tabsNav">
            <a class="tab active" href="#descripcion">Descripción</a>
            <a class="tab" href="#materiales">Materiales utilizados</a>
            <a class="tab" href="#resultados">Resultados</a>
        </nav>
        <span class="tabs-scroll-hint" id="tabsScrollHint" aria-hidden="true">
            <i class="fas fa-chevron-right"></i>
        </span>
    </div>

    <section class="content-layout">
        <div class="tab-panels">
            <section id="descripcion" class="tab-panel active">
                <article class="case-card">
                    <h2>Descripción del caso</h2>
                    <p>{!! $resource->content !!}</p>

                    @if($resource->image_url)
                        <img src="{{ $resource->image_url }}" alt="{{ $resource->title }}" class="case-detail-image" loading="lazy">
                    @endif

                    @if($resource->diagnosis)
                        <h3>Diagnóstico inicial</h3>
                        <ul>
                            @foreach(explode("\n", $resource->diagnosis) as $item)
                                @if(trim($item))
                                    <li>{{ trim($item) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                    @if($resource->gallery && count($resource->gallery) > 0)
                        <div class="case-gallery">
                            @foreach($resource->gallery as $image)
                                <div class="case-img" style="background-image: url('{{ asset('storage/' . $image) }}'); background-size: cover; background-position: center;"></div>
                            @endforeach
                        </div>
                    @endif
                </article>
            </section>

            <section id="materiales" class="tab-panel">
                <article class="case-card">
                    <h2>Materiales utilizados</h2>
                    @if($resource->materials)
                        @php $materialsList = explode("\n", $resource->materials); @endphp
                        <ul>
                            @foreach($materialsList as $material)
                                @if(trim($material))
                                    <li>{{ trim($material) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p>Información de materiales próximamente.</p>
                    @endif
                </article>
            </section>

            <section id="resultados" class="tab-panel">
                <article class="case-card">
                    <h2>Resultados</h2>
                    @if($resource->results)
                        <p>{!! nl2br(e($resource->results)) !!}</p>
                    @else
                        <p>Información de resultados próximamente.</p>
                    @endif
                </article>
            </section>
        </div>

        <aside>
            @if($shareSection && $shareSection->status == 1 && $shareSection->status_content == 1)
            <section class="share-card">
                <div class="share-header">
                    <h3>{{ $shareSection->title }}</h3>
                    <button type="button" class="share-copy" onclick="copyPageLink(this)" aria-label="Copiar enlace">
                        <i class="fas fa-link"></i>
                        <span class="tooltip">Enlace copiado</span>
                    </button>
                </div>
            </section>
            @endif

            @if($advisorSection && $advisorSection->status == 1 && $advisorSection->status_content == 1)
            <section class="advisor-card">
                <div class="advisor-head">
                    <div class="advisor-icon"><i class="fas fa-headset"></i></div>
                    <div>
                        <h3>{{ $advisorSection->title }}</h3>
                        @if($advisorSection->description)
                        <p>{{ $advisorSection->description }}</p>
                        @endif
                    </div>
                </div>
                @if($caseWhatsApp)
                <a href="https://wa.me/{{ $caseWhatsApp }}?text={{ urlencode('Hola, tengo dudas sobre un caso clínico de Helin.') }}" target="_blank" class="advisor-btn">
                    <i class="fab fa-whatsapp"></i> {{ $advisorSection->name_button ?: 'Hablar por WhatsApp' }}
                </a>
                @endif
            </section>
            @endif
        </aside>
    </section>

    @if($bottomCtaSection && $bottomCtaSection->status == 1 && $bottomCtaSection->status_content == 1)
    <section class="bottom-cta">
        <div class="bottom-icon"><i class="fas fa-comments"></i></div>
        <div>
            <h2>{{ $bottomCtaSection->title }}</h2>
            @if($bottomCtaSection->description)
            <p>{{ $bottomCtaSection->description }}</p>
            @endif
        </div>
        <a href="{{ route('contactanos', ['asunto' => 'recursos-clinicos']) }}" class="advisor-btn">
            <i class="material-icons">email</i> {{ $bottomCtaSection->name_button ?: 'Solicitar asesoría especializada' }}
        </a>
    </section>
    @endif
</main>

@include('web.partials.beneficios')

@push('scripts')
<script src="@minAsset('helin/js/caso-clinico.js')"></script>
@endpush
@endsection
