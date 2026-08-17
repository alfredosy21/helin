{{--
@props([
    'label' => null,
    'name',
    'value' => '',
    'placeholder' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 4,
    'resize' => 'vertical',
    'maxlength' => null,
    'showCount' => false
])
--}}
@php
    // Asegurar que todas las variables estén disponibles
    $label = $label ?? null;
    $name = $name ?? '';
    $value = $value ?? '';
    $placeholder = $placeholder ?? null;
    $error = $error ?? null;
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $readonly = $readonly ?? false;
    $rows = $rows ?? 4;
    $resize = $resize ?? 'vertical';
    $maxlength = $maxlength ?? null;
    $showCount = $showCount ?? false;
@endphp
<div class="space-y-1">
    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 {{ $required ? 'text-red-500' : '' }}">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    {{-- Textarea Container --}}
    <div class="relative">
        {{-- Textarea --}}
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $maxlength ? "maxlength=\"{$maxlength}\"" : '' }}
            @php
            $textareaClasses = 'block w-full rounded-2xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-colors duration-200 placeholder:text-gray-400';
            if ($error) {
                $textareaClasses .= ' border-red-500 focus:border-red-500 focus:ring-red-500/20';
            }
            if ($disabled) {
                $textareaClasses .= ' bg-gray-100 cursor-not-allowed';
            }
            if ($readonly) {
                $textareaClasses .= ' bg-gray-50 cursor-not-allowed';
            }

            // Handle resize class
            $resizeClass = '';
            if ($resize === 'none') {
                $resizeClass = 'resize-none';
            } elseif ($resize === 'both') {
                $resizeClass = 'resize';
            } else {
                $resizeClass = 'resize-' . $resize;
            }
            $textareaClasses .= ' ' . $resizeClass;
            @endphp
            {{ $attributes->merge([
                'class' => trim($textareaClasses)
            ])}}
        >{{ $value }}</textarea>

        {{-- Character Count --}}
        @if($showCount && $maxlength)
            <div class="absolute bottom-2 right-2 text-xs text-gray-500 bg-white px-1 rounded">
                <span id="{{ $name }}-count">{{ strlen($value) }}</span>/{{ $maxlength }}
            </div>
        @endif

        {{-- Error Message --}}
        @if($error)
            <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
        @endif
    </div>

    {{-- Character Count Script --}}
    @if($showCount && $maxlength)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('{{ $name }}');
                const counter = document.getElementById('{{ $name }}-count');

                if (textarea && counter) {
                    function updateCount() {
                        counter.textContent = textarea.value.length;

                        // Change color when approaching limit
                        if (textarea.value.length > {{ $maxlength * 0.9 }}) {
                            counter.classList.add('text-orange-500');
                            counter.classList.remove('text-gray-500');
                        } else {
                            counter.classList.remove('text-orange-500');
                            counter.classList.add('text-gray-500');
                        }

                        // Change color when at limit
                        if (textarea.value.length >= {{ $maxlength }}) {
                            counter.classList.add('text-red-500');
                            counter.classList.remove('text-orange-500', 'text-gray-500');
                        }
                    }

                    textarea.addEventListener('input', updateCount);
                    updateCount(); // Initial count
                }
            });
        </script>
    @endif
</div>
