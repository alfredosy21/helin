<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <!-- Encabezado de la Sección -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Marcas de Productos</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona las marcas comerciales y su orden de visualización en el catálogo Helin.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Marca
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
                placeholder="Buscar por nombre o imagen...">
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
                        <th class="px-6 py-4 font-semibold">Marca</th>
                        <th class="px-6 py-4 font-semibold">Imagen</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($brands as $brand)
                        <tr wire:key="brand-{{ $brand->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-700 font-bold text-xs border border-gray-200">
                                    {{ $brand->order }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-900 font-bold text-sm">
                                    {{ $brand->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($brand->image)
                                    <img src="{{ $brand->image }}" alt="{{ $brand->name }}"
                                         class="w-12 h-12 object-cover rounded-lg border border-gray-200"
                                         onerror="this.src='/images/default-brand.png'">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="edit({{ $brand->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar Marca">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:confirm="¿Deseas eliminar esta marca? Se podrían afectar productos asociados." wire:click="confirmDelete({{ $brand->id }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Marca">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No se encontraron marcas en el catálogo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-white border-t border-gray-100">
            {{ $brands->links() }}
        </div>
    </div>

    <!-- Modal de Gestión -->
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="cancel"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $editingId ? 'Actualizar Marca' : 'Nueva Marca de Catálogo' }}
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
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Nombre de la Marca</label>
                        <input type="text" wire:model="name"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="ej. 3M, Siemens, Medtronic...">
                        @error('name') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Logo o Imagen (Opcional)</label>
                        <input type="text" wire:model="image"
                            class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl text-sm shadow-sm placeholder-gray-400"
                            placeholder="URL o ruta del logo...">
                        <p class="mt-1 text-[10px] text-gray-400 italic">URL del logo o referencia de imagen para la marca</p>
                        @error('image') <p class="mt-1 text-xs text-red-500 font-medium italic">{{ $message }}</p> @enderror
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

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all flex items-center justify-center min-w-[140px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar Cambios' : 'Crear Marca' }}
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <x-ui-card class="sticky top-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editingId ? 'Editar Marca' : 'Nueva Marca' }}
                    </h3>
                    @if($showForm)
                        <button wire:click="cancel" class="text-gray-400 hover:text-gray-600">
                            <x-ui-icon name="x" class="w-5 h-5" />
                        </button>
                    @endif
                </div>

                <form wire:submit="save" class="space-y-4">
                    <!-- Name Field -->
                    <div>
                        <x-ui-label for="name" required>Nombre de la Marca</x-ui-label>
                        <x-ui-input
                            id="name"
                            wire:model="name"
                            placeholder="Nombre de la marca"
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Field -->
                    <div>
                        <x-ui-label for="image">Logo o Imagen</x-ui-label>
                        <x-ui-input
                            id="image"
                            wire:model="image"
                            placeholder="URL del logo o referencia de imagen"
                        />
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">URL del logo o referencia de imagen</p>
                    </div>

                    <!-- Position Field -->
                    <div>
                        <x-ui-label for="position" required>Posición</x-ui-label>
                        <x-ui-input
                            id="position"
                            type="number"
                            wire:model="position"
                            placeholder="0"
                            required
                        />
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Prioridad de visualización</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <x-ui-button
                            type="submit"
                            variant="primary"
                            class="flex-1"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading wire:target="save">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Guardando...
                            </span>
                            <span wire:loading.remove wire:target="save">
                                {{ $editingId ? 'Actualizar' : 'Crear' }} Marca
                            </span>
                        </x-ui-button>

                        @if($showForm)
                            <x-ui-button
                                type="button"
                                variant="ghost"
                                wire:click="cancel"
                            >
                                Cancelar
                            </x-ui-button>
                        @endif
                    </div>
                </form>
            </x-ui-card>
        </div>

        <!-- List Section -->
        <div class="lg:col-span-2">
            <x-ui-card>
                <!-- Card Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Lista de Marcas</h3>

                    <!-- Search -->
                    <div class="relative w-64">
                        <x-ui-icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="Buscar marcas..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                    </div>
                </div>

                <!-- Brands Grid -->
                @if($brands->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($brands as $brand)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <!-- Brand Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $brand->name }}</h4>
                                        <p class="text-sm text-gray-500">Posición: {{ $brand->position }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="edit({{ $brand->id }})"
                                            class="p-1 text-blue-600 hover:bg-blue-50 rounded"
                                            title="Editar"
                                        >
                                            <x-ui-icon name="edit-2" class="w-4 h-4" />
                                        </button>
                                        <button
                                            wire:click="confirmDelete({{ $brand->id }})"
                                            class="p-1 text-red-600 hover:bg-red-50 rounded"
                                            title="Eliminar"
                                        >
                                            <x-ui-icon name="trash-2" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Brand Image Preview -->
                                @if($brand->image)
                                    <div class="mb-3">
                                        <img src="{{ $brand->image }}"
                                             alt="{{ $brand->name }}"
                                             class="w-full h-24 object-cover rounded-lg bg-gray-100"
                                             onerror="this.src='/images/default-brand.png'">
                                    </div>
                                @else
                                    <div class="mb-3 w-full h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <x-ui-icon name="image" class="w-8 h-8 text-gray-400" />
                                    </div>
                                @endif

                                <!-- Brand Info -->
                                <div class="text-sm text-gray-600">
                                    <p class="mb-1">ID: {{ $brand->id }}</p>
                                    <p>Creado: {{ $brand->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $brands->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <x-ui-icon name="tag" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay marcas</h3>
                        <p class="text-gray-500 mb-4">No se encontraron marcas. Crea tu primera marca.</p>
                        <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <x-ui-icon name="plus" class="w-4 h-4 mr-2" />
                            Crear Marca
                        </button>
                    </div>
                @endif
            </x-ui-card>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('open-form', () => {
        // Auto-scroll to form when opened
        setTimeout(() => {
            document.querySelector('.sticky')?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    });

    Livewire.on('close-form', () => {
        // Handle form close
    });

    Livewire.on('toast', (event) => {
        // Handle toast notifications
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 max-w-sm ${
            event.type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' :
            event.type === 'error' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' :
            'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800'
        } rounded-lg p-4 shadow-lg`;

        const icon = event.type === 'success' ? 'check-circle' :
                   event.type === 'error' ? 'x-circle' :
                   'info-circle';

        toast.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-ui-icon name="${icon}" class="w-5 h-5 text-${
                        event.type === 'success' ? 'green' :
                        event.type === 'error' ? 'red' : 'blue'
                    }-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-${
                        event.type === 'success' ? 'green-800 dark:text-green-200' :
                        event.type === 'error' ? 'red-800 dark:text-red-200' :
                        'blue-800 dark:text-blue-200'
                    }">
                        ${event.message}
                    </p>
                </div>
            </div>
        </div>`;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
});
</script>
@endsection
