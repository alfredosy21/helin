<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Users Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <x-ui-icon name="users" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <x-ui-icon name="package" class="w-4 h-4 text-green-600 dark:text-green-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Productos</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['products'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <x-ui-icon name="folder" class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Categorías</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['categories'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Brands Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <x-ui-icon name="tag" class="w-4 h-4 text-orange-600 dark:text-orange-400" />
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Marcas</h3>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['brands'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Products -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Productos Recientes
                </h3>
                <div class="space-y-4">
                    @forelse ($recentProducts as $product)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <x-ui-icon name="package" class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $product->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product->category->name ?? 'Sin categoría' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            No hay productos recientes
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Actividad Reciente
                </h3>
                <div class="space-y-4">
                    @forelse ($recentActivity as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <x-ui-icon name="{{ $activity['icon'] ?? 'document' }}" class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $activity['description'] ?? 'Actividad del sistema' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $activity['time'] ?? 'Hace unos momentos' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            No hay actividad reciente
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Acciones Rápidas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('catalog.products.create') }}" class="block text-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <x-ui-icon name="package-plus" class="w-5 h-5 mx-auto mb-2" />
                    Nuevo Producto
                </a>
                <a href="{{ route('catalog.categories.create') }}" class="block text-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <x-ui-icon name="folder-plus" class="w-5 h-5 mx-auto mb-2" />
                    Nueva Categoría
                </a>
                <a href="{{ route('catalog.brands.create') }}" class="block text-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <x-ui-icon name="tag" class="w-5 h-5 mx-auto mb-2" />
                    Nueva Marca
                </a>
                <a href="{{ route('settings.index') }}" class="block text-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <x-ui-icon name="cog" class="w-5 h-5 mx-auto mb-2" />
                    Configuración
                </a>
            </div>
        </div>
    </div>
</div>
