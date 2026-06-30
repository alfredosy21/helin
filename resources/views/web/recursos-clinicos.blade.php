@extends('web.layouts.app')

@section('title', 'Recursos Clínicos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/recursos-clinicos.css') }}">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                                @if($heroSection && $heroSection->status == 1 && $heroSection->status_content == 1)
                    @if($heroSection->layout_type === 'text_simple')
                        <span class="hero-badge">{{ $heroSection->title }}</span>
                        <h1>{!! $heroSection->content !!}</h1>
                    @else
                        {!! $heroSection->content !!}
                    @endif
                @else
                    <span class="hero-badge">Centro de conocimiento clínico</span>
                    <h1>Recursos clínicos para decisiones más precisas.</h1>
                    <p>Explora casos clínicos, videos, manuales técnicos, fichas descargables y guías de referencia para profesionales odontológicos.</p>
                @endif

                <div class="hero-buttons">
                    <a href="#recursos" class="hero-btn-primary">Explorar recursos →</a>
                    <a href="#casos" class="hero-btn-secondary">Ver casos clínicos</a>
                </div>
            </div>
            <div class="hero-panel">
                <div class="panel-title">
                    <h3>Encuentra contenido por tipo</h3>
                    <span class="badge">Actualizado</span>
                </div>
                <div class="quick-cards">
                    <article class="quick-card">
                        <div class="quick-icon">C</div>
                        <h4>Casos clínicos</h4>
                        <p>Protocolos, materiales utilizados y resultados.</p>
                    </article>
                    <article class="quick-card">
                        <div class="quick-icon">▶</div>
                        <h4>Videos</h4>
                        <p>Contenido audiovisual para soporte técnico.</p>
                    </article>
                    <article class="quick-card">
                        <div class="quick-icon">PDF</div>
                        <h4>Manuales</h4>
                        <p>Documentos técnicos y descargables.</p>
                    </article>
                    <article class="quick-card">
                        <div class="quick-icon">F</div>
                        <h4>Fichas técnicas</h4>
                        <p>Información clave de productos y soluciones.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="stats">
                <article class="stat">
            <div class="stat-icon">+</div>
            <div>
                <strong>{{ $totalResources }}</strong>
                <span>Recursos disponibles</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">5</div>
            <div>
                <strong>{{ $totalSpecialties }}</strong>
                <span>Especialidades clínicas</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">PDF</div>
            <div>
                <strong>{{ $totalPDFs }}</strong>
                <span>Descargables técnicos</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">24</div>
            <div>
                <strong>{{ $totalCases }}</strong>
                <span>Casos clínicos</span>
            </div>
        </article>
    </section>

<main class="container mx-auto px-4 py-8">
    <!-- Sección de Búsqueda -->
    <section class="section-head">
                @if($librarySection && $librarySection->status == 1 && $librarySection->status_content == 1)
            @if($librarySection->layout_type === 'search_features')
                @php
                    $items = $librarySection->items ? json_decode($librarySection->items, true) : [];
                    $searchFeatures = $items['search_features'] ?? [];
                @endphp
                <div>
                    <small>Biblioteca clínica Helin</small>
                    <h2>Busca, filtra y consulta recursos especializados.</h2>
                </div>
                <p>Una experiencia organizada para acceder rápidamente a contenido clínico por especialidad, formato y tipo de recurso.</p>
            @else
                {!! $librarySection->content !!}
            @endif
        @endif
    </section>

    <!-- Formulario de Búsqueda -->
    <form class="resource-search" id="resourceSearchForm" onsubmit="return false;">
        <input type="search" name="search" placeholder="Buscar por tema, producto o procedimiento..." id="searchInput">
        <select name="specialty" id="specialtySelect">
            <option value="">Especialidad</option>
                        @foreach($resourceSpecialties as $specialty)
                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
            @endforeach
        </select>
        <select name="type" id="typeSelect">
            <option value="">Tipo de recurso</option>
                        @foreach($resourceTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
        <button type="button" id="searchButton">Buscar</button>
        <button type="button" id="clearFilters" class="clear-filters-icon" title="Limpiar filtros" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </form>

    <!-- Layout Principal -->
    <section class="layout">
        <!-- Sidebar Filtros -->
        <aside class="sidebar">
            <h3>Filtros</h3>
            <div class="filter-group">
                <div class="group-title">Tipo de recurso</div>
                @foreach($resourceTypeCounts as $type)
                    @php
                        $count = $type->resources_count;
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="resource_type[]" value="{{ $type->id }}" class="filter-checkbox" data-filter-type="resource_type"> {{ $type->name }}</span>
                        <b class="count">{{ $count }}</b>
                    </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Especialidad</div>
                @foreach($resourceSpecialtyCounts as $specialty)
                    @php
                        $count = $specialty->resources_count;
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="resource_specialty[]" value="{{ $specialty->id }}" class="filter-checkbox" data-filter-type="resource_specialty"> {{ $specialty->name }}</span>
                        <b class="count">{{ $count }}</b>
                    </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Formato</div>
                                @foreach($formats as $format)
                    @php
                        $formatName = match($format->format) {
                            'article' => 'Artículo',
                            'pdf' => 'PDF',
                            'video' => 'Video',
                            default => ucfirst($format->format)
                        };
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="format[]" value="{{ $format->format }}" class="filter-checkbox" data-filter-type="format"> {{ $formatName }}</span>
                        <b class="count">{{ $format->count }}</b>
                    </label>
                @endforeach
            </div>
        </aside>

        <!-- Grid de Recursos -->
        <section id="resourcesContainer">
            <!-- Indicador de carga -->
            <div id="loadingIndicator" class="loading-indicator hidden">
                <div class="spinner"></div>
                <p>Cargando recursos...</p>
            </div>

            <!-- Contenido dinámico -->
            <div id="resourcesContent">
                @php
                    $sortBy    = request('sort', 'position');
                    $iconMap   = ['case_study'=>'→','video'=>'▶','manual'=>'↓','technical_sheet'=>'↓','guide'=>'→','downloadable_guide'=>'→'];
                    $formatMap = ['article'=>'▣ Artículo','pdf'=>'▤ PDF','video'=>'▶ Video'];
                @endphp
                @include('web.partials.resource-results', compact('resources','sortBy','iconMap','formatMap'))
            </div>
        </section>
    </section>

    <!-- Sección Destacada -->
    <section class="featured-section">
        <div>
            <h2>Contenido clínico pensado para acompañar tu práctica.</h2>
            <p>Centraliza recursos técnicos, casos clínicos y materiales descargables en una plataforma clara, rápida y alineada al portafolio de Helin.</p>
        </div>
        <div class="feature-box">
            <strong>Asesoría especializada</strong>
            <p>Conecta cada recurso con productos, casos de uso y soporte comercial para profesionales.</p>
            <a href="{{ route('contactanos') }}" class="btn-primary">Contactar asesor</a>
        </div>
    </section>
</main>

@include('web.partials.beneficios')

@push('scripts')
<script src="{{ asset('helin/js/recursos-clinicos.js') }}"></script>
@endpush

@endsection
