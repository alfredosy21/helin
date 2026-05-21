@props([
'moduleId' => null,
'submoduleId' => null,
'section' => null,    // Plain text for final actions like "Create", "Edit", etc.
'sectionIcon' => null // Optional icon name for the final section
])

@php
// Fetch the module from the database if the ID is provided
$module = $moduleId ? \App\Models\Module::find($moduleId) : null;

// Fetch the submodule from the database if the ID is provided
$submodule = $submoduleId ? \App\Models\Submodule::find($submoduleId) : null;

$isModuleActive = $module && !$submodule && !$section;
$isSubmoduleActive = $submodule && !$section;
@endphp

<div class="flex items-center gap-2 text-xs text-slate-400 mb-1 font-medium tracking-wide uppercase">
    {{-- Home / Dashboard Link --}}
    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-primary-600 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
        </svg>
        {{ __('cms.general.dashboard') }}
    </a>

    {{-- Module Rendering --}}
    @if($module)
    <span class="text-slate-300">/</span>
    <span class="flex items-center gap-1 {{ $isModuleActive ? 'text-primary-600 font-semibold' : 'text-slate-500' }}">
        @if($module->class)
        {{-- Fixed: Using your layout's custom icon component --}}
        <x-ui-icon name="{{ $module->class }}" class="w-3.5 h-3.5" />
        @endif
        {{ __($module->name) }}
    </span>
    @endif

    {{-- Submodule Rendering --}}
    @if($submodule)
    <span class="text-slate-300">/</span>
    <span class="flex items-center gap-1 {{ $isSubmoduleActive ? 'text-primary-600 font-semibold' : 'text-slate-500' }}">
        @if($submodule->icon)
        {{-- Fixed: Using your layout's custom icon component --}}
        <x-ui-icon name="{{ $submodule->icon }}" class="w-3.5 h-3.5" />
        @endif
        {{ __($submodule->name) }}
    </span>
    @endif

    {{-- Optional Section Rendering --}}
    @if($section)
    <span class="text-slate-300">/</span>
    <span class="flex items-center gap-1 text-primary-600 font-semibold">
        @if($sectionIcon)
        {{-- Fixed: Using your layout's custom icon component --}}
        <x-ui-icon name="{{ $sectionIcon }}" class="w-3.5 h-3.5" />
        @endif
        {{ __($section) }}
    </span>
    @endif
</div>