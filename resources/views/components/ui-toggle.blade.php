@props([
    'label' => null,
    'disabled' => false,
])

<label class="inline-flex items-center gap-2 {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
    <span class="relative inline-flex items-center flex-shrink-0">
        <input type="checkbox"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'sr-only peer']) }}
        >
        <span class="w-9 h-5 bg-slate-200 peer-checked:bg-primary-500 rounded-full transition-colors duration-200"></span>
        <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform duration-200 peer-checked:translate-x-4"></span>
    </span>
    @if($label)
        <span class="text-[13px] text-body">{{ $label }}</span>
    @endif
</label>
