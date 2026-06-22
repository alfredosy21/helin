<!-- Breadcrumb -->
<nav {{ $attributes ?? '' }}>
    @foreach($items ?? [] as $index => $item)
        @if($item['url'] ?? false)
            <a href="{{ $item['url'] }}" {{ $item['linkAttributes'] ?? '' }}>{{ $item['label'] }}</a>
        @else
            <span {{ $item['spanAttributes'] ?? '' }}>{{ $item['label'] }}</span>
        @endif

        @if($index < count($items) - 1)
            <span {{ $separatorAttributes ?? '' }}>{{ $separator ?? '>' }}</span>
        @endif
    @endforeach
</nav>
