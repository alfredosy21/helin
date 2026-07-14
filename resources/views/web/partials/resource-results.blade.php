<div class="toolbar">
    <p>Mostrando <strong>{{ $resources->firstItem() ?? 0 }}-{{ $resources->lastItem() ?? 0 }}</strong> de <strong>{{ $resources->total() }}</strong> recursos clínicos</p>
    <select class="sort-select" id="sortSelect">
        <option value="position" {{ $sortBy == 'position' ? 'selected' : '' }}>Ordenar por defecto</option>
        <option value="recent" {{ $sortBy == 'recent' ? 'selected' : '' }}>Más recientes</option>
    </select>
</div>

@if($resources->count() > 0)
<div class="resource-grid" id="casos">
    @foreach($resources as $resource)
        @php
            $typeName     = $resource->resourceType     ? $resource->resourceType->name     : 'Desconocido';
            $specialtyName = $resource->resourceSpecialty ? $resource->resourceSpecialty->name : '';
            $tags         = [];
        @endphp
        @include('web.components.resource-card', [
            'resourceType'        => $typeName,
            'resourcePlay'        => $iconMap[$resource->type] ?? '→',
            'resourceTags'        => $specialtyName ? [$specialtyName] : [],
            'resourceTitle'       => $resource->title,
            'resourceDescription' => $resource->description,
            'resourceFormat'      => $formatMap[$resource->format] ?? '▣ Artículo',
            'resourceLink'        => $resource->type === 'video' ? 'Ver video' : ($resource->format === 'pdf' ? 'Descargar' : 'Leer'),
            'resourceUrl'         => $resource->url ?: '#',
            'resourceImage'       => $resource->image_url ?? null,
        ])
    @endforeach
</div>

<div class="pagination-wrapper">
    {{ $resources->links() }}
</div>
@else
<div class="no-results">
    <p>No se encontraron recursos que coincidan con los criterios de búsqueda.</p>
</div>
@endif
