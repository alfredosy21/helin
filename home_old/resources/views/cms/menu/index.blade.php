{{-- Root Container --}}
<div class="min-h-screen pb-12 bg-[#f8fafc] relative">

    {{-- Content Layout --}}
    <div class="relative z-10 p-6 space-y-6">

        {{-- Header Section & Breadcrumb --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <x-cms-breadcrumb :module-id="1" :submodule-id="1" />
                <p class="text-sm text-slate-500 mt-2.5">
                    Gestiona el menú de navegación del sitio web
                </p>
            </div>

            {{-- Botón Principal --}}
            @if(!$showForm)
            <button wire:click="create" class="rounded-lg bg-primary hover:bg-[#079d8b] text-white px-4 py-2.5 text-sm font-medium transition-colors inline-flex items-center shadow-none border-none cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Menú
            </button>
            @endif
        </div>

        @if(!$showForm)

        {{-- Main Unified Card --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-[0_1px_2px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Search & Filter Section --}}
            <div class="p-4 bg-white border-b border-slate-50 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z"/></svg>
                    </span>
                    <input type="text" wire:model.live="search" placeholder="Buscar menús..."
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg focus:outline-none focus:border-primary transition-colors text-sm text-[#222] placeholder-[#c0c1c6]" />
                </div>
                <select wire:model.live="filterType" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="0">Todos los tipos</option>
                    <option value="1">Header</option>
                    <option value="2">Footer</option>
                    <option value="3">Sidebar</option>
                </select>
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-2 text-sm text-slate-600 focus:outline-none focus:border-primary transition-colors">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            {{-- Menu Items Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[#c0c1c6] text-xs font-semibold">
                            <th class="px-6 py-3.5">Título</th>
                            <th class="px-6 py-3.5">URL</th>
                            <th class="px-6 py-3.5 text-center w-20">Tipo</th>
                            <th class="px-6 py-3.5 text-center w-24">Padre</th>
                            <th class="px-6 py-3.5 text-center w-24">Posición</th>
                            <th class="px-6 py-3.5 text-center w-24">Estado</th>
                            <th class="px-6 py-3.5 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="menus-table-body" class="divide-y divide-slate-50 text-sm">
                        @forelse($menus as $menu)
                        <tr wire:key="menu-{{ $menu->id }}" data-id="{{ $menu->id }}" class="sortable-row hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-2">
                                    <div class="drag-handle cursor-move text-slate-400 hover:text-slate-600 mt-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-[#222]">{{ $menu->title }}</span>
                                        @if($menu->icon)
                                        <div class="flex items-center gap-1 mt-1">
                                            <i class="{{ $menu->icon }} text-xs text-slate-500"></i>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    @if($menu->url)
                                    <a href="{{ $menu->url }}" target="{{ $menu->target_blank ? '_blank' : '_self' }}" class="text-xs text-slate-600 hover:text-primary truncate max-w-xs block">
                                        {{ $menu->url }}
                                        @if($menu->target_blank)
                                        <svg class="w-3 h-3 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        @endif
                                    </a>
                                    @else
                                    <span class="text-xs text-slate-400 italic">Sin URL</span>
                                    @endif
                                    @if($menu->description)
                                    <span class="text-xs text-slate-500 mt-1 block">{{ Str::limit($menu->description, 50) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($menu->type == 1) bg-blue-100 text-blue-800
                                    @elseif($menu->type == 2) bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $this->getTypeLabel($menu->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs text-slate-600">{{ $this->getParentLabel($menu->parent_id) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-medium text-slate-700">{{ $menu->position }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleStatus({{ $menu->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors cursor-pointer
                                    @if($menu->status) bg-primary
                                    @else bg-slate-200 @endif">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                        @if($menu->status) translate-x-6
                                        @else translate-x-1 @endif"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <x-cms-tooltip text="Editar">
                                        <button wire:click="edit({{ $menu->id }})" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                            </svg>
                                        </button>
                                    </x-cms-tooltip>
                                    <x-cms-tooltip text="Eliminar">
                                        <button onclick="deleteMenu({{ $menu->id }})" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
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
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center text-[#c0c1c6]">
                                    <svg class="w-10 h-10 mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.25a2.25 2.25 0 0 1-2.25 2.25H2.25A2.25 2.25 0 0 1 0 20.25v-4.25A2.25 2.25 0 0 1 2.25 13.5A2.25 2.25 0 0 0 2.25 11.25V7.104a2.25 2.25 0 0 1 .515-1.425l3.525-4.406A2.25 2.25 0 0 1 8.012 1.5h7.976a2.25 2.25 0 0 1 1.722.813l3.525 4.406a2.25 2.25 0 0 1 .515 1.425v4.146ZM12 3v3.75m0-3.75a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Z"/></svg>
                                    <p class="text-xs font-medium">No se encontraron menús</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($menus->hasPages())
            <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500">
                {{ $menus->links() }}
            </div>
            @endif
        </div>

        @else

        {{-- SECCIÓN DEL FORMULARIO A PANTALLA COMPLETA --}}
        <div class="max-w-4xl mx-auto bg-white rounded-xl border border-slate-100 shadow-[0_1px_3px_0_rgba(0,0,0,0.02)] overflow-hidden">

            {{-- Cabecera limpia --}}
            <div class="p-6 border-b border-slate-50">
                <h2 class="text-lg font-bold text-[#222]">
                    {{ $editingId ? 'Editar Menú' : 'Nuevo Menú' }}
                </h2>
                <p class="text-xs text-[#c0c1c6] mt-1">Configura los elementos del menú de navegación</p>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="w-full">
                <div class="p-6 space-y-6">

                    {{-- Toggle de estado --}}
                    <div class="flex items-center gap-3 bg-slate-50/50 border border-slate-100 p-4 rounded-lg">
                        <label for="status" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="status" wire:model="status" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 text-sm font-medium text-slate-700">Activo</span>
                        </label>
                    </div>

                    {{-- Inputs principales --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Título <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" placeholder="Ej: Inicio"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('title') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Tipo <span class="text-red-500">*</span></label>
                            <select wire:model="type" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                                <option value="">Seleccionar tipo</option>
                                @foreach($menuTypes as $typeValue => $typeName)
                                <option value="{{ $typeValue }}">{{ $typeName }}</option>
                                @endforeach
                            </select>
                            @error('type') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">URL</label>
                            <input type="text" wire:model="url" placeholder="Ej: /inicio o https://ejemplo.com"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('url') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Posición</label>
                            <input type="number" wire:model="position" min="0" placeholder="0"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('position') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Menú Padre</label>
                            <select wire:model="parent_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors">
                                <option value="">Ninguno (menú principal)</option>
                                @foreach($availableParents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->title }} ({{ $this->getTypeLabel($parent->type) }})</option>
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Icono</label>
                            <input type="text" wire:model="icon" placeholder="Ej: fas fa-home"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300" />
                            @error('icon') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Descripción</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-100 text-sm text-slate-700 rounded-lg focus:outline-none focus:border-primary transition-colors placeholder-slate-300 resize-none"
                                  placeholder="Descripción opcional del menú"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium italic">{{ $message }}</span> @enderror
                    </div>

                    {{-- Opciones adicionales --}}
                    <div class="space-y-1.5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="target_blank" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 text-sm font-medium text-slate-700">Abrir en nueva pestaña</span>
                        </label>
                    </div>

                    {{-- Imagen --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#c0c1c6] uppercase tracking-wider">Imagen</label>
                        <div class="relative">
                            @if($image)
                            <div class="mb-3 relative group max-w-xs">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @elseif($current_image)
                            <div class="mb-3 relative group max-w-xs">
                                <img src="{{ asset('storage/' . $current_image) }}" class="w-full h-32 object-cover rounded-lg border border-slate-100">
                                <button type="button" wire:click="$set('current_image', null)" class="absolute top-2 right-2 p-1 bg-white rounded-lg shadow-sm text-red-500 hover:text-red-700 border-none cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @endif

                            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary hover:bg-slate-50/50 transition-colors bg-slate-50/30">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                    <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.25 5.25 5.25 0 0110.32-2.17 4.5 4.5 0 0110.34 2.17 4.5 4.5 0 01-1.41 8.25H6.75z"/>
                                    </svg>
                                    <p class="text-xs text-slate-500 font-medium">Subir imagen</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG (Máx. 2MB)</p>
                                </div>
                                <input type="file" wire:model="image" class="hidden" accept="image/*" />
                            </label>
                            @error('image') <span class="text-xs text-red-500 font-medium italic mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

                {{-- Acciones alineadas a la derecha --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="px-6 py-2.5 rounded-lg text-sm font-medium bg-primary hover:bg-[#079d8b] text-white transition-colors border-none cursor-pointer flex items-center justify-center gap-2">
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar' : 'Crear' }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $editingId ? 'Guardar' : 'Crear' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>

    {{-- Scripts Javascript --}}
    <script>
        // Drag & Drop con SortableJS
        (function() {
        let sortableInstance = null;
        function initSortable() {
        const tbody = document.getElementById('menus-table-body');
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

        if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSortable);
        } else {
        initSortable();
        }

        document.addEventListener('livewire:updated', initSortable);
        })();

        function deleteMenu(menuId) {
        window.confirmDelete({
        title: 'Eliminar Menú',
                text: '¿Estás seguro de eliminar este menú? Esta acción no se puede deshacer.',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                onConfirm: function() {
                Livewire.find('{{ $this->getId() }}').delete(menuId);
                }
        });
        }
    </script>
</div>
