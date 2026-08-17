<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::SETTINGS" :submodule-id="\App\Models\Submodule::PAGE_SEO" :subtitle="__('cms.page_seo.breadcrumb')">
            @if(!$showForm)
            <x-slot:action>
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('cms.page_seo.new_button') }}
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
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-body">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.page_seo.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Page SEO Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5">{{ __('cms.page_seo.page_slug') }}</th>
                            <th class="px-4 py-2.5">{{ __('cms.page_seo.seo_title') }}</th>
                            <th class="px-4 py-2.5 text-center w-40">{{ __('cms.tables.updated_at') }}</th>
                            <th class="px-4 py-2.5 text-right w-40">{{ __('cms.general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13px]">
                        @forelse($pageSeos as $pageSeo)
                        <tr wire:key="page-seo-{{ $pageSeo->id }}" class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-2.5">
                                <span class="font-semibold text-heading">{{ $pageSeo->page_slug }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="text-[13px] text-body">{{ $pageSeo->seo_title ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-[13px] text-body">
                                    {{ $pageSeo->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button wire:click="edit({{ $pageSeo->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                        <button onclick="deletePageSeo({{ $pageSeo->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="4" class="px-4 py-8 text-center">
                                <x-ui-empty-state icon="folder" :title="__('cms.page_seo.no_records')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $pageSeos->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.page_seo.edit_title') : __('cms.page_seo.new_title') }}
                </h2>
                <p class="text-[13px] text-body mt-1">{{ __('cms.page_seo.subtitle') }}</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.page_seo.page_slug') }} <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="page_slug" placeholder="{{ __('cms.page_seo.page_slug_placeholder') }}"
                               class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                        @error('page_slug') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.page_seo.seo_title') }}</label>
                        <input type="text" wire:model="seo_title" placeholder="{{ __('cms.page_seo.seo_title_placeholder') }}"
                               class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                        @error('seo_title') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.general.seo_description_label') }}</label>
                        <textarea wire:model="seo_description" rows="3"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"></textarea>
                        @error('seo_description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.page_seo.seo_keywords') }}</label>
                        <textarea wire:model="seo_keywords" rows="2"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"></textarea>
                        @error('seo_keywords') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Imagen OG --}}
                    <x-ui-file-upload model="og_image" current-model="current_og_image" :preview="$og_image" :current-image="$current_og_image" :label="__('cms.page_seo.og_image')" hint="JPG, PNG (Máx. 2MB)" height="h-24">
                        {{ __('cms.page_seo.select_image') }}
                    </x-ui-file-upload>

                </div>

                {{-- Acciones alineadas a la derecha --}}
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
                            {{ $editingId ? __('cms.general.save') : __('cms.page_seo.new_button') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.page_seo.new_button') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
    <script>
        function deletePageSeo(pageSeoId) {
        window.confirmDelete({
        title: '{{ __('cms.page_seo.delete_title') }}',
                text: '{{ __('cms.general.delete_confirm_text_with_associated') }}',
                confirmButtonText: '{{ __('cms.page_seo.delete_button') }}',
                cancelButtonText: '{{ __('cms.general.cancel') }}',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(pageSeoId);
                }
        });
        }
    </script>
</div>
