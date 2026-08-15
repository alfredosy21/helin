@extends('web.layouts.app')

@section('title', $resource->title . ' - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/caso-clinico.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('content')
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
                @if($resource->file_path)
                    <a href="{{ asset('storage/' . $resource->file_path) }}" download class="outline-btn">
                        <i class="fas fa-download"></i> Descargar PDF
                    </a>
                @endif
            </div>
        </div>

        <div class="clinical-photo" @if($resource->image_url) style="background-image: url('{{ $resource->image_url }}'); background-size: cover; background-position: center;" @endif></div>
    </section>

    <nav class="tabs">
        <a class="tab active" href="#descripcion">Descripción</a>
        <a class="tab" href="#materiales">Materiales utilizados</a>
        <a class="tab" href="#resultados">Resultados</a>
    </nav>

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
            <section class="share-card">
                <div class="share-header">
                    <h3>Compartir este recurso</h3>
                    <button type="button" class="share-copy" onclick="copyPageLink(this)" aria-label="Copiar enlace">
                        <i class="fas fa-link"></i>
                        <span class="tooltip">Enlace copiado</span>
                    </button>
                </div>
            </section>

            <section class="advisor-card">
                <div class="advisor-head">
                    <div class="advisor-icon"><i class="fas fa-headset"></i></div>
                    <div>
                        <h3>¿Necesitas asesoría personalizada?</h3>
                        <p>Un asesor Helin puede ayudarte a resolver dudas sobre este caso y los materiales utilizados.</p>
                    </div>
                </div>
                @php
                    $settings = \App\Models\Settings::getSettings();
                    $caseWhatsApp = $settings && !empty($settings->valencia_whatsapp) ? preg_replace('/[^0-9]/', '', $settings->valencia_whatsapp) : null;
                @endphp
                @if($caseWhatsApp)
                <a href="https://wa.me/{{ $caseWhatsApp }}?text={{ urlencode('Hola, tengo dudas sobre un caso clínico de Helin.') }}" target="_blank" class="advisor-btn">
                    <i class="fab fa-whatsapp"></i> Hablar por WhatsApp
                </a>
                @endif
            </section>
        </aside>
    </section>

    <section class="bottom-cta">
        <div class="bottom-icon"><i class="fas fa-comments"></i></div>
        <div>
            <h2>¿Tienes un caso similar o necesitas orientación?</h2>
            <p>Nuestro equipo de especialistas está disponible para brindarte asesoría personalizada y acompañarte en la planificación de tus procedimientos.</p>
        </div>
        <a href="{{ route('contactanos', ['asunto' => 'recursos-clinicos']) }}" class="advisor-btn">
            <i class="material-icons">email</i> Solicitar asesoría especializada
        </a>
    </section>
</main>

@include('web.partials.beneficios')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tabs .tab');
        const panels = document.querySelectorAll('.tab-panel');

        function activateTab(targetId) {
            tabs.forEach(function (tab) {
                tab.classList.toggle('active', tab.getAttribute('href') === '#' + targetId);
            });
            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.id === targetId);
            });
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                activateTab(this.getAttribute('href').substring(1));
            });
        });

        if (window.location.hash) {
            activateTab(window.location.hash.substring(1));
        }
    });

    function copyPageLink(button) {
        navigator.clipboard.writeText(window.location.href).then(function () {
            const tooltip = button.querySelector('.tooltip');
            tooltip.classList.add('show');
            setTimeout(function () {
                tooltip.classList.remove('show');
            }, 2000);
        });
    }
</script>
@endpush
@endsection
