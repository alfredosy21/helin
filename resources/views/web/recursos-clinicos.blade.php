@extends('web.layouts.app')

@section('title', 'Recursos Clínicos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/recursos-clinicos.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                @php
                    $heroSection = \App\Models\Sections::find(\App\Models\Sections::CLINICAL_RESOURCES_HERO);
                @endphp
                @if($heroSection && $heroSection->status_content)
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
        @php
            // Obtener estadísticas dinámicas de la base de datos
            $totalResources = \App\Models\Resource::where('is_active', true)->count();
            $totalSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->count();
            $totalPDFs = \App\Models\Resource::where('is_active', true)->where('format', 'pdf')->count();
            $totalCases = \App\Models\Resource::where('is_active', true)->where('type', 'case')->count();
        @endphp
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

    <!-- Sección de Búsqueda -->
    <section class="section-head">
        @php
            $librarySection = \App\Models\Sections::find(\App\Models\Sections::CLINICAL_LIBRARY);
        @endphp
        @if($librarySection && $librarySection->status_content)
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
                <div>
                    <small>Biblioteca clínica Helin</small>
                    <h2>{{ $librarySection->title }}</h2>
                </div>
                @if($librarySection->description)
                    <p>{{ $librarySection->description }}</p>
                @else
                    {!! $librarySection->content !!}
                @endif
            @endif
        @endif
    </section>

    <!-- Formulario de Búsqueda -->
    <form class="resource-search" id="resourceSearchForm">
        <input type="search" name="search" placeholder="Buscar por tema, producto o procedimiento..." id="searchInput">
        <select name="specialty" id="specialtySelect">
            <option value="">Especialidad</option>
            @php
                $resourceSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->orderBy('name')->get();
            @endphp
            @foreach($resourceSpecialties as $specialty)
                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
            @endforeach
        </select>
        <select name="type" id="typeSelect">
            <option value="">Tipo de recurso</option>
            @php
                $resourceTypes = \App\Models\ResourceType::where('is_active', true)->orderBy('name')->get();
            @endphp
            @foreach($resourceTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
        <button type="submit">Buscar</button>
    </form>

    <!-- Layout Principal -->
    <section class="layout">
        <!-- Sidebar Filtros -->
        <aside class="sidebar">
            <h3>Filtros</h3>
            <div class="filter-group">
                <div class="group-title">Tipo de recurso</div>
                @php
                    $resourceTypes = \App\Models\ResourceType::where('is_active', true)->orderBy('name')->get();
                @endphp
                @foreach($resourceTypes as $type)
                    @php
                        $count = \App\Models\Resource::where('resource_type_id', $type->id)->where('is_active', true)->count();
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="resource_type[]" value="{{ $type->id }}" class="filter-checkbox" data-filter-type="resource_type"> {{ $type->name }}</span>
                        <b class="count">{{ $count }}</b>
                    </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Especialidad</div>
                @php
                    $resourceSpecialties = \App\Models\ResourceSpecialty::where('is_active', true)->orderBy('name')->get();
                @endphp
                @foreach($resourceSpecialties as $specialty)
                    @php
                        $count = \App\Models\Resource::where('resource_specialty_id', $specialty->id)->where('is_active', true)->count();
                    @endphp
                    <label class="filter-check">
                        <span><input type="checkbox" name="resource_specialty[]" value="{{ $specialty->id }}" class="filter-checkbox" data-filter-type="resource_specialty"> {{ $specialty->name }}</span>
                        <b class="count">{{ $count }}</b>
                    </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Formato</div>
                @php
                    // Obtener formatos únicos desde la base de datos
                    $formats = \App\Models\Resource::where('is_active', true)
                        ->select('format')
                        ->selectRaw('count(*) as count')
                        ->groupBy('format')
                        ->orderByRaw('count(*) DESC')
                        ->get();
                @endphp
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
                    // Obtener parámetros de filtro
                    $search = request('search', '');
                    $typeId = request('type', '');
                    $specialtyId = request('specialty', '');
                    $format = request('format', '');
                    $sortBy = request('sort', 'position');

                    // Construir query
                    $resourcesQuery = \App\Models\Resource::where('is_active', true);

                    // Aplicar filtros
                    if ($search) {
                        $resourcesQuery->where(function($query) use ($search) {
                            $query->where('title', 'like', '%' . $search . '%')
                                  ->orWhere('description', 'like', '%' . $search . '%');
                        });
                    }

                    if ($typeId) {
                        $resourcesQuery->where('resource_type_id', $typeId);
                    }

                    if ($specialtyId) {
                        $resourcesQuery->where('resource_specialty_id', $specialtyId);
                    }

                    if ($format) {
                        if (is_array($format)) {
                            $resourcesQuery->whereIn('format', $format);
                        } else {
                            $resourcesQuery->where('format', $format);
                        }
                    }

                    // Aplicar ordenamiento
                    switch($sortBy) {
                        case 'recent':
                            $resourcesQuery->orderBy('created_at', 'desc');
                            break;
                        case 'views':
                            $resourcesQuery->orderBy('views', 'desc');
                            break;
                        case 'position':
                        default:
                            $resourcesQuery->orderBy('position', 'asc');
                            break;
                    }

                    $resources = $resourcesQuery->with(['resourceType', 'resourceSpecialty'])->paginate(12);
                @endphp

                <div class="toolbar">
                    <p>Mostrando <strong>{{ $resources->firstItem() ?? 0 }}-{{ $resources->lastItem() ?? 0 }}</strong> de <strong>{{ $resources->total() }}</strong> recursos clínicos</p>
                    <select class="sort-select" id="sortSelect">
                        <option value="position" {{ request('sort') == 'position' ? 'selected' : '' }}>Ordenar por defecto</option>
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Más recientes</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Más consultados</option>
                    </select>
                </div>

            @if($resources->count() > 0)
            <div class="resource-grid" id="casos">
                @foreach($resources as $resource)
                    @php
                        $iconMap = [
                            'case_study' => '→',
                            'video' => '▶',
                            'manual' => '↓',
                            'technical_sheet' => '↓',
                            'guide' => '→',
                            'downloadable_guide' => '→'
                        ];
                        $formatMap = [
                            'article' => '▣ Artículo',
                            'pdf' => '▤ PDF',
                            'video' => '▶ Video'
                        ];
                        $tags = is_string($resource->tags) ? json_decode($resource->tags, true) : [];

                        // Obtener nombres relacionados
                        $typeName = $resource->resourceType ? $resource->resourceType->name : 'Desconocido';
                        $specialtyName = $resource->resourceSpecialty ? $resource->resourceSpecialty->name : '';
                    @endphp
                    @include('web.components.resource-card', [
                        'resourceType' => $typeName,
                        'resourcePlay' => $iconMap[$resource->type] ?? '→',
                        'resourceTags' => array_merge($tags, $specialtyName ? [$specialtyName] : []),
                        'resourceTitle' => $resource->title,
                        'resourceDescription' => $resource->description,
                        'resourceFormat' => $formatMap[$resource->format] ?? '▣ Artículo',
                        'resourceLink' => $resource->type === 'video' ? 'Ver video' : ($resource->format === 'pdf' ? 'Descargar' : 'Leer'),
                        'resourceUrl' => $resource->url ?: '#'
                    ])
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="pagination-wrapper">
                {{ $resources->appends(request()->query())->links() }}
            </div>
            @else
            <div class="no-results">
                <p>No se encontraron recursos que coincidan con los criterios de búsqueda.</p>
                <a href="{{ route('recursos-clinicos') }}" class="btn-outline">Limpiar filtros</a>
            </div>
            @endif
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
<script>
// Sistema simple de filtros AJAX
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('resourceSearchForm');
    const searchInput = document.getElementById('searchInput');
    const specialtySelect = document.getElementById('specialtySelect');
    const typeSelect = document.getElementById('typeSelect');
    const sortSelect = document.getElementById('sortSelect');
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    const resourcesContent = document.getElementById('resourcesContent');
    const loadingIndicator = document.getElementById('loadingIndicator');

    let debounceTimer = null;

    function showLoading() {
        if (loadingIndicator) {
            loadingIndicator.classList.remove('hidden');
            if (resourcesContent) {
                resourcesContent.style.opacity = '0.5';
            }
        }
    }

    function hideLoading() {
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
            if (resourcesContent) {
                resourcesContent.style.opacity = '1';
            }
        }
    }

    function getFilters() {
        const params = new URLSearchParams();
        console.log('Obteniendo filtros...');

        // Búsqueda
        if (searchInput && searchInput.value.trim()) {
            params.append('search', searchInput.value.trim());
        }

        // Selects
        if (specialtySelect && specialtySelect.value) {
            params.append('specialtyId', specialtySelect.value);
        }

        if (typeSelect && typeSelect.value) {
            params.append('typeId', typeSelect.value);
        }

        if (sortSelect && sortSelect.value) {
            params.append('sort', sortSelect.value);
        }

        // Checkboxes
        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const filterType = checkbox.dataset.filterType;
                params.append(filterType, checkbox.value);
            }
        });

        const queryString = params.toString();
        console.log('Query string generada:', queryString);
        return queryString;
    }

    async function applyFilters() {
        try {
            showLoading();

            const queryString = getFilters();
            const response = await fetch(`/api/recursos-clinicos/filtrar?${queryString}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();

            if (data.success && resourcesContent) {
                // Actualizar el contenido principal
                let html = '';
                if (data.toolbar) {
                    html += data.toolbar;
                }
                if (data.html) {
                    html += data.html;
                }
                if (data.pagination) {
                    html += data.pagination;
                }
                resourcesContent.innerHTML = html;

                // Re-inicializar el select de ordenamiento si existe
                const newSortSelect = document.getElementById('sortSelect');
                if (newSortSelect) {
                    newSortSelect.addEventListener('change', applyFilters);
                }
            } else {
                throw new Error(data.message || 'Error desconocido');
            }

        } catch (error) {
            console.error('Error al aplicar filtros:', error);
            if (resourcesContent) {
                resourcesContent.innerHTML = '<div class="error-message">Ocurrió un error al cargar los recursos. Por favor, intenta nuevamente.</div>';
            }
        } finally {
            hideLoading();
        }
    }

    // Event listeners
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 500);
        });
    }

    [specialtySelect, typeSelect, sortSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', applyFilters);
        }
    });

    console.log('Checkboxes encontrados:', filterCheckboxes.length);
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox cambiado:', checkbox.value, checkbox.checked);
            applyFilters();
        });
    });
});
</script>

@push('styles')
<style>
/* Estilos para el sistema de filtros AJAX */
.loading-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    text-align: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.hidden {
    display: none !important;
}

.error-message {
    background-color: #fee;
    border: 1px solid #fcc;
    color: #c33;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    text-align: center;
}

/* Transiciones suaves */
#resourcesContent {
    transition: opacity 0.3s ease;
}

/* Efecto hover en filtros */
.filter-check:hover {
    background-color: rgba(107, 194, 195, 0.1);
    transition: background-color 0.2s ease;
}

/* Estados activos de filtros */
.filter-checkbox:checked + span {
    font-weight: 600;
    color: #06b6d4;
}

/* Responsive para loading */
@media (max-width: 768px) {
    .loading-indicator {
        padding: 2rem 1rem;
    }

    .spinner {
        width: 32px;
        height: 32px;
    }
}
</style>
@endpush
@endpush
@endsection
