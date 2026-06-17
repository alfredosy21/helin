@extends('web.layouts.app')

@section('title', 'Recursos Clínicos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/recursos-clinicos.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav>
        <a href="{{ route('web.home') }}">Inicio</a>
        <span>></span>
        <span>Recursos Clínicos</span>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                <span class="hero-badge">Centro de conocimiento clínico</span>
                <h1>Recursos clínicos para decisiones más precisas.</h1>
                <p>Explora casos clínicos, videos, manuales técnicos, fichas descargables y guías de referencia para profesionales odontológicos.</p>
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
                <strong>120</strong>
                <span>Recursos disponibles</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">5</div>
            <div>
                <strong>5</strong>
                <span>Especialidades clínicas</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">PDF</div>
            <div>
                <strong>48</strong>
                <span>Descargables técnicos</span>
            </div>
        </article>
        <article class="stat">
            <div class="stat-icon">24</div>
            <div>
                <strong>24</strong>
                <span>Casos clínicos</span>
            </div>
        </article>
    </section>

    <!-- Sección de Búsqueda -->
    <section class="section-head">
        <div>
            <small>Biblioteca clínica Helin</small>
            <h2>Busca, filtra y consulta recursos especializados.</h2>
        </div>
        <p>Una experiencia organizada para acceder rápidamente a contenido clínico por especialidad, formato y tipo de recurso.</p>
    </section>

    <!-- Formulario de Búsqueda -->
    <form class="resource-search">
        <input type="search" placeholder="Buscar por tema, producto o procedimiento...">
        <select>
            <option>Especialidad</option>
            <option>Cirugía Bucal</option>
            <option>Cirugía Maxilofacial</option>
            <option>Periodoncia</option>
            <option>Ortodoncia</option>
            <option>Endodoncia</option>
        </select>
        <select>
            <option>Tipo de recurso</option>
            <option>Caso clínico</option>
            <option>Video</option>
            <option>Manual</option>
            <option>Ficha técnica</option>
            <option>Guía descargable</option>
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
                @foreach(['Caso clínico' => 24, 'Video' => 18, 'Manual' => 32, 'Ficha técnica' => 28] as $tipo => $count)
                <label class="filter-check">
                    <span><input type="checkbox"> {{ $tipo }}</span>
                    <b class="count">{{ $count }}</b>
                </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Especialidad</div>
                @foreach(['Cirugía Bucal' => 36, 'Maxilofacial' => 22, 'Periodoncia' => 19, 'Ortodoncia' => 14, 'Endodoncia' => 11] as $especialidad => $count)
                <label class="filter-check">
                    <span><input type="checkbox"> {{ $especialidad }}</span>
                    <b class="count">{{ $count }}</b>
                </label>
                @endforeach
            </div>
            <div class="filter-group">
                <div class="group-title">Formato</div>
                @foreach(['Artículo' => 34, 'PDF' => 48, 'Video' => 18] as $formato => $count)
                <label class="filter-check">
                    <span><input type="checkbox"> {{ $formato }}</span>
                    <b class="count">{{ $count }}</b>
                </label>
                @endforeach
            </div>
        </aside>

        <!-- Grid de Recursos -->
        <section>
            <div class="toolbar">
                <p>Mostrando <strong>1-9</strong> de <strong>120</strong> recursos clínicos</p>
                <select class="sort-select">
                    <option>Ordenar por defecto</option>
                    <option>Más recientes</option>
                    <option>Más consultados</option>
                </select>
            </div>

            <div class="resource-grid" id="casos">
                <article class="resource-card">
                    <div class="resource-thumb">
                        <span class="resource-type">Caso clínico</span>
                        <span class="resource-play">→</span>
                    </div>
                    <div class="resource-body">
                        <div class="resource-tags">
                            <span class="tag">Cirugía Bucal</span>
                            <span class="tag">GBR</span>
                        </div>
                        <h3>Regeneración ósea guiada en zona posterior</h3>
                        <p>Protocolo clínico con biomateriales, membrana y seguimiento del caso.</p>
                        <div class="resource-footer">
                            <span class="resource-format">▣ Artículo</span>
                            <a href="#" class="resource-link">Ver caso</a>
                        </div>
                    </div>
                </article>

                <article class="resource-card">
                    <div class="resource-thumb">
                        <span class="resource-type">Video</span>
                        <span class="resource-play">▶</span>
                    </div>
                    <div class="resource-body">
                        <div class="resource-tags">
                            <span class="tag">Implantología</span>
                            <span class="tag">AB</span>
                        </div>
                        <h3>Colocación de implante con protocolo quirúrgico</h3>
                        <p>Video de referencia para planificación, inserción y control de estabilidad.</p>
                        <div class="resource-footer">
                            <span class="resource-format">▶ Video</span>
                            <a href="#" class="resource-link">Ver video</a>
                        </div>
                    </div>
                </article>

                <article class="resource-card">
                    <div class="resource-thumb">
                        <span class="resource-type">Manual</span>
                        <span class="resource-play">↓</span>
                    </div>
                    <div class="resource-body">
                        <div class="resource-tags">
                            <span class="tag">Osteosíntesis</span>
                            <span class="tag">PDF</span>
                        </div>
                        <h3>Manual técnico de placas y tornillos</h3>
                        <p>Guía descargable para selección, uso y consideraciones clínicas.</p>
                        <div class="resource-footer">
                            <span class="resource-format">▤ PDF</span>
                            <a href="#" class="resource-link">Descargar</a>
                        </div>
                    </div>
                </article>

                @include('web.components.resource-card', ['resourceType' => 'Ficha técnica', 'resourcePlay' => '↓', 'resourceTags' => ['Biomateriales', 'PDF'], 'resourceTitle' => 'Ficha técnica de membrana reabsorbible', 'resourceDescription' => 'Especificaciones, indicaciones y recomendaciones de manipulación.', 'resourceFormat' => '▤ PDF', 'resourceLink' => 'Descargar', 'resourceUrl' => '#'])

                @include('web.components.resource-card', ['resourceType' => 'Guía', 'resourcePlay' => '→', 'resourceTags' => ['Periodoncia', 'Soporte'], 'resourceTitle' => 'Guía clínica para manejo de tejidos blandos', 'resourceDescription' => 'Recomendaciones prácticas para procedimientos regenerativos.', 'resourceFormat' => '▣ Artículo', 'resourceLink' => 'Leer guía', 'resourceUrl' => '#'])

                @include('web.components.resource-card', ['resourceType' => 'Caso clínico', 'resourcePlay' => '→', 'resourceTags' => ['Maxilofacial', 'Fijación'], 'resourceTitle' => 'Reconstrucción con sistema de osteosíntesis', 'resourceDescription' => 'Resumen del abordaje, materiales utilizados y evolución clínica.', 'resourceFormat' => '▣ Artículo', 'resourceLink' => 'Ver caso', 'resourceUrl' => '#'])
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
            <a href="{{ route('web.contactanos') }}" class="btn-primary">Contactar asesor</a>
        </div>
    </section>
</main>

@include('web.partials.beneficios')
@endsection
