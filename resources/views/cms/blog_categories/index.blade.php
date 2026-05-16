<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <!-- Encabezado de la Sección -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Categorías de Blog</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona la taxonomía del blog con personalización visual y optimización SEO.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva categoría
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
                placeholder="Buscar por nombre, slug o descripción...">
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
                        <th class="px-6 py-4 font-semibold w-20 text-center">Orden</th>
                        <th class="px-6 py-4 font-semibold">Categoría</th>
                        <th class="px-6 py-4 font-semibold">Slug / URL</th>
                        <th class="px-6 py-4 font-semibold w-24 text-center">Color</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($blogCategories as $blogCategory)
                        <tr wire:key="blog-category-{{ $blogCategory->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-700 font-bold text-xs border border-gray-200">
                                    {{ $blogCategory->order }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($blogCategory->color)
                                        <div class="w-4 h-4 rounded-full border border-gray-200 category-color-indicator" data-category-color="{{ $blogCategory->color }}"></div>
                                    @endif
                                    <div>
                                        <span class="text-gray-900 font-bold text-sm block">
                                            {{ $blogCategory->name }}
                                        </span>
                                        @if($blogCategory->description)
                                            <span class="text-gray-500 text-xs block truncate max-w-xs">
                                                {{ $blogCategory->description }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-indigo-600 font-mono text-xs bg-indigo-50 px-2 py-1 rounded">
                                    {{ $blogCategory->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($blogCategory->color)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium category-color-badge" data-category-color="{{ $blogCategory->color }}">
                                        {{ $blogCategory->color }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="edit({{ $blogCategory->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar Categoría">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal(<?php echo $blogCategory->id; ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Categoría">
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
                                No se encontraron categorías de blog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-white border-t border-gray-100">
            {{ $blogCategories->links() }}
        </div>
    </div>

    <!-- Modal de Gestión -->
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="cancel"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $editingId ? 'Actualizar Categoría' : 'Nueva Categoría de Blog' }}
                </h3>
                <button wire:click="cancel" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="p-6 space-y-5">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Nombre de la Categoría</label>
                        <input type="text" wire:model="name"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="ej. Tecnología, Noticias, Tutoriales...">
                        @error('name') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Descripción (Opcional)</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400 resize-none"
                            placeholder="Breve descripción para SEO y visualización..."></textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Slug -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Slug (Opcional)</label>
                            <input type="text" wire:model="slug"
                                class="w-full border-gray-300 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                placeholder="ej. tecnologia">
                            <p class="mt-1 text-[10px] text-gray-400 italic">Si se deja vacío, se generará automáticamente.</p>
                            @error('slug') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>

                        <!-- Posición -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Posición en Menú</label>
                            <input type="number" wire:model="order"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                min="0">
                            @error('order') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Color -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Color Tema</label>
                            <div class="flex gap-2">
                                <input type="color" wire:model="color"
                                    class="h-10 w-20 border-gray-300 rounded cursor-pointer">
                                <input type="text" wire:model="color"
                                    class="flex-1 border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                    placeholder="#3B82F6"
                                    pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <p class="mt-1 text-[10px] text-gray-400 italic">Color para la interfaz</p>
                            @error('color') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>

                        <!-- Icono -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Icono (Opcional)</label>
                            <input type="text" wire:model="icon"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                placeholder="ej. book, news, tech">
                            @error('icon') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>

                        <!-- Imagen -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Imagen (Opcional)</label>
                            <input type="text" wire:model="image"
                                class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm"
                                placeholder="URL o ruta">
                            @error('image') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all flex items-center justify-center min-w-[140px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar Cambios' : 'Crear Categoría' }}
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply dynamic colors to category indicators
    const colorIndicators = document.querySelectorAll('.category-color-indicator');
    colorIndicators.forEach(indicator => {
        const color = indicator.getAttribute('data-category-color');
        if (color) {
            indicator.style.backgroundColor = color;
        }
    });

    // Apply dynamic colors to category badges
    const colorBadges = document.querySelectorAll('.category-color-badge');
    colorBadges.forEach(badge => {
        const color = badge.getAttribute('data-category-color');
        if (color) {
            // Create a lighter version for background
            const r = parseInt(color.slice(1, 3), 16);
            const g = parseInt(color.slice(3, 5), 16);
            const b = parseInt(color.slice(5, 7), 16);
            const lighterColor = `rgba(${r}, ${g}, ${b}, 0.2)`;

            badge.style.backgroundColor = lighterColor;
            badge.style.color = color;
        }
    });
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">¿Eliminar Categoría?</h3>
                    <p class="text-sm text-gray-600">¿Deseas eliminar esta categoría de blog? Se podrían afectar artículos asociados.</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteBlogCategoryId = null;

function openDeleteModal(blogCategoryId) {
    deleteBlogCategoryId = blogCategoryId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    deleteBlogCategoryId = null;
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    if (deleteBlogCategoryId) {
        Livewire.find('<?= $this->getId() ?>').confirmDelete(deleteBlogCategoryId);
        closeDeleteModal();
    }
}
</script>
