<div class="min-h-screen pb-12 bg-[#f8fafc] relative">
    <div class="relative z-10 p-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>

                           <x-cms-breadcrumb :module-id="1" :submodule-id="2" section="cms.permissions.breadcrumb" >
                    <x-slot name="sectionIcon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </x-slot>
                </x-cms-breadcrumb>
           <p class="text-sm text-slate-500 mt-2.5">
                    {{ __('cms.permissions.role_access') }} <span class="font-semibold text-slate-700">{{ $roleName ?: 'Unknown Role' }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="toggleAllModules(1)"
                    class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('cms.permissions.activate_all') }}
                </button>
                <button wire:click="toggleAllModules(0)"
                    class="rounded-lg bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('cms.permissions.deactivate_all') }}
                </button>
            </div>
        </div>

        @if($isLoading)
            <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] p-12 text-center">
                <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-sm text-slate-500">{{ __('cms.permissions.loading_matrix') }}</p>
            </div>
        @elseif(count($permissions) > 0)
            <div class="space-y-6">
                @foreach($permissions as $module)
                    <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">
                        <div class="p-6 border-b border-slate-50 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-500 shadow-sm">
                                        <x-ui-icon name="{{ $module['class'] }}" class="w-5 h-5 stroke-[1.75]" />
                                    </div>

                                    <div>
                                        <h3 class="text-base font-bold text-slate-800">{{ $module['name'] }}</h3>
                                        <p class="text-xs text-slate-400">
                                            {{ count($module['submodules']) }} {{ __('cms.permissions.submodules') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <span class="text-xs font-semibold uppercase tracking-wider {{ $module['status'] == 1 ? 'text-primary' : 'text-slate-400' }}">
                                        {{ $module['status'] == 1 ? __('cms.general.status_active') : __('cms.general.status_inactive') }}
                                    </span>
                                    <button
                                        wire:click="toggleModulePermission({{ $module['module_id'] }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 {{ $module['status'] == 1 ? 'bg-primary' : 'bg-slate-200' }}"
                                        wire:loading.attr="disabled"
                                        wire:target="toggleModulePermission">
                                        <span class="sr-only">Toggle module permission</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $module['status'] == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if(count($module['submodules']) > 0)
                            <div class="p-6 bg-slate-50/50 border-t border-slate-50">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('cms.permissions.submodules_actions') }}</h4>
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleAllSubmodules(1, {{ $module['module_id'] }})"
                                            class="text-xs px-3 py-1 bg-green-50 text-green-700 border border-green-100 rounded-full hover:bg-green-100 transition-colors font-medium">
                                            {{ __('cms.permissions.activate_all_sub') }}
                                        </button>
                                        <button wire:click="toggleAllSubmodules(0, {{ $module['module_id'] }})"
                                            class="text-xs px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full hover:bg-red-100 transition-colors font-medium">
                                            {{ __('cms.permissions.deactivate_all_sub') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($module['submodules'] as $submodule)
                                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-6 h-6 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 border border-slate-100">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                </div>

                                                <span class="text-sm font-medium text-slate-700">{{ $submodule['name'] }}</span>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <span class="text-[11px] font-semibold uppercase tracking-wider {{ $submodule['status'] == 1 ? 'text-primary' : 'text-slate-400' }}">
                                                    {{ $submodule['status'] == 1 ? __('cms.general.status_active') : __('cms.general.status_inactive') }}
                                                </span>
                                                <button
                                                    wire:click="toggleSubmodulePermission({{ $submodule['id'] }}, {{ $module['status'] }})"
                                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 {{ $submodule['status'] == 1 ? 'bg-primary' : 'bg-slate-200' }} {{ $module['status'] != 1 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                    wire:loading.attr="disabled"
                                                    {{ $module['status'] != 1 ? 'disabled' : '' }}>
                                                    <span class="sr-only">Toggle submodule permission</span>
                                                    <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform {{ $submodule['status'] == 1 ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-6 text-center text-slate-400 bg-slate-50/50">
                                <p class="text-xs italic">{{ __('cms.permissions.no_subpermissions') }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">{{ __('cms.permissions.no_permissions_title') }}</h3>
                <p class="text-sm text-slate-500 mb-6">{{ __('cms.permissions.no_permissions_text') }}</p>
            </div>
        @endif

        <div class="relative z-10">
            <a href="{{ route('cms.roles') }}" wire:navigate class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-all duration-200 shadow-sm border border-slate-200/80">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('cms.permissions.back_to_roles') }}
            </a>
        </div>
    </div>
</div>

