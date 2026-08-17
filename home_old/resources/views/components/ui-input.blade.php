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
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 {{ $required ? 'text-red-500' : '' }}">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    {{-- Input Container --}}
    <div class="relative">
        {{-- Prefix --}}
        @if($prefix)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
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
    $inputClasses = 'block w-full rounded-2xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-colors duration-200 placeholder:text-gray-400';
    if ($error) {
        $inputClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500/20';
    }
    if ($disabled) {
        $inputClasses .= ' bg-gray-100 cursor-not-allowed';
    }
    if ($readonly) {
        $inputClasses .= ' bg-gray-50 cursor-not-allowed';
    }
@endphp
        {{ $attributes->merge([
            'class' => trim($inputClasses)
        ])}}
        >

        {{-- Suffix --}}
        @if($suffix)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                {{ $suffix }}
            </div>
        @endif

        {{-- Error Message --}}
        @if($error)
            <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
        @endif
    </div>
</div>
