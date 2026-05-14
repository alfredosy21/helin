<div class="min-h-screen pb-12 bg-gradient-to-br from-slate-50 via-white to-blue-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-blue-900/20">

    <!-- Header dinámico -->
    <div class="relative p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <nav class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                    <span class="flex items-center hover:text-blue-600 transition-colors cursor-default">
                        <x-ui-icon name="layout" class="w-4 h-4 mr-1" />
                        Gestión de Contenido
                    </span>
                    <x-ui-icon name="chevron-right" class="w-4 h-4 mx-2" />
                    <span class="text-slate-900 dark:text-white font-semibold">Secciones de la Plataforma</span>
                </nav>
                <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">
                    Bloques de <span class="text-blue-600">Contenido</span>
                </h1>
            </div>

            <!-- Buscador y Acciones -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="relative w-full sm:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                        <x-ui-icon name="search" class="w-5 h-5" />
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por título..."
                        class="block w-full pl-10 pr-4 py-2.5 bg-white/80 dark:bg-gray-800/80 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="px-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">

            <!-- Tabla de Secciones -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-gray-900/50 border-b border-slate-100 dark:border-gray-700">
                            <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-20">ID</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Sección / Título</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Estado Visibilidad</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Contenido</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700/50 text-sm">
                        @forelse($sections as $section)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                                <td class="px-6 py-4 text-center font-mono text-slate-400">#{{ $section->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @php $firstPhoto = $section->image ? explode(',', $section->image)[0] : null; @endphp
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-gray-700 flex-shrink-0 overflow-hidden border border-slate-200 dark:border-gray-600">
                                            @if($firstPhoto)
                                                <img src="{{ asset('storage/' . $firstPhoto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center opacity-30">
                                                    <x-ui-icon name="image" class="w-6 h-6" />
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white line-clamp-1 italic">{{ $section->title }}</p>
                                            <p class="text-xs text-slate-500 line-clamp-1">{{ Str::limit(strip_tags($section->content), 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $section->status ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $section->status ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                        {{ $section->status ? 'Público' : 'Borrador' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                        {{ $section->status_content ? 'Visible' : 'Oculto' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <x-ui-button wire:click="edit({{ $section->id }})" variant="secondary" size="sm" class="!rounded-xl shadow-sm">
                                            <x-ui-icon name="edit-3" class="w-4 h-4" />
                                        </x-ui-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-6 bg-slate-50 dark:bg-gray-800 rounded-full mb-4">
                                            <x-ui-icon name="folder-open" class="w-12 h-12 text-slate-300" />
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">No se encontraron secciones</h3>
                                        <p class="text-slate-500">Intenta con otros términos de búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($sections->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 dark:border-gray-700 bg-slate-50/30 dark:bg-gray-900/30">
                    {{ $sections->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Edición (Panel Lateral / Slide-over) -->
    @if($showEditForm)
    <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="cancelEdit"></div>

        <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-2xl transform transition-all duration-500 ease-in-out">
                <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-2xl border-l border-white/20">

                    <!-- Header Modal -->
                    <div class="p-8 border-b border-slate-100 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-white to-blue-50 dark:from-gray-800 dark:to-gray-900">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                                <x-ui-icon name="edit-2" class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 dark:text-white leading-tight">Configurar Sección</h2>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-widest">Editando Registro #{{ $editingId }}</p>
                            </div>
                        </div>
                        <button wire:click="cancelEdit" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                            <x-ui-icon name="x" class="w-8 h-8" />
                        </button>
                    </div>

                    <!-- Formulario con Scroll -->
                    <div class="flex-1 overflow-y-auto p-8 space-y-10 custom-scrollbar">

                        <!-- Bloque: Texto -->
                        <section class="space-y-6">
                            <h3 class="flex items-center text-sm font-black uppercase tracking-[0.2em] text-slate-400">
                                <span class="w-8 h-[2px] bg-blue-500 mr-3"></span>
                                Contenido Principal
                            </h3>
                            <x-ui-input label="Título de la Sección" wire:model="title" icon="type" />
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Cuerpo del contenido</label>
                                <textarea
                                    wire:model="content"
                                    rows="6"
                                    class="w-full p-4 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all resize-none"
                                ></textarea>
                                @error('content') <span class="text-xs text-red-500 italic font-medium ml-1">{{ $message }}</span> @enderror
                            </div>
                        </section>

                        <!-- Bloque: Botón de Acción -->
                        <section class="space-y-6">
                            <h3 class="flex items-center text-sm font-black uppercase tracking-[0.2em] text-slate-400">
                                <span class="w-8 h-[2px] bg-purple-500 mr-3"></span>
                                Call to Action (CTA)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui-input label="Texto del Botón" wire:model="name_button" placeholder="Ej: Saber más" />
                                <x-ui-input label="URL / Destino" wire:model="url_button" placeholder="https://..." />
                            </div>
                        </section>

                        <!-- Bloque: Media -->
                        <section class="space-y-6">
                            <h3 class="flex items-center text-sm font-black uppercase tracking-[0.2em] text-slate-400">
                                <span class="w-8 h-[2px] bg-emerald-500 mr-3"></span>
                                Activos Multimedia
                            </h3>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($photos as $photo)
                                    <div class="group/media relative aspect-square rounded-2xl overflow-hidden border-2 border-slate-100 dark:border-gray-700 shadow-md">
                                        <img src="{{ asset('storage/' . $photo['name']) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/media:scale-110">
                                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover/media:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                            <button
                                                wire:click="removePhoto('{{ $photo['name'] }}')"
                                                class="bg-white/20 hover:bg-red-500 text-white p-2 rounded-xl transition-all hover:rotate-12"
                                                title="Desvincular imagen"
                                            >
                                                <x-ui-icon name="trash-2" class="w-6 h-6" />
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <!-- Bloque: Visibilidad -->
                        <section class="space-y-6">
                            <h3 class="flex items-center text-sm font-black uppercase tracking-[0.2em] text-slate-400">
                                <span class="w-8 h-[2px] bg-orange-500 mr-3"></span>
                                Ajustes de Publicación
                            </h3>
                            <div class="flex flex-wrap gap-8 p-6 bg-slate-50 dark:bg-gray-900/30 rounded-3xl border border-slate-100 dark:border-gray-700">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" wire:model="status" class="hidden peer">
                                    <div class="w-12 h-6 bg-slate-300 peer-checked:bg-emerald-500 rounded-full relative transition-colors mr-3 shadow-inner">
                                        <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-blue-600 transition-colors">Visibilidad Pública</span>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" wire:model="status_content" class="hidden peer">
                                    <div class="w-12 h-6 bg-slate-300 peer-checked:bg-blue-600 rounded-full relative transition-colors mr-3 shadow-inner">
                                        <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-blue-600 transition-colors">Mostrar Contenido</span>
                                </label>
                            </div>
                        </section>
                    </div>

                    <!-- Footer Modal -->
                    <div class="p-8 border-t border-slate-100 dark:border-gray-700 flex gap-4">
                        <x-ui-button wire:click="cancelEdit" variant="secondary" class="flex-1 !py-4 font-black uppercase tracking-widest text-xs">
                            Descartar
                        </x-ui-button>
                        <x-ui-button wire:click="update" variant="primary" class="flex-[2] !py-4 font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-500/20">
                            <span wire:loading.remove wire:target="update">Sincronizar Sección</span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <x-ui-icon name="loader" class="w-4 h-4 mr-2 animate-spin" />
                                Procesando...
                            </span>
                        </x-ui-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
