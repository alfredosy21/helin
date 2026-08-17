{{-- 
@props([
    'variant' => 'default',
    'size' => 'md',
    'rounded' => 'full',
    'dot' => false
])
@php
    $variantClasses = [
        'default' => 'bg-gray-100 text-gray-800',
        'primary' => 'bg-primary-100 text-primary-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-cyan-100 text-cyan-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'outline' => 'border border-gray-300 text-gray-700 bg-transparent',
        'outline-primary' => 'border border-blue-300 text-primary-700 bg-transparent',
        'outline-success' => 'border border-green-300 text-green-700 bg-transparent',
        'outline-warning' => 'border border-amber-300 text-amber-700 bg-transparent',
        'outline-danger' => 'border border-red-300 text-red-700 bg-transparent'
    ];
    
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-0.5 text-sm',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
        'xl' => 'px-5 py-2 text-lg'
    ];
    
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        '3xl' => 'rounded-3xl',
        'full' => 'rounded-full'
    ];
    
    $currentVariant = $variantClasses[$variant] ?? $variantClasses['default'];
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    $currentRounded = $roundedClasses[$rounded] ?? $roundedClasses['full'];
@endphp
--}}
<span {{ $attributes->merge([
    'class' => trim("
    inline-flex items-center font-medium
    {$currentVariant}
    {$currentSize}
    {$currentRounded}
    {$dot ? 'pl-1.5' : ''}
    transition-colors duration-200
")])}}>
    {{-- Dot indicator --}}
    @if($dot)
        <span class="w-2 h-2 mr-2 rounded-full bg-current opacity-60"></span>
    @endif
    
    {{-- Badge content --}}
    {{ $slot }}
</span>
