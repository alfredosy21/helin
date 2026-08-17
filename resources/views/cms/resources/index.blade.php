{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-soft relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- SECCIÓN DE LA TABLA (Se muestra solo si showForm es falso) --}}

        {{-- Header Section & Breadcrumb --}}
        <x-ui-section-header :module-id="\App\Models\Module::CONTENT" :submodule-id="\App\Models\Submodule::CLINICAL_RESOURCES" :subtitle="__('cms.resources.breadcrumb')">
            @if(!$showForm)
            <x-slot:action>
                {{-- Botón Principal --}}
                <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-3 py-1.5 text-[13px] font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('cms.resources.new_button') }}
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
                    <input type="text" wire:model.live="search" placeholder="{{ __('cms.resources.search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-heading placeholder-body" />
                </div>
                <select wire:model.live="filterType" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="">{{ __('cms.resources.all_types') }}</option>
                    <option value="case_study">Caso clínico</option>
                    <option value="video">Video</option>
                    <option value="manual">Manual</option>
                    <option value="technical_sheet">Ficha técnica</option>
                    <option value="downloadable_guide">Guía descargable</option>
                    <option value="article">Artículo</option>
                </select>
                <select wire:model.live="filterSpecialty" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="">{{ __('cms.resources.all_specialties') }}</option>
                    <option value="Cirugía Bucal">Cirugía Bucal</option>
                    <option value="Maxilofacial">Maxilofacial</option>
                    <option value="Periodoncia">Periodoncia</option>
                    <option value="Ortodoncia">Ortodoncia</option>
                    <option value="Endodoncia">Endodoncia</option>
                    <option value="Implantología">Implantología</option>
                </select>
                <select wire:model.live="perPage" class="cms-perpage-select bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 pr-8 text-[13px] text-body focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Resources Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-[10px] font-semibold text-body uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2.5">{{ __('cms.resources.resource') }}</th>
                            <th class="px-4 py-2.5">{{ __('cms.resources.type') }}</th>
                            <th class="px-4 py-2.5">{{ __('cms.resources.specialty') }}</th>
                            <th class="px-4 py-2.5 text-center w-20">{{ __('cms.resources.format') }}</th>
                            <th class="px-4 py-2.5 text-center w-40">Actualizado</th>
                            <th class="px-4 py-2.5 text-center w-24">Estado</th>
                            <th class="px-4 py-2.5 text-right w-40">{{ __('cms.general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="resources-table-body" class="divide-y divide-slate-100 text-[13px]">
                        @forelse($resources as $resource)
                        <tr wire:key="resource-{{ $resource->id }}" data-id="{{ $resource->id }}" class="sortable-row hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-2.5">
                                <div class="flex items-start gap-3">
                                    <div class="drag-handle cursor-move text-body hover:text-body mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-body mb-1">{{ $resource->title }}</div>
                                        <div class="text-[13px] text-body line-clamp-2">{{ Str::limit($resource->description, 80) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-primary/10 text-primary">
                                    {{ $this->getTypeLabel($resource->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="text-[13px] text-body">{{ $resource->resourceSpecialty->name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-[13px] text-body">{{ $resource->format ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-[13px] text-body">
                                    {{ $resource->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <x-ui-badge-status :active="$resource->is_active" :active-label="__('cms.general.status_active')" :inactive-label="__('cms.general.status_inactive')" />
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="{{ __('cms.general.edit') }}">
                                        <button wire:click="edit({{ $resource->id }})" class="p-2 text-body hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="{{ __('cms.general.delete') }}">
                                        <button onclick="deleteResource({{ $resource->id }})" class="p-2 text-body hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="7" class="px-4 py-8 text-center"><x-ui-empty-state icon="folder" :title="__('cms.resources.no_resources')" />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            {{ $resources->links() }}
        </div>
        @else
        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-heading">
                    {{ $editingId ? __('cms.resources.edit_title') : __('cms.resources.new_title') }}
                </h2>
                <p class="text-[13px] text-body mt-1">{{ __('cms.resources.subtitle') }}</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    {{-- Toggle de estado y destacado --}}
                    <div class="flex items-center gap-6 bg-slate-50/50 border border-slate-100 p-4 rounded-lg">
                        <label for="is_active" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 text-sm font-medium text-body">{{ __('cms.general.status_active') }}</span>
                        </label>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="featured" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            <span class="ml-3 text-sm font-medium text-body">{{ __('cms.general.featured') }}</span>
                        </label>
                    </div>

                    {{-- Inputs principales --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.title_label') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" placeholder="{{ __('cms.resources.title_placeholder') }}"
                                   class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                            @error('title') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Slug (URL amigable) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="slug" placeholder="ejemplo: regeneracion-osea-guiada"
                                   class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                            @error('slug') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.type_label') }} <span class="text-red-500">*</span></label>
                            <select wire:model="type" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                <option value="">Seleccionar tipo</option>
                                <option value="case_study">Caso clínico</option>
                                <option value="video">Video</option>
                                <option value="manual">Manual</option>
                                <option value="technical_sheet">Ficha técnica</option>
                                <option value="downloadable_guide">Guía descargable</option>
                                <option value="article">Artículo</option>
                            </select>
                            @error('type') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.format_label') }}</label>
                            <select wire:model="format" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                <option value="">Seleccionar formato</option>
                                <option value="article">Artículo</option>
                                <option value="pdf">PDF</option>
                                <option value="video">Video</option>
                            </select>
                            @error('format') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.description_label') }} <span class="text-red-500">*</span></label>
                        <textarea wire:model="description" rows="4"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"
                                  placeholder="{{ __('cms.resources.description_placeholder') }}"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tipo de Recurso y Especialidad --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Tipo de Recurso <span class="text-red-500">*</span></label>
                            <select wire:model="resource_type_id" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                <option value="">Seleccionar tipo</option>
                                <option value="1">Caso Clínico</option>
                                <option value="2">Video</option>
                                <option value="3">Manual</option>
                                <option value="4">Ficha Técnica</option>
                                <option value="5">Guía Descargable</option>
                                <option value="6">Artículo</option>
                            </select>
                            @error('resource_type_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">Especialidad</label>
                            <select wire:model="resource_specialty_id" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                                <option value="">Seleccionar especialidad</option>
                                <option value="1">Cirugía Bucal</option>
                                <option value="2">Maxilofacial</option>
                                <option value="3">Periodoncia</option>
                                <option value="4">Ortodoncia</option>
                                <option value="5">Endodoncia</option>
                                <option value="6">Implantología</option>
                                <option value="7">Osteosíntesis</option>
                                <option value="8">Biomateriales</option>
                                <option value="9">Odontología General</option>
                            </select>
                            @error('resource_specialty_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Archivo/URL --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.file_label') }} (opcional)</label>
                            <input type="file" wire:model="file_path" class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                            @error('file_path') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.url_label') }} (opcional)</label>
                            <input type="url" wire:model="url" placeholder="{{ __('cms.resources.url_placeholder') }}"
                                   class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                            @error('url') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Imagen miniatura --}}
                    <x-ui-file-upload model="thumbnail" current-model="current_thumbnail" :preview="$thumbnail" :current-image="$current_thumbnail" :label="__('cms.resources.thumbnail_label')">
                        {{ __('cms.general.select_image') }}
                    </x-ui-file-upload>

                    {{-- Video URL --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.video_url_label') }}</label>
                        <input type="url" wire:model="video_url" placeholder="https://www.youtube.com/embed/..."
                               class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body" />
                        @error('video_url') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Contenido --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.content_label') }}</label>
                        <textarea wire:model="content" rows="6"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"
                                  placeholder="{{ __('cms.resources.content_placeholder') }}"></textarea>
                        @error('content') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.diagnosis_label') }}</label>
                        <textarea wire:model="diagnosis" rows="3"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"></textarea>
                        @error('diagnosis') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.materials_label') }}</label>
                        <textarea wire:model="materials" rows="3"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"></textarea>
                        @error('materials') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.results_label') }}</label>
                        <textarea wire:model="results" rows="3"
                                  class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors placeholder-body resize-none"></textarea>
                        @error('results') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Galería --}}
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-body uppercase tracking-wider">{{ __('cms.resources.gallery_label') }}</label>
                        <div class="relative">
                            @if($gallery)
                            <div class="mb-3 flex flex-wrap gap-3">
                                @foreach($gallery as $gIndex => $gImage)
                                <div class="relative">
                                    <img src="{{ $gImage->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border border-slate-100">
                                    <button type="button" wire:click="$set('gallery.{{ $gIndex }}', null)" class="absolute top-1 right-1 p-0.5 bg-white rounded shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @if($current_gallery)
                            <div class="mb-3 flex flex-wrap gap-3">
                                @foreach($current_gallery as $gIndex => $gImage)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $gImage) }}" class="w-24 h-24 object-cover rounded-lg border border-slate-100">
                                    <button type="button" wire:click="$set('current_gallery.{{ $gIndex }}', null)" class="absolute top-1 right-1 p-0.5 bg-white rounded shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <input type="file" wire:model="gallery" multiple class="w-full px-2.5 py-1.5 bg-white border border-line text-[13px] text-body rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors" />
                            @error('gallery.*') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>


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
                            {{ $editingId ? __('cms.general.save') : __('cms.resources.new_button') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? __('cms.general.save') : __('cms.resources.new_button') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
    <script>
        function deleteResource(resourceId) {
        window.confirmDelete({
        title: '{{ __('cms.resources.delete_title') }}',
                text: '{{ __('cms.general.delete_confirm_text_with_associated') }}',
                confirmButtonText: '{{ __('cms.general.delete') }}',
                cancelButtonText: '{{ __('cms.general.cancel') }}',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').confirmDelete(resourceId);
                }
        });
        }

        // Drag & Drop con SortableJS
        (function() {
        let sortableInstance = null;
        function initSortable() {
        const tbody = document.getElementById('resources-table-body');
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
