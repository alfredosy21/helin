@extends('web.layouts.app')

@section('title', 'Detalle Caso Clínico - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/caso-clinico.css') }}">
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
                        <strong>{{ $resource->specialty->name ?? 'General' }}</strong>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <small>Formato</small>
                        <strong>{{ $resource->type->name ?? 'Artículo' }}</strong>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <small>Fecha</small>
                        <strong style="color:#172b49">{{ $resource->created_at->format('d M, Y') ?? '15 mayo, 2024' }}</strong>
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

        @if($resource->image)
            <div class="clinical-photo" style="background-image: url('{{ asset('storage/' . $resource->image) }}'); background-size: cover; background-position: center;"></div>
        @else
            <div class="clinical-photo" aria-label="Imagen clínica de referencia"></div>
        @endif
    </section>

    <nav class="tabs">
        <a class="tab active" href="#descripcion">Descripción</a>
        <a class="tab" href="#protocolo">Protocolo</a>
        <a class="tab" href="#materiales">Materiales utilizados</a>
        <a class="tab" href="#resultados">Resultados</a>
    </nav>

    <section class="content-layout">
        <article class="case-card">
            <h2>Descripción del caso</h2>
            <p>{!! $resource->content ?? 'Contenido del caso clínico...' !!}</p>

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

        <aside>
            <section class="share-card">
                <h3>Comparte este recurso</h3>
                <div class="share-grid">
                    <a href="https://wa.me/?text={{ urlencode(route('caso-clinico', $resource->id)) }}" target="_blank" class="share-item">
                        <div class="share-icon"><i class="fab fa-whatsapp"></i></div>
                        WhatsApp
                    </a>
                    <a href="mailto:?subject=Caso Clínico Helin&body={{ urlencode(route('caso-clinico', $resource->id)) }}" class="share-item">
                        <div class="share-icon"><i class="fas fa-envelope"></i></div>
                        Correo
                    </a>
                    <a href="https://linkedin.com/sharing/share-offsite/?url={{ urlencode(route('caso-clinico', $resource->id)) }}" target="_blank" class="share-item">
                        <div class="share-icon"><i class="fab fa-linkedin-in"></i></div>
                        LinkedIn
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href)" class="share-item" style="background:none;border:none;cursor:pointer;">
                        <div class="share-icon"><i class="fas fa-link"></i></div>
                        Copiar link
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
                <a href="https://wa.me/584244669150?text={{ urlencode('Hola, tengo dudas sobre un caso clínico de Helin.') }}" target="_blank" class="advisor-btn">
                    <i class="fab fa-whatsapp"></i> Hablar por WhatsApp
                </a>
            </section>
        </aside>
    </section>

    <section class="bottom-cta">
        <div class="bottom-icon"><i class="fas fa-comments"></i></div>
        <div>
            <h2>¿Tienes dudas sobre este caso o los materiales utilizados?</h2>
            <p>Nuestros asesores clínicos están listos para ayudarte.</p>
        </div>
        <a href="{{ route('contactanos') }}" class="advisor-btn">
            <i class="fas fa-phone"></i> Consultar con un asesor
        </a>
    </section>
</main>

@include('web.partials.beneficios')
@endsection
