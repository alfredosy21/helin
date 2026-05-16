@php
    // Asegurar que todos los props estén disponibles con valores por defecto
    $variant = $variant ?? 'primary';
    $size = $size ?? 'md';
    $loading = $loading ?? false;
    $disabled = $disabled ?? false;
    $icon = $icon ?? null;
    $iconPosition = $iconPosition ?? 'left';
    $type = $type ?? 'button';

    // Definir arrays de clases
    $variantClasses = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'warning' => 'bg-amber-600 text-white hover:bg-amber-700 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:red-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed',
        'link' => 'text-primary-600 hover:text-primary-700 focus:ring-blue-500 p-0 disabled:opacity-50 disabled:cursor-not-allowed'
    ];

    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        'xl' => 'px-8 py-4 text-xl'
    ];

    // Construir clases finales
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-2xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    $variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $stateClass = $loading || $disabled ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-[0.98]';
    $finalClass = trim($baseClasses . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $stateClass);
@endphp
<button
    {{ $attributes->merge([
        'type' => $type,
        'disabled' => $disabled || $loading,
        'class' => $finalClass
    ])}}
>
    {{-- Loading Spinner --}}
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    {{-- Icon (Left) --}}
    @if($icon && $iconPosition === 'left')
        <x-ui-icon :name="$icon" class="mr-2 h-4 w-4" />
    @endif

    {{-- Button Content --}}
    {{ $slot }}

    {{-- Icon (Right) --}}
    @if($icon && $iconPosition === 'right')
        <x-ui-icon :name="$icon" class="ml-2 h-4 w-4" />
    @endif
</button>
