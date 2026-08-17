<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">


        {{-- SECCIÓN DE LA TABLA (Se muestra solo si showForm es falso) --}}

        {{-- Header Section & Breadcrumb Refinado --}}
        <x-ui-section-header :module-id="\App\Models\Module::ADMINISTRATORS" :submodule-id="\App\Models\Submodule::ROLES" :subtitle="__('cms.roles.title')">
            @if(!$showForm)
            <x-slot:action>
                {{-- Botón Principal Nativo Corporativo --}}
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('cms.roles.new_button') }}
                </button>
            </x-slot:action>
            @endif
        </x-ui-section-header>
        @if(!$showForm)
        {{-- Main Unified Card: Filtros y Tabla en una sola estructura --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.roles.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                    <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                    <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                </select>
            </div>

            {{-- Roles Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5">{{ __('cms.tables.role_name') }}</th>
                            <th class="px-4 py-2.5 text-center w-32">{{ __('cms.tables.status') }}</th>
                            <th class="px-4 py-2.5 text-right w-40">{{ __('cms.tables.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13px]">
                        @forelse($roles as $role)
                        <tr wire:key="role-{{ $role->id }}" class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-2.5">
                                <span class="text-body">
                                    {{ $role->name }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                    <span class="text-xs font-medium text-body">{{ __('cms.general.status_active') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex justify-end gap-1">
                                    @if(Auth::user()->rol_id === \App\Models\Role::ADMINISTRATOR)
                                    {{-- Botón Permisos --}}
                                    <x-cms-tooltip text="{{ __('cms.general.permissions') }}">
                                        <a href="{{ route('cms.permissions.index', $role->id) }}" class="p-2 text-body hover:text-purple-600 hover:bg-slate-50 rounded-lg transition-colors inline-flex">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </a>
                                    </x-cms-tooltip>
                                    @endif
                                    {{-- Botón Editar --}}
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button wire:click="edit({{ $role->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    {{-- Botón Eliminar --}}
                                    <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                        <button onclick="deleteRole({{ $role->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center">
                                <x-ui-empty-state icon="folder" :title="__('cms.roles.no_roles')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $roles->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden animate-in fade-in duration-200">

            {{-- Cabecera limpia sin botón X --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.roles.form_title_edit') : __('cms.roles.form_title_new') }}
                </h2>
                <p class="text-[13px] text-body mt-1">{{ __('cms.roles.form_subtitle') }}</p>
            </div>

            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider block">{{ __('cms.roles.name_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="{{ __('cms.roles.name_placeholder') }}"
                               class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                        @error('name')
                        <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Acciones en la base del formulario --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-body bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ __('cms.general.cancel') }}
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-4 py-1.5 rounded-lg text-[13px] font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.roles.create_button') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.roles.create_button') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>

<script>
    function deleteRole(roleId) {
    window.confirmDelete({
    title: '{{ __('cms.roles.delete_title') }}',
            text: '{{ __('cms.roles.delete_warning') }}',
            confirmButtonText: '{{ __('cms.general.yes_delete') }}',
            cancelButtonText: '{{ __('cms.general.cancel') }}',
            onConfirm: function() {
            Livewire.find('{{ $this->getId() }}').confirmDelete(roleId);
            }
    });
    }
</script>
