@props([
    'moduleId' => null,
    'submoduleId' => null,
    'section' => null,
    'sectionIcon' => null,
    'title' => null,
    'subtitle' => null,
])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
    <div>
        <x-cms-breadcrumb :module-id="$moduleId" :submodule-id="$submoduleId" :section="$section" :section-icon="$sectionIcon" />
        @if($title)
            <h1 class="text-xl font-bold text-heading mt-1">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="text-sm text-body mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($action)
        <div class="flex items-center gap-3">
            {{ $action }}
        </div>
    @endisset
</div>
