{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">


        {{-- SECCIÓN DE LA TABLA (Se muestra solo si showForm es falso) --}}

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::CONTENT" :submodule-id="\App\Models\Submodule::TESTIMONIALS" :subtitle="__('cms.testimonials.title')">
            @if(!$showForm)
            <x-slot:action>
                <button type="button" wire:click="create" class="rounded-lg bg-primary-500 hover:bg-primary-600 text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                    </svg>
                    {{ __('cms.testimonials.new_button') }}
                </button>
            </x-slot:action>
            @endif
        </x-ui-section-header>
        @if(!$showForm)
        {{-- Main Unified Card: Filtros y Tabla --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-3 sm:p-4 bg-white border-b border-line flex flex-col sm:flex-row gap-2 sm:gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.testimonials.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                    <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                    <option value="50">{{ __('cms.tables.per_page_50') }}</option>
                </select>
            </div>

            {{-- Testimonials Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5 w-2/5">{{ __('cms.testimonials.author') }}</th>
                            <th class="px-4 py-2.5">{{ __('cms.testimonials.testimony') }}</th>
                            <th class="px-4 py-2.5 text-center w-24">{{ __('cms.tables.updated_at') }}</th>
                            <th class="px-4 py-2.5 text-center w-20">{{ __('cms.tables.status') }}</th>
                            <th class="px-4 py-2.5 text-center w-24">{{ __('cms.testimonials.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="testimonials-table-body" class="divide-y divide-slate-100 text-[13px]">
                        @forelse($testimonials as $testimonial)
                        <tr wire:key="testimonial-{{ $testimonial->id }}" data-id="{{ $testimonial->id }}" class="sortable-row hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-2.5">
                                <div class="flex items-start gap-2">
                                    <div class="drag-handle cursor-move text-body hover:text-body mt-1 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    </div>
                                    <div class="flex flex-col min-w-0 flex-1">
                                        <span class="text-body truncate">{{ $testimonial->name }}</span>
                                        <span class="text-[13px] text-body truncate">{{ $testimonial->specialty }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5">
                                <p class="text-[13px] text-body line-clamp-2">{{ $testimonial->content }}</p>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-[13px] text-body whitespace-nowrap">
                                    {{ $testimonial->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <x-ui-badge-status :active="$testimonial->is_active" active-label="Activo" inactive-label="Inactivo" />
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex justify-center gap-1">
                                    <x-cms-tooltip text="{{ __('cms.testimonials.edit_tooltip') }}">
                                        <button type="button" wire:click="edit({{ $testimonial->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="{{ __('cms.testimonials.delete_tooltip') }}">
                                        <button onclick="deleteTestimonial({{ $testimonial->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="5" class="px-4 py-8 text-center"><x-ui-empty-state icon="inbox" :title="__('cms.general.no_records')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $testimonials->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Cabecera limpia sin botón X --}}
            <div class="p-4 sm:p-6 border-b border-line">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.testimonials.edit_title') : __('cms.testimonials.new_title') }}
                </h2>
                <p class="text-[13px] text-body mt-1">{{ __('cms.testimonials.subtitle') }}</p>
            </div>

            {{-- Cuerpo del Formulario --}}
            <form wire:submit.prevent="save" class="p-6 space-y-6">

                {{-- Toggle de estado activo/inactivo --}}
                <div class="flex items-center gap-6 bg-soft/50 border border-line p-4 rounded-lg">
                    <x-ui-toggle wire:model="is_active" :label="__('cms.general.status_active')" />
                </div>

                {{-- Inputs principales organizados en dos columnas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.testimonials.name_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                        @error('name') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.testimonials.charge_label') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="specialty" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                        @error('specialty') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Texto del testimonio --}}
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.testimonials.description_label') }} <span class="text-red-500">*</span></label>
                    <textarea wire:model="content" rows="4" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none"></textarea>
                    @error('content') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                </div>

                {{-- Imagen del testimonio --}}
                <x-ui-file-upload model="image" current-model="current_image" :preview="$image" :current-image="$current_image" label="Imagen del Autor" accept="image/*" />

            {{-- Acciones alineadas a la derecha --}}
            <div class="p-4 sm:p-6 border-t border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="button" wire:click="cancel" class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-body bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                    {{ __('cms.general.cancel') }}
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-4 py-1.5 rounded-lg text-[13px] font-medium bg-primary hover:bg-primary-600 text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="save">
                        {{ __('cms.general.save') }}
                    </span>
                    <span wire:loading wire:target="save">
                        {{ __('cms.general.save') }}
                    </span>
                </button>
            </div>
            </form>
            </div>
        </div>
        @endif

    </div>

    {{-- Scripts Javascript --}}
    <script>
        // Drag & Drop con SortableJS
        (function() {
        let sortableInstance = null;
        function initSortable() {
        const tbody = document.getElementById('testimonials-table-body');
        if (!tbody) return;
        if (typeof Sortable === 'undefined') return;
        if (sortableInstance) sortableInstance.destroy();
        sortableInstance = new Sortable(tbody, {
        handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-primary/5',
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

        if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSortable);
        } else {
        initSortable();
        }

        document.addEventListener('livewire:updated', initSortable);
        })();
        function deleteTestimonial(testimonialId) {
        window.confirmDelete({
        title: '{{ __('cms.testimonials.delete_title') }}',
                text: '{{ __('cms.general.delete_confirm_text') }}',
                confirmButtonText: '{{ __('cms.general.yes_delete') }}',
                cancelButtonText: '{{ __('cms.general.cancel') }}',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(testimonialId);
                }
        });
        }
    </script>
</div>
