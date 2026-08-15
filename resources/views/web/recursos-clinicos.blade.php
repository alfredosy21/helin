@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Recursos Clínicos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/recursos-clinicos.css') }}">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                                @if($heroSection && $heroSection->status == 1 && $heroSection->status_content == 1)
                    {!! $heroSection->content !!}
                @else
                    <span class="hero-badge">Centro de conocimiento clínico</span>
                    <h1>Recursos clínicos para decisiones más precisas.</h1>
                    <p>Explora casos clínicos, videos, manuales técnicos, fichas descargables y guías de referencia para profesionales odontológicos.</p>

                    <div class="hero-buttons">
                        <a href="#recursos" class="hero-btn-primary">Explorar recursos →</a>
                        <a href="#casos" class="hero-btn-secondary">Ver casos clínicos</a>
                    </div>
                @endif
            </div>
            <div class="hero-panel">
                <div class="panel-title">
                    <h3>Encuentra contenido por tipo</h3>

                </div>
                @php
                    $heroJson = $heroSection ? (json_decode($heroSection->items, true) ?: []) : [];
                    $quickCards = $heroJson['items'] ?? [];
                @endphp
                <div class="quick-cards">
                    @if(count($quickCards) > 0)
                        @foreach($quickCards as $quickCard)
                        <article class="quick-card">
                            <div class="quick-icon">{!! $quickCard['icon'] ?? '' !!}</div>
                            <h4>{{ $quickCard['title'] ?? '' }}</h4>
                            <p>{{ $quickCard['description'] ?? '' }}</p>
                        </article>
                        @endforeach
                    @else
                    <article class="quick-card">
                        <div class="quick-icon"><i class="fa fa-file" aria-hidden="true"></i></div>
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
                        <div class="quick-icon"><i class="fa fa-cloud-download" aria-hidden="true"></i></div>
                        <h4>Fichas técnicas</h4>
                        <p>Información clave de productos y soluciones.</p>
                    </article>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="stats">
        @php
            $statsItems = [];
            if ($statsSection && $statsSection->status == 1 && $statsSection->status_content == 1) {
                $decoded = $statsSection->items ? json_decode($statsSection->items, true) : [];
                $statsItems = $decoded['items'] ?? $decoded ?? [];
            }
            $statsValues = [
                'total_resources' => $totalResources,
                'total_specialties' => $totalSpecialties,
                'total_pdfs' => $totalPDFs,
                'total_cases' => $totalCases,
            ];
        @endphp
        @if(!empty($statsItems))
            @foreach($statsItems as $statItem)
                <article class="stat">
                    <div class="stat-icon">{!! $statItem['icon'] ?? '' !!}</div>
                    <div>
                        <strong>{{ $statsValues[$statItem['value_key'] ?? ''] ?? '' }}</strong>
                        <span>{{ $statItem['label'] ?? '' }}</span>
                    </div>
                </article>
            @endforeach
        @else
            <article class="stat">
                <div class="stat-icon"><i class="fas fa-laptop-medical"></i></div>
                <div>
                    <strong>{{ $totalResources }}</strong>
                    <span>Recursos disponibles</span>
                </div>
            </article>
            <article class="stat">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div>
                    <strong>{{ $totalSpecialties }}</strong>
                    <span>Especialidades clínicas</span>
                </div>
            </article>
            <article class="stat">
                <div class="stat-icon"><i class="fas fa-download"></i></div>
                <div>
                    <strong>{{ $totalPDFs }}</strong>
                    <span>Descargables técnicos</span>
                </div>
            </article>
            <article class="stat">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <strong>{{ $totalCases }}</strong>
                    <span>Casos clínicos</span>
                </div>
            </article>
        @endif
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
                    <small>{{ $librarySection->title ?? 'Biblioteca clínica Helin' }}</small>
                    <h2>{{ $librarySection->subtitle ?? 'Busca, filtra y consulta recursos especializados.' }}</h2>
                </div>
                <p>Una experiencia organizada para acceder rápidamente a contenido clínico por especialidad, formato y tipo de recurso.</p>
            @else
                {!! $librarySection->content !!}
            @endif
        @endif
    </section>

    <!-- Formulario de Búsqueda -->
    <form class="resource-search" id="resourceSearchForm" onsubmit="return false;">
        <input type="search" name="search" placeholder="Buscar por tema, producto o procedimiento..." id="searchInput" value="{{ request('search', '') }}">
        <select name="specialty" id="specialtySelect">
            <option value="">Especialidad</option>
                        @foreach($resourceSpecialties as $specialty)
                <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>{{ $specialty->name }}</option>
            @endforeach
        </select>
        <select name="type" id="typeSelect">
            <option value="">Tipo de recurso</option>
                        @foreach($resourceTypes as $type)
                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
        <button type="button" id="searchButton">Buscar</button>
        <button type="button" id="clearFilters" class="clear-filters-text" title="Limpiar filtros" style="display:none;">
            Limpiar filtros
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
                        <span><input type="checkbox" name="resource_type[]" value="{{ $type->id }}" class="filter-checkbox" data-filter-type="resource_type" {{ in_array($type->id, (array) request('resource_type')) ? 'checked' : '' }}> {{ $type->name }}</span>
                        <b class="count" data-count-type="resource_type" data-count-id="{{ $type->id }}">{{ $count }}</b>
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
                        <span><input type="checkbox" name="resource_specialty[]" value="{{ $specialty->id }}" class="filter-checkbox" data-filter-type="resource_specialty" {{ in_array($specialty->id, (array) request('resource_specialty')) ? 'checked' : '' }}> {{ $specialty->name }}</span>
                        <b class="count" data-count-type="resource_specialty" data-count-id="{{ $specialty->id }}">{{ $count }}</b>
                    </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Formato</div>
                                @foreach($formats as $format)
                    @php
                        $formatLabel = collect($resourceTypes)->first(fn($rt) => $rt->format_label && str_contains(strtolower($rt->format_label), strtolower($format->format)));
                        $formatName = $formatLabel ? trim(str_replace(['▣', '▤', '▶'], '', $formatLabel->format_label)) : ucfirst($format->format);
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="format[]" value="{{ $format->format }}" class="filter-checkbox" data-filter-type="format" {{ in_array($format->format, (array) request('format')) ? 'checked' : '' }}> {{ $formatName }}</span>
                        <b class="count" data-count-type="format" data-count-id="{{ $format->format }}">{{ $format->count }}</b>
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
                    $resourceTypes = \App\Models\ResourceType::all()->keyBy('id');
                    $iconMap = [];
                    $formatMap = [];
                    foreach ($resourceTypes as $rt) {
                        if ($rt->icon) $iconMap[$rt->id] = $rt->icon;
                        if ($rt->format_label) $formatMap[$rt->id] = $rt->format_label;
                    }
                @endphp
                @include('web.partials.resource-results', compact('resources','sortBy','iconMap','formatMap'))
            </div>
        </section>
    </section>

    <!-- Sección Destacada -->
    <section class="featured-section">
        <div class="featured-content">
            @if($featuredSection && $featuredSection->status == 1 && $featuredSection->status_content == 1)
                <h2>{{ $featuredSection->title }}</h2>
                @if($featuredSection->content)
                    {!! $featuredSection->content !!}
                @else
                    <p class="featured-lead">Nuestro equipo comercial puede orientarte en la selección de productos y soluciones según las necesidades de tu práctica clínica.</p>
                @endif
                <a href="{{ route('contactanos') }}" class="featured-cta">
                    {{ $featuredSection->name_button ?: 'Hablar con un asesor' }}
                </a>
            @else
                <h2>¿Necesitas apoyo para tu próximo procedimiento?</h2>
                <p class="featured-lead">Nuestro equipo comercial puede orientarte en la selección de productos y soluciones según las necesidades de tu práctica clínica.</p>
                <p class="featured-text">Contacta a nuestro equipo y recibe asesoría personalizada.</p>
                <a href="{{ route('contactanos') }}" class="featured-cta">
                    Hablar con un asesor
                </a>
            @endif
        </div>
    </section>
</main>

@include('web.partials.beneficios')

@push('scripts')
<script src="{{ asset('helin/js/recursos-clinicos.js') }}"></script>
@endpush

@endsection
