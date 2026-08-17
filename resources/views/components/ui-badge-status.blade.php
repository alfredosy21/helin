@props([
    'active' => false,
    'activeLabel' => 'Activo',
    'inactiveLabel' => 'Inactivo',
])

<span {{ $attributes->merge([
    'class' => trim('inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ' . ($active ? 'bg-primary-500/10 text-primary-700' : 'bg-gray-100 text-body'))
]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-primary-500' : 'bg-gray-400' }}"></span>
    {{ $active ? $activeLabel : $inactiveLabel }}
</span>
