@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'action' => null,
])

<div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">
    @if($title || $description || $action)
    <div class="px-4 sm:px-5 py-3 border-b border-slate-50 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
            @if($icon)
            <div class="w-7 h-7 rounded-lg bg-primary-500/10 flex items-center justify-center flex-shrink-0">
                <x-ui-icon name="{{ $icon }}" class="w-3.5 h-3.5 text-primary-600" />
            </div>
            @endif
            <div class="min-w-0">
                @if($title)
                <h3 class="text-[13px] font-semibold text-heading truncate">{{ $title }}</h3>
                @endif
                @if($description)
                <p class="text-[11px] text-body truncate">{{ $description }}</p>
                @endif
            </div>
        </div>
        @if($action)
        <div class="flex-shrink-0">{{ $action }}</div>
        @endif
    </div>
    @endif
    <div class="p-4 sm:p-5">
        {{ $slot }}
    </div>
</div>
