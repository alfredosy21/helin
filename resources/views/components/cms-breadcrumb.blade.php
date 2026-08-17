@props([
'moduleId' => null,
'submoduleId' => null,
'section' => null,    // Plain text for final actions like "Create", "Edit", etc.
'sectionIcon' => null // Optional icon name for the final section (kept for compatibility, not rendered)
])

@php
// Fetch the module from the database if the ID is provided
$module = $moduleId ? \App\Models\Module::find($moduleId) : null;

// Fetch the submodule from the database if the ID is provided
$submodule = $submoduleId ? \App\Models\Submodule::find($submoduleId) : null;

$isModuleActive = $module && !$submodule && !$section;
$isSubmoduleActive = $submodule && !$section;
@endphp

<nav class="flex items-center gap-1.5 text-xs text-body mb-2">
    {{-- Home / Dashboard Link --}}
    <a href="{{ route('dashboard') }}" class="hover:text-primary-600 transition-colors">{{ __('cms.general.dashboard') }}</a>

    {{-- Module Rendering --}}
    @if($module)
    <span class="text-slate-300">/</span>
    <span class="{{ $isModuleActive ? 'text-primary-600 font-medium' : 'text-body' }}">{{ __($module->name) }}</span>
    @endif

    {{-- Submodule Rendering --}}
    @if($submodule)
    <span class="text-slate-300">/</span>
    <span class="{{ $isSubmoduleActive ? 'text-primary-600 font-medium' : 'text-body' }}">{{ __($submodule->name) }}</span>
    @endif

    {{-- Optional Section Rendering --}}
    @if($section)
    <span class="text-slate-300">/</span>
    <span class="text-primary-600 font-medium">{{ __($section) }}</span>
    @endif
</nav>
