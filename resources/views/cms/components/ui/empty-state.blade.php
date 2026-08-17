{{--
@props([
    'icon' => 'info',
    'title' => 'Sin resultados',
    'description' => null,
])
--}}
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-8 px-6']) }}>
    <div class="w-10 h-10 rounded-full bg-primary-500/5 flex items-center justify-center mb-2">
        <x-ui-icon :name="$icon" class="w-4 h-4 text-primary-500" />
    </div>
    <p class="text-[13px] text-body">{{ $title }}</p>
    @if($description)
        <p class="mt-0.5 text-[11px] text-body/70 max-w-sm">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-3">
            {{ $action }}
        </div>
    @endisset
</div>
