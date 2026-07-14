<article class="resource-card">
    <div class="resource-thumb">
        @if(!empty($resourceImage))
            <img data-src="{{ $resourceImage }}"
                 data-fallback="{{ asset('images/placeholder-resource.webp') }}"
                 alt="{{ $resourceTitle ?? '' }}"
                 class="resource-thumb-img lazy-image">
        @endif
        <span class="resource-type">{{ $resourceType ?? '' }}</span>
        <span class="resource-play">{{ $resourcePlay ?? '→' }}</span>
    </div>
    <div class="resource-body">
        <div class="resource-tags">
            @foreach($resourceTags ?? [] as $tag)
                <span class="tag">{{ $tag }}</span>
            @endforeach
        </div>
        <h3>{{ $resourceTitle ?? '' }}</h3>
        <p>{{ $resourceDescription ?? '' }}</p>
        <div class="resource-footer">
            <span class="resource-format">{{ $resourceFormat ?? '▣ Artículo' }}</span>
            <a href="{{ $resourceUrl ?? '#' }}" class="resource-link">{{ $resourceLink ?? 'Ver más' }}</a>
        </div>
    </div>
</article>
