{{--
@props([
    'moduleId' => null,
    'submoduleId' => null,
    'section' => null,
    'sectionIcon' => null,
    'title' => null,
    'subtitle' => null,
])
--}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-2">
    <div>
        <x-cms-breadcrumb :module-id="$moduleId" :submodule-id="$submoduleId" :section="$section" :section-icon="$sectionIcon" />
        @if($title)
            <h1 class="text-base sm:text-lg font-semibold text-heading">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="text-[12px] sm:text-[13px] text-body mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($action)
        <div class="flex items-center gap-2 sm:gap-3">
            {{ $action }}
        </div>
    @endisset
</div>
