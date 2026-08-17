<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- SECCIÓN DE LA TABLA --}}

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::CONTENT" :submodule-id="\App\Models\Submodule::RESOURCE_TYPES" :subtitle="__('cms.resource_types.breadcrumb')">
            @if(!$showForm)
            <x-slot:action>
                {{-- Botón Principal --}}
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('cms.resource_types.new_button') }}
                </button>
            </x-slot:action>
            @endif
        </x-ui-section-header>

        @if(!$showForm)

        {{-- Main Unified Card --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.resource_types.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-slate-300" />
                </div>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Resource Types Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-xs font-semibold">
                            <th class="px-6 py-3.5">{{ __('cms.resource_types.resource_type') }}</th>
                            <th class="px-6 py-3.5 text-center w-40">Actualizado</th>
                            <th class="px-6 py-3.5 text-center w-24">Estado</th>
                            <th class="px-6 py-3.5 text-right w-40">{{ __('cms.general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="resource-types-table-body" class="divide-y divide-slate-50 text-sm">
                        @forelse($resourceTypes as $resourceType)
                        <tr wire:key="resource-type-{{ $resourceType->id }}" data-id="{{ $resourceType->id }}" class="sortable-row hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="drag-handle cursor-move text-slate-400 hover:text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <div class="font-medium text-heading">{{ $resourceType->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs text-slate-500">
                                    {{ $resourceType->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-ui-badge-status :active="$resourceType->is_active" :active-label="__('cms.general.status_active')" :inactive-label="__('cms.general.status_inactive')" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button wire:click="edit({{ $resourceType->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                        <button onclick="deleteResourceType({{ $resourceType->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="4">
                                <x-ui-empty-state icon="folder" :title="__('cms.resource_types.no_types')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $resourceTypes->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.resource_types.edit_title') : __('cms.resource_types.new_title') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">{{ __('cms.resource_types.subtitle') }}</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    {{-- Toggle de estado --}}
                    <div class="flex items-center gap-3 bg-slate-50/50 border border-slate-100 p-4 rounded-lg">
                        <x-ui-toggle wire:model="is_active" :label="__('cms.general.status_active')" />
                    </div>

                    {{-- Inputs principales --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.resource_types.name_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="{{ __('cms.resource_types.name_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.resource_types.description_label') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3 py-2 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300 resize-none"
                                  placeholder="{{ __('cms.resource_types.description_placeholder') }}"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Icono --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.resource_types.icon_label') }}</label>
                        <input type="text" wire:model="icon" placeholder="{{ __('cms.resource_types.icon_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        <p class="text-[10px] text-slate-400">{{ __('cms.resource_types.icon_hint') }}</p>
                        @error('icon') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Etiqueta de formato --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.resource_types.format_label_label') }}</label>
                        <input type="text" wire:model="format_label" placeholder="{{ __('cms.resource_types.format_label_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        <p class="text-[10px] text-slate-400">{{ __('cms.resource_types.format_label_hint') }}</p>
                        @error('format_label') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Imagen --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.general.image_label') }}</label>
                        <div class="relative">
                            @if($image)
                            <div class="mb-3 relative">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @elseif($current_image)
                            <div class="mb-3 relative">
                                <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('current_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @endif
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                    <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                    </svg>
                                    <p class="text-xs text-slate-500">{{ __('cms.general.select_image') }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB)</p>
                                </div>
                                <input type="file" wire:model="image" class="hidden" accept="image/*" />
                            </label>
                            @error('image') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Banner --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.general.banner_title_label') }}</label>
                        <input type="text" wire:model="banner_title"
                               class="w-full px-3 py-2.5 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300" />
                        @error('banner_title') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.general.banner_description_label') }}</label>
                        <textarea wire:model="banner_description" rows="3"
                                  class="w-full px-3 py-2 bg-white border border-line text-sm text-slate-700 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-slate-300 resize-none"></textarea>
                        @error('banner_description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('cms.general.banner_image_label') }}</label>
                        <div class="relative">
                            @if($banner_image)
                            <div class="mb-3 relative">
                                <img src="{{ $banner_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('banner_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @elseif($current_banner_image)
                            <div class="mb-3 relative">
                                <img src="{{ asset('storage/' . $current_banner_image) }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('current_banner_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @endif
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50 transition-colors bg-slate-50/50">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                    <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                    </svg>
                                    <p class="text-xs text-slate-500">{{ __('cms.general.select_banner_image') }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB)</p>
                                </div>
                                <input type="file" wire:model="banner_image" class="hidden" accept="image/*" />
                            </label>
                            @error('banner_image') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>


                </div>

                {{-- Acciones alineadas a la derecha --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ __('cms.general.cancel') }}
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-6 py-2.5 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.resource_types.new_button') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.resource_types.new_button') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
    <script>
        function deleteResourceType(resourceTypeId) {
        window.confirmDelete({
        title: '{{ __('cms.resource_types.delete_title') }}',
                text: '{{ __('cms.general.delete_confirm_text_with_associated') }}',
                confirmButtonText: '{{ __('cms.resource_types.delete_button') }}',
                cancelButtonText: '{{ __('cms.general.cancel') }}',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(resourceTypeId);
                }
        });
        }

        // Drag & Drop con SortableJS
        (function() {
        let sortableInstance = null;
        function initSortable() {
        const tbody = document.getElementById('resource-types-table-body');
        if (!tbody) return;
        if (typeof Sortable === 'undefined') return;
        if (sortableInstance) sortableInstance.destroy();
        sortableInstance = new Sortable(tbody, {
        handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-emerald-50',
                onEnd: function() {
                const rows = tbody.querySelectorAll('tr[data-id]');
                const orderedIds = Array.from(rows).map(row => parseInt(row.dataset.id));
                const component = window.Livewire ? Livewire.find('{{ $this->getId() }}') : null;
                if (component && orderedIds.length > 0) {
                component.updateOrder(orderedIds);
                }
                }
        });
        }

        // Initialize after DOM is ready
        if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSortable);
        } else {
        initSortable();
        }

        // Reinitialize after Livewire updates
        document.addEventListener('livewire:updated', initSortable);
        })();
    </script>
</div>
