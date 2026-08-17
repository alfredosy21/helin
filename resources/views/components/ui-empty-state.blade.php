@props([
    'icon' => 'info',
    'title' => 'Sin resultados',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-6 px-4']) }}>
    <p class="text-[13px] text-body italic">{{ $title }}</p>
    @if($description)
        <p class="mt-1 text-[11px] text-body italic">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-3">
            {{ $action }}
        </div>
    @endisset
</div>
