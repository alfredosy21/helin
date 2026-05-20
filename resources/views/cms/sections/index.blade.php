<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

    {{-- Header Section & Breadcrumb Refinado --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
        <div>
            <x-cms-breadcrumb :module-id="2" :submodule-id="4" />
               <p class="text-sm text-slate-500 mt-2.5">
                {{ __('cms.sections.title') }}
</p>
        </div>
    </div>

    {{-- Main Unified Card: Filtros y Tabla --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

        {{-- Search & Filter Section --}}
        <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/>
                    </svg>
                </span>
                <input type="text" wire:model.live="search" placeholder="{{ __('cms.sections.search_placeholder') }}"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
            </div>
            <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                <option value="10">{{ __('cms.tables.per_page_10') }}</option>
                <option value="20">{{ __('cms.tables.per_page_20') }}</option>
                <option value="50">{{ __('cms.tables.per_page_50') }}</option>
            </select>
        </div>

        {{-- Sections Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                        <th class="px-6 py-3.5">{{ __('cms.tables.section_title') }}</th>
                        <th class="px-6 py-3.5">{{ __('cms.tables.visibility_status') }}</th>
                        <th class="px-6 py-3.5">{{ __('cms.tables.content') }}</th>
                        <th class="px-6 py-3.5 text-center w-40">{{ __('cms.tables.updated_at') }}</th>
                        <th class="px-6 py-3.5 text-right w-32">{{ __('cms.tables.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($sections as $section)
                        <tr wire:key="section-{{ $section->id }}" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-[#222]">{{ $section->title }}</span>
                                    <span class="text-xs text-[#c0c1c6] max-w-xl truncate">{{ $section->subtitle ?? __('cms.tables.no_subtitle') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $section->status ? 'bg-primary' : 'bg-slate-300' }}"></span>
                                    <span class="text-xs font-medium {{ $section->status ? 'text-slate-700' : 'text-slate-400' }}">
                                        {{ $section->status ? __('cms.general.status_active') : __('cms.general.status_inactive') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 bg-slate-50 border border-slate-100 rounded text-xs text-slate-600 font-medium">
                                    {{ $section->status_content ? __('cms.sections.visible') : __('cms.sections.hidden') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs text-slate-500">
                                    {{ $section->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button type="button" wire:click="edit({{ $section->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center text-[#c0c1c6]">
                                    <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/>
                                    </svg>
                                    <p class="text-xs font-medium">{{ __('cms.sections.no_sections') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($sections && method_exists($sections, 'hasPages') && $sections->hasPages())
            <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                {{ $sections->links() }}
            </div>
        @endif
    </div>

    {{-- Drawer lateral de edición --}}
    @if($showEditForm)
        <div class="fixed inset-0 z-[50] flex justify-end">
            {{-- Backdrop sutil --}}
            <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-xs transition-opacity" wire:click="cancelEdit"></div>

            {{-- Contenedor del Drawer --}}
            <div class="relative w-full max-w-lg h-full bg-white shadow-2xl flex flex-col border-l border-slate-100 z-10 animate-in slide-in-from-right duration-200">

                {{-- Header del Drawer --}}
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-white flex-shrink-0">
                    <div>
                        <h2 class="text-base font-bold text-[#222]">{{ __('cms.sections.edit_title') }}</h2>
                    </div>
                    <button type="button" wire:click="cancelEdit" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors border-none bg-transparent cursor-pointer flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Formulario --}}
                <form wire:submit.prevent="update" class="flex flex-col flex-1 overflow-hidden bg-white">
                    {{-- Cuerpo con Scroll --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-5">

                        {{-- Toggles de estado --}}
                        <div class="flex items-center gap-6 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <div class="relative">
                                    <input type="checkbox" wire:model="status" class="sr-only peer">
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-600">{{ __('cms.sections.active') }}</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <div class="relative">
                                    <input type="checkbox" wire:model="status_content" class="sr-only peer">
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </div>
                                <span class="text-xs font-medium text-slate-600">{{ __('cms.sections.visible') }}</span>
                            </label>
                        </div>

                        {{-- Título --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.title_label') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" placeholder="{{ __('cms.sections.title_placeholder') }}"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('title') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contenido con Quill Editor integrado correctamente --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.content_label') }} <span class="text-red-500">*</span></label>
                            <div x-data="{ quill: null }"
                                 x-init="
                                    quill = new Quill($refs.editor, {
                                        theme: 'snow',
                                        placeholder: '{{ __('cms.sections.content_placeholder') }}'
                                    });
                                    quill.root.innerHTML = $wire.content || '';
                                    quill.on('text-change', () => { $wire.content = quill.root.innerHTML });
                                 "
                                 @open-edit-form.window="setTimeout(() => { if(quill) quill.root.innerHTML = $wire.content || ''; }, 100)"
                                 class="relative bg-white rounded-lg overflow-hidden border border-slate-200">
                                <div x-ref="editor" class="min-h-[220px] max-h-[300px] overflow-y-auto"></div>
                            </div>
                            @error('content') <span class="text-xs text-red-500 font-medium italic block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Botón CTA e URL en grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.button_label') }}</label>
                                <input type="text" wire:model="name_button" placeholder="{{ __('cms.sections.button_placeholder') }}"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.url_label') }}</label>
                                <input type="text" wire:model="url_button" placeholder="{{ __('cms.sections.url_placeholder') }}"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            </div>
                        </div>

                        {{-- Imagen principal --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.main_image_label') }}</label>
                            <input type="text" wire:model="image" placeholder="{{ __('cms.sections.main_image_placeholder') }}"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                        </div>

                        {{-- Fotos vinculadas --}}
                        @if(count($photos) > 0)
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider block">{{ __('cms.sections.linked_photos') }}</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($photos as $photo)
                                        <div class="relative group bg-slate-50 rounded-lg border border-slate-100 p-2 flex flex-col items-center">
                                            <img src="{{ asset('storage/' . $photo['name']) }}" class="w-full h-16 object-cover rounded mb-1" alt="" />
                                            <button type="button" wire:click="removePhoto('{{ $photo['name'] }}')" class="text-[10px] text-red-500 hover:text-red-700 font-medium border-none bg-transparent cursor-pointer">
                                                {{ __('cms.sections.delete_photo') }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer fijo abajo del Drawer --}}
                    <div class="p-6 border-t border-slate-50 bg-slate-50/50 flex gap-3 flex-shrink-0">
                        <button type="button" wire:click="cancelEdit" class="flex-1 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors py-2 cursor-pointer">
                            {{ __('cms.general.cancel') }}
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-1 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors py-2 border-none cursor-pointer flex items-center justify-center">
                            <span wire:loading.remove wire:target="update">{{ __('cms.general.save') }}</span>
                            <span wire:loading wire:target="update" class="flex items-center justify-center">
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

</div>
</div>
