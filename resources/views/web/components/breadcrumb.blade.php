<!-- Breadcrumb -->
<nav class="breadcrumb {{ $attributes ?? '' }}">
    @foreach($items ?? [] as $index => $item)
        @if($item['url'] ?? false)
            <a href="{{ $item['url'] }}" class="breadcrumb-link" {{ $item['linkAttributes'] ?? '' }}>{{ $item['label'] }}</a>
        @else
            <span class="breadcrumb-active" {{ $item['spanAttributes'] ?? '' }}>{{ $item['label'] }}</span>
        @endif

        @if($index < count($items) - 1)
            <span class="breadcrumb-separator" {{ $separatorAttributes ?? '' }}><i class="fa fa-angle-right"></i></span>
        @endif
    @endforeach
</nav>
