{{--
@props([
    'type' => 'text',
    'label' => null,
    'name',
    'value' => '',
    'placeholder' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'prefix' => null,
    'suffix' => null
])
--}}
@php
    // Asegurar que todas las variables estén disponibles
    $type = $type ?? 'text';
    $label = $label ?? null;
    $name = $name ?? '';
    $value = $value ?? '';
    $placeholder = $placeholder ?? null;
    $error = $error ?? null;
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $readonly = $readonly ?? false;
    $prefix = $prefix ?? null;
    $suffix = $suffix ?? null;
@endphp
<div class="space-y-1">
    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-[11px] font-medium text-body">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    {{-- Input Container --}}
    <div class="relative">
        {{-- Prefix --}}
        @if($prefix)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-body">
                {{ $prefix }}
            </div>
        @endif

        {{-- Input --}}
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            @php
    $inputClasses = 'block w-full rounded-lg border border-line bg-white text-[13px] text-body px-3 py-1.5 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors duration-200 placeholder:text-body placeholder:text-[11px]';
    if ($error) {
        $inputClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500/20';
    }
    if ($disabled) {
        $inputClasses .= ' bg-soft/50 cursor-not-allowed';
    }
    if ($readonly) {
        $inputClasses .= ' bg-soft/50 cursor-not-allowed';
    }
@endphp
        {{ $attributes->merge([
            'class' => trim($inputClasses)
        ])}}
        >

        {{-- Suffix --}}
        @if($suffix)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-body">
                {{ $suffix }}
            </div>
        @endif

        {{-- Error Message --}}
        @if($error)
            <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $error }}</p>
        @endif
    </div>
</div>
