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
<div class="space-y-1">
    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-[11px] font-semibold text-body uppercase tracking-wider {{ $required ? 'text-red-500' : '' }}">
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
            {{ $attributes->merge([
                'class' => trim("
                block w-full rounded-lg border border-line bg-white px-2.5 py-1.5 text-[13px] text-body
                focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20
                shadow-none
                {$error ? 'border-red-500' : ''}
                {$disabled ? 'bg-gray-100 cursor-not-allowed' : ''}
                {$readonly ? 'bg-gray-50 cursor-not-allowed' : ''}
                transition-colors duration-200
                placeholder:text-body
            "])}}
        >

        {{-- Suffix --}}
        @if($suffix)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-body">
                {{ $suffix }}
            </div>
        @endif

        {{-- Error Message --}}
        @if($error)
            <p class="mt-1 text-xs text-red-600">{{ $error }}</p>
        @endif
    </div>
</div>
