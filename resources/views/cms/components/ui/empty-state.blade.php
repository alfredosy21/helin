{{--
@props([
    'icon' => 'info',
    'title' => 'Sin resultados',
    'description' => null,
])
--}}
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-16 px-6']) }}>
    <div class="w-16 h-16 rounded-full bg-primary-500/5 flex items-center justify-center mb-4">
        <x-ui-icon :name="$icon" class="w-7 h-7 text-primary-500" />
    </div>
    <h3 class="text-base font-semibold text-heading">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-body max-w-sm">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-4">
            {{ $action }}
        </div>
    @endisset
</div>
