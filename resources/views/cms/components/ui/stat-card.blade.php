{{--
@props([
    'icon' => 'info',
    'value' => 0,
    'label' => '',
    'trend' => null,      // e.g. '12%'
    'trendUp' => true,
])
--}}
<div {{ $attributes->merge(['class' => 'rounded-xl bg-gradient-to-br from-primary-500/5 to-primary-500/10 border border-line p-3 sm:p-5']) }}>
    <div class="flex items-center justify-between">
        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-primary-500/10 flex items-center justify-center">
            <x-ui-icon :name="$icon" class="w-4 h-4 sm:w-5 sm:h-5 text-primary-600" />
        </div>
        @if($trend)
            <span class="inline-flex items-center gap-1 text-[10px] sm:text-xs font-semibold {{ $trendUp ? 'text-green-600' : 'text-red-500' }}">
                <x-ui-icon :name="$trendUp ? 'trending-up' : 'trending-down'" class="w-3 h-3 sm:w-3.5 sm:h-3.5" />
                {{ $trend }}
            </span>
        @endif
    </div>
    <p class="mt-3 sm:mt-4 text-xl sm:text-2xl font-bold text-heading">{{ $value }}</p>
    <p class="mt-1 text-[11px] sm:text-sm text-body">{{ $label }}</p>
</div>
