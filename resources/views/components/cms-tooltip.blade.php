@props([
    'text' => '',
    'position' => 'top',
])

@php
$positionClasses = match($position) {
    'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
    'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
    'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
    default => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
};

$arrowClasses = match($position) {
    'top' => 'top-full left-1/2 -translate-x-1/2 -mt-0.5',
    'bottom' => 'bottom-full left-1/2 -translate-x-1/2 -mb-0.5',
    'left' => 'left-full top-1/2 -translate-y-1/2 -ml-0.5',
    'right' => 'right-full top-1/2 -translate-y-1/2 -mr-0.5',
    default => 'top-full left-1/2 -translate-x-1/2 -mt-0.5',
};
@endphp

<div class="relative inline-flex group/tooltip">
    {{ $slot }}
    <div class="absolute {{ $positionClasses }} px-2.5 py-1.5 bg-slate-800 text-white text-[10px] font-semibold rounded-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none whitespace-nowrap shadow-lg z-50">
        {{ $text }}
        <div class="absolute {{ $arrowClasses }} w-1.5 h-1.5 bg-slate-800 rotate-45"></div>
    </div>
</div>
