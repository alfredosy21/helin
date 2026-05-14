<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-pink-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 3s;"></div>
    </div>

    <!-- Header Section -->
    <div class="relative z-10 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            Gestión de Permisos
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Administra los permisos del rol: <span class="font-semibold">{{ $roleName ?? 'Unknown Role' }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button wire:click="toggleAllModules(1)" 
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activar Todo
                        </button>
                        <button wire:click="toggleAllModules(0)" 
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg font-semibold hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Desactivar Todo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions List -->
    <div class="relative z-10">
        @if($isLoading)
            <!-- Loading State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-12 text-center">
                <div class="w-12 h-12 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400">Cargando permisos...</p>
            </div>
        @elseif(count($permissions) > 0)
            <!-- Modules and Submodules -->
            <div class="space-y-4">
                @foreach($permissions as $module)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <!-- Module Header -->
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Module Icon -->
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                        <x-ui-icon name="{{ $module['class'] }}" class="w-6 h-6" />
                                    </div>
                                    
                                    <!-- Module Info -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $module['name'] }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ count($module['submodules']) }} submódulos
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Module Toggle -->
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium {{ $module['status'] == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $module['status'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <button 
                                        wire:click="toggleModulePermission({{ $module['module_id'] }}, {{ $module['status'] }})"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ $module['status'] == 1 ? 'bg-purple-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                        wire:loading.attr="disabled">
                                        <span class="sr-only">Toggle module permission</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $module['status'] == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submodules -->
                        @if(count($module['submodules']) > 0)
                            <div class="p-6 bg-gray-50 dark:bg-gray-900/50">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Submódulos</h4>
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleAllSubmodules(1)" 
                                            class="text-xs px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400 rounded-full hover:bg-green-200 dark:hover:bg-green-900/30 transition-colors">
                                            Activar todos
                                        </button>
                                        <button wire:click="toggleAllSubmodules(0)" 
                                            class="text-xs px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400 rounded-full hover:bg-red-200 dark:hover:bg-red-900/30 transition-colors">
                                            Desactivar todos
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    @foreach($module['submodules'] as $submodule)
                                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center space-x-3">
                                                <!-- Submodule Icon -->
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center text-white">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Submodule Name -->
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $submodule['name'] }}</span>
                                            </div>
                                            
                                            <!-- Submodule Toggle -->
                                            <div class="flex items-center space-x-3">
                                                <span class="text-xs font-medium {{ $submodule['status'] == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $submodule['status'] == 1 ? 'Activo' : 'Inactivo' }}
                                                </span>
                                                <button 
                                                    wire:click="toggleSubmodulePermission({{ $submodule['id'] }}, {{ $module['status'] }})"
                                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 {{ $submodule['status'] == 1 ? 'bg-purple-600' : 'bg-gray-200 dark:bg-gray-600' }} {{ $module['status'] != 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    wire:loading.attr="disabled"
                                                    {{ $module['status'] != 1 ? 'disabled' : '' }}>
                                                    <span class="sr-only">Toggle submodule permission</span>
                                                    <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform {{ $submodule['status'] == 1 ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                <p class="text-sm">Este módulo no tiene submódulos</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay permisos disponibles</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">No se encontraron módulos o permisos para este rol.</p>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="relative z-10 mt-8">
        <a href="{{ route('cms.roles') }}" wire:navigate class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl border border-gray-200/50 dark:border-gray-700/50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a Roles
        </a>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (event) => {
        // Handle toast notifications
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 max-w-sm transform transition-all duration-300 ease-in-out ${
            event.type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' :
            event.type === 'error' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' :
            event.type === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800' :
            'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800'
        } rounded-lg p-4 shadow-lg`;
        
        const icon = event.type === 'success' ? 'check-circle' : 
                   event.type === 'error' ? 'x-circle' : 
                   event.type === 'warning' ? 'alert-triangle' :
                   'info-circle';
        
        toast.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-${
                        event.type === 'success' ? 'green' :
                        event.type === 'error' ? 'red' :
                        event.type === 'warning' ? 'yellow' :
                        'blue'
                    }-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M${
                            event.type === 'success' ? '5 13l4 4L19 7' :
                            event.type === 'error' ? '6 18L18 6' :
                            event.type === 'warning' ? '12 9v2m0 4h.01' :
                            '13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                        }"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-${
                        event.type === 'success' ? 'green-800 dark:text-green-200' :
                        event.type === 'error' ? 'red-800 dark:text-red-200' :
                        event.type === 'warning' ? 'yellow-800 dark:text-yellow-200' :
                        'blue-800 dark:text-blue-200'
                    }">
                        ${event.message}
                    </p>
                </div>
            </div>
        </div>`;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.add('translate-x-0');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    });
});
</script>
