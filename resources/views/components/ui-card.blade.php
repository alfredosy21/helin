@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'normal',
    'variant' => 'default',
    'hover' => false,
    'bordered' => false,
    'shadow' => true
])

@php
    $variantClasses = [
        'default' => 'bg-white',
        'elevated' => 'bg-white',
        'flat' => 'bg-gray-50',
        'bordered' => 'bg-white border border-gray-200',
        'gradient' => 'bg-gradient-to-br from-blue-50 to-indigo-50'
    ];

    $paddingClasses = [
        'none' => '',
        'tight' => 'p-4',
        'normal' => 'p-6',
        'loose' => 'p-8',
        'xl' => 'p-10'
    ];

    $shadowClasses = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow-md',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
        '2xl' => 'shadow-2xl'
    ];

    $variant = $attributes->get('variant', 'default');
    $padding = $attributes->get('padding', 'normal');
    $shadow = $attributes->get('shadow', true);
    $hover = $attributes->get('hover', false);
    $bordered = $attributes->get('bordered', false);

    $currentVariant = $variantClasses[$variant] ?? $variantClasses['default'];
    $currentPadding = $paddingClasses[$padding] ?? $paddingClasses['normal'];
    $currentShadow = $shadow ? ($shadowClasses[$shadow] ?? 'shadow-lg') : '';
    $hoverClass = $hover ? 'hover:shadow-xl hover:scale-[1.02] transition-all duration-300' : '';
@endphp
<div
    {{ $attributes->merge([
        'class' => trim('{$currentVariant} {$currentPadding} {$currentShadow} {$hoverClass} rounded-3xl ' . ($bordered ? 'border-gray-200 ' : '') . 'shadow-black/5')
])}}
>
    {{-- Header --}}
    @if($title || $subtitle)
        <div class="mb-4 {{ $padding === 'none' ? '' : 'space-y-2' }}">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900 {{ $subtitle ? 'text-base' : 'text-lg' }}">
                    {{ $title }}
                </h3>
            @endif

            @if($subtitle)
                <p class="text-sm text-gray-600 {{ $title ? '' : 'text-base font-medium' }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    @endif

    {{-- Body --}}
    <div {{ $title || $subtitle ? '' : 'w-full' }}>
        {{ $slot }}
    </div>
</div>
