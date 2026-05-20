<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb Refinado --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
           <x-cms-breadcrumb :module-id="1" :submodule-id="2" />
                     <p class="text-sm text-slate-500 mt-2.5">
                    {{ __('cms.roles.title') }}
</p>
            </div>

            {{-- Botón Principal Nativo Corporativo --}}
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('cms.roles.new_button') }}
            </button>
        </div>

        {{-- Main Unified Card: Filtros y Tabla en una sola estructura --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.roles.search_placeholder') }}"
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                    <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                    <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                </select>
            </div>

            {{-- Roles Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5">{{ __('cms.tables.role_name') }}</th>
                            <th class="px-6 py-3.5 text-center w-32">{{ __('cms.tables.status') }}</th>
                            <th class="px-6 py-3.5 text-right w-40">{{ __('cms.tables.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($roles as $role)
                            <tr wire:key="role-{{ $role->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#222] uppercase tracking-wide text-xs bg-slate-50 border border-slate-100 px-2.5 py-1 rounded">
                                        {{ $role->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                                        <span class="text-xs font-medium text-slate-700">{{ __('cms.general.status_active') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        @if(Auth::user()->rol_id === \App\Models\Role::ADMINISTRATOR)
                                            {{-- Botón Permisos --}}
                                            <x-cms-tooltip text="{{ __('cms.general.permissions') }}">
                                                <a href="{{ route('cms.permissions.index', $role->id) }}" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-slate-50 rounded-lg transition-colors inline-flex">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                </a>
                                            </x-cms-tooltip>
                                        @endif
                                        {{-- Botón Editar --}}
                                        <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                            <button wire:click="edit({{ $role->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                                </svg>
                                            </button>
                                        </x-cms-tooltip>
                                        {{-- Botón Eliminar --}}
                                        <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                            <button onclick="deleteRole({{ $role->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                                <td colspan="3" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-[#c0c1c6]">
                                        <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/></svg>
                                        <p class="text-xs font-medium">{{ __('cms.roles.no_roles') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($roles->hasPages())
                <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Formulario Lateral / Drawer Refinado --}}
    @if($showForm)
    <div class="fixed inset-0 z-[50] flex items-center justify-end">
        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs" wire:click="cancel"></div>

        <div class="relative w-full max-w-md h-full bg-white shadow-xl flex flex-col border-l border-slate-100">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold text-[#222]">
                        {{ $editingId ? __('cms.roles.form_title_edit') : __('cms.roles.form_title_new') }}
                    </h2>
                    <p class="text-xs text-[#c0c1c6]">{{ __('cms.roles.form_subtitle') }}</p>
                </div>
                <button wire:click="cancel" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors border-none bg-transparent cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="flex flex-col flex-1 h-full">
                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">{{ __('cms.roles.name_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="{{ __('cms.roles.name_placeholder') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        @error('name')
                            <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="p-6 border-t border-slate-50 bg-slate-50/50 flex gap-3">
                    <button type="button" wire:click="cancel" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2 cursor-pointer">
                     {{ __('cms.general.cancel') }}
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors py-2 border-none cursor-pointer flex items-center justify-center">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.roles.create_button') }}
                        </span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

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
</div>
