<div class="toolbar">
    <p>Mostrando <strong>{{ $resources->firstItem() ?? 0 }}-{{ $resources->lastItem() ?? 0 }}</strong> de <strong>{{ $resources->total() }}</strong> recursos clínicos</p>
    <select class="sort-select" id="sortSelect">
        <option value="position" {{ $sortBy == 'position' ? 'selected' : '' }}>Ordenar por defecto</option>
        <option value="recent" {{ $sortBy == 'recent' ? 'selected' : '' }}>Más recientes</option>
    </select>
</div>

@if($resources->count() > 0)
@php
    $fallbackImages = [
        'images/regeneracion-osea-guiada-recursos1.jpg',
        'images/PLCAS_RCURSOS.jpg',
        'images/IMPL_REC.jpg',
    ];
@endphp
<div class="resource-grid" id="casos">
    @foreach($resources as $resource)
        @php
            $typeName     = $resource->resourceType     ? $resource->resourceType->name     : 'Desconocido';
            $specialtyName = $resource->resourceSpecialty ? $resource->resourceSpecialty->name : '';
            $tags         = [];
            $fallbackImage = $fallbackImages[$loop->index % 3];
            $resourceImage = $resource->image_url ?? asset($fallbackImage);
        @endphp
        @include('web.components.resource-card', [
            'resourceType'        => $typeName,
            'resourcePlay'        => $iconMap[$resource->type] ?? '→',
            'resourceTags'        => $specialtyName ? [$specialtyName] : [],
            'resourceTitle'       => $resource->title,
            'resourceDescription' => $resource->description,
            'resourceFormat'      => $formatMap[$resource->format] ?? '▣ Artículo',
            'resourceLink'        => 'Ver detalle',
            'resourceUrl'         => route('caso-clinico', ['slug' => $resource->slug]),
            'resourceImage'       => $resourceImage,
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
