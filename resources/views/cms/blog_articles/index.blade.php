<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Artículos de Blog</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona el contenido del blog con SEO optimizado</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo artículo
            </button>
        </div>
    </div>

    <!-- Toolbar: Búsqueda y Paginación -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row md:items-center gap-4">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input wire:model.live="search" type="text"
                class="block w-full pl-10 pr-3 py-2 border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg text-sm"
                placeholder="Buscar por título, autor o contenido...">
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="perPage" class="border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg text-sm bg-white">
                <option value="10">10 por página</option>
                <option value="20">20 por página</option>
                <option value="50">50 por página</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Datos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Título</th>
                        <th class="px-6 py-4 font-semibold">Autor</th>
                        <th class="px-6 py-4 font-semibold">Categoría</th>
                        <th class="px-6 py-4 font-semibold text-center">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($articles as $article)
                        <tr wire:key="article-{{ $article->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <span class="text-gray-900 font-bold text-sm block">
                                        {{ $article->title }}
                                    </span>
                                    @if($article->is_featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                            Destacado
                                        </span>
                                    @endif
                                    @if($article->is_pinned)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800 mt-1 ml-1">
                                            Fijado
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-600 text-sm">
                                    {{ $article->author ?? 'Sin autor' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($article->blogCategory)
                                    <span class="text-indigo-600 font-medium text-sm">
                                        {{ $article->blogCategory->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm italic">
                                        Sin categoría
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleStatus({{ $article->id }})" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $article->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }} hover:opacity-80 transition-opacity">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $article->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $article->is_active ? 'Publicado' : 'Borrador' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="toggleFeatured({{ $article->id }})" 
                                        class="p-2 {{ $article->is_featured ? 'text-yellow-600 bg-yellow-50' : 'text-gray-400 hover:text-yellow-600 hover:bg-yellow-50' }} rounded-lg transition-colors" 
                                        title="{{ $article->is_featured ? 'Quitar destacado' : 'Marcar como destacado' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="edit({{ $article->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar Artículo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:confirm="¿Deseas eliminar este artículo? Esta acción no se puede deshacer." wire:click="confirmDelete({{ $article->id }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Artículo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No se encontraron artículos en el blog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-white border-t border-gray-100">
            {{ $articles->links() }}
        </div>
    </div>

    <!-- Modal de Gestión -->
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="cancel"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $editingId ? 'Actualizar Artículo' : 'Nuevo Artículo de Blog' }}
                </h3>
                <button wire:click="cancel" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    <!-- Title and Author -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Título del Artículo</label>
                            <input type="text" wire:model="title"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                                placeholder="Título del artículo" required>
                            @error('title') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Autor</label>
                            <input type="text" wire:model="author"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                                placeholder="Nombre del autor">
                            @error('author') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Slug and Category -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Slug (URL)</label>
                            <input type="text" wire:model="slug"
                                class="w-full border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                placeholder="url-del-articulo">
                            <p class="mt-1 text-[10px] text-gray-400 italic">Si se deja vacío, se generará automáticamente.</p>
                            @error('slug') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Categoría</label>
                            <select wire:model="blog_category_id"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm bg-white">
                                <option value="">Sin categoría</option>
                                @foreach(\App\Models\BlogCategory::orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('blog_category_id') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Extracto (Resumen)</label>
                        <textarea wire:model="excerpt" rows="2"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="Breve resumen del artículo para previsualización"></textarea>
                        @error('excerpt') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Contenido del Artículo</label>
                        <textarea wire:model="content" rows="8"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="Escribe aquí el contenido completo del artículo..." required></textarea>
                        @error('content') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Imagen Destacada</label>
                        <input type="text" wire:model="featured_image"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="URL de la imagen destacada">
                        @error('featured_image') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 pt-5">
                        <h4 class="text-sm font-bold text-gray-700 mb-4">SEO y Metaetiquetas</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Meta Título</label>
                                <input type="text" wire:model="meta_title"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                                    placeholder="Título para SEO (máx 255 caracteres)">
                                @error('meta_title') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Meta Descripción</label>
                                <textarea wire:model="meta_description" rows="2"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                                    placeholder="Descripción para SEO (máx 500 caracteres)"></textarea>
                                @error('meta_description') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Meta Keywords</label>
                                <input type="text" wire:model="meta_keywords"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                                    placeholder="palabra1, palabra2, palabra3">
                                @error('meta_keywords') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Toggles -->
                    <div class="flex flex-wrap gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model="is_active" class="hidden peer">
                            <div class="w-12 h-6 bg-slate-300 peer-checked:bg-emerald-500 rounded-full relative transition-colors mr-3 shadow-inner">
                                <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-primary-600 transition-colors">Publicado</span>
                        </label>

                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model="is_featured" class="hidden peer">
                            <div class="w-12 h-6 bg-slate-300 peer-checked:bg-yellow-500 rounded-full relative transition-colors mr-3 shadow-inner">
                                <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-primary-600 transition-colors">Destacado</span>
                        </label>

                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model="is_pinned" class="hidden peer">
                            <div class="w-12 h-6 bg-slate-300 peer-checked:bg-primary-600 rounded-full relative transition-colors mr-3 shadow-inner">
                                <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-primary-600 transition-colors">Fijado</span>
                        </label>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all flex items-center justify-center min-w-[140px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar Cambios' : 'Crear Artículo' }}
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
</div>
