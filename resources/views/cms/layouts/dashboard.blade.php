<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Helin Latam CMS' }}</title>

    {{-- Preload critical resources --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/cms/css/app.css'])

    {{-- Livewire Styles --}}
    @livewireStyles

    {{-- Additional Head Content --}}
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:static lg:inset-0">
            <div class="flex flex-col flex-grow bg-slate-800 dark:bg-slate-900 shadow-2xl shadow-black/10 overflow-hidden border-r border-slate-700 dark:border-slate-800">
                {{-- Logo --}}
                <div class="flex items-center h-16 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-inner">
                            <span class="text-blue-600 font-bold text-lg">H</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">Helin CMS</h1>
                            <p class="text-[10px] uppercase tracking-widest text-blue-100 opacity-80">Gestión Médica</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    {{-- Dashboard Link --}}
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3 px-3 py-2 text-sm font-medium {{ Request::is('cms/dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }} rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <x-ui-icon name="layout-dashboard" class="w-4 h-4" />
                        <span>Dashboard</span>
                    </a>

                    @php
                        $modules = \App\Models\Module::getModules();
                    @endphp

                    @foreach($modules as $module)
                        <div class="group" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <x-ui-icon name="{{ $module['class'] }}" class="w-4 h-4" />
                                    <span>{{ $module['name'] }}</span>
                                </div>

                                @if(isset($module['submodules']) && count($module['submodules']) > 0)
                                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>

                            {{-- Submodules --}}
                            @if(isset($module['submodules']) && count($module['submodules']) > 0)
                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-4 mt-2 border-l-2 border-gray-100 dark:border-gray-700 pl-2 space-y-1">
                                    @foreach($module['submodules'] as $submodule)
                                        <a href="{{ url($submodule['url']) }}" wire:navigate class="block px-3 py-2 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors">
                                            {{ $submodule['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Header --}}
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-30">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Mobile Menu Button --}}
                    <button class="lg:hidden p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <x-ui-icon name="menu" class="w-6 h-6" />
                    </button>

                    {{-- Actions --}}
                    <div class="flex items-center space-x-4 ml-auto">
                        {{-- Fullscreen Toggle --}}
                        <button onclick="toggleFullscreen()" id="fullscreen-toggle" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600">
                            <x-ui-icon name="maximize" class="w-5 h-5 block" />
                            <x-ui-icon name="minimize" class="w-5 h-5 hidden" />
                        </button>

                        {{-- User Avatar Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-transparent hover:border-gray-200">
                                @if(auth()->user()->image)
                                    <div class="w-8 h-8 rounded-lg overflow-hidden shadow-lg shadow-blue-500/20">
                                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                                        <span class="text-white text-sm font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">{{ auth()->user()->name }}</span>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 py-2 z-50"
                            >
                                <div class="px-4 py-2 border-b border-gray-50 dark:border-gray-700 mb-1">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-tighter">Mi Cuenta</p>
                                </div>
                                <a href="{{ route('profile.show') }}" wire:navigate class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                                    <x-ui-icon name="user" class="w-4 h-4" />
                                    <span>Mi Perfil</span>
                                </a>
                                <button onclick="openModal('logoutModal')" class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <x-ui-icon name="log-out" class="w-4 h-4" />
                                    <span>Cerrar Sesión</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 flex flex-col">
                <div class="flex-1 overflow-auto p-6 bg-slate-50 dark:bg-gray-900">
                    {{-- SOPORTE LIVEWIRE 3 --}}
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>

                {{-- Footer --}}
                <footer class="bg-white dark:bg-gray-800 border-t border-slate-200/50 dark:border-gray-700/50">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                © {{ date('Y') }} Helin CMS. Todos los derechos reservados.
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                Desarrollado por <span class="font-semibold text-slate-700 dark:text-slate-300">SyEvolution</span>
                            </div>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    {{-- Modals --}}
    <x-modal id="logoutModal" title="¿Cerrar sesión?" message="¿Estás seguro de que deseas cerrar tu sesión actual?" confirmText="Cerrar Sesión" cancelText="Cancelar" type="primary" icon="logout" />
    <x-modal id="inactivityModal" title="¿Sigue ahí?" message="Tu sesión está a punto de expirar" submessage="Selecciona 'Continuar' para mantenerte activo" confirmText="Continuar Sesión" cancelText="Cerrar Sesión" type="warning" icon="clock" />

    {{-- Flash Notifications Container --}}
    <div class="fixed top-4 right-4 z-[100] flex flex-col space-y-3">
        @foreach (['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="flash-message">
                    {{-- Aquí va tu diseño de alerta según el tipo --}}
                    <div class="bg-white dark:bg-gray-800 border-l-4 {{ $type == 'success' ? 'border-green-500' : ($type == 'error' ? 'border-red-500' : 'border-blue-500') }} rounded-xl p-4 shadow-2xl flex items-center space-x-4 min-w-[300px]">
                        <x-ui-icon name="{{ $type == 'success' ? 'check-circle' : 'alert-circle' }}" class="w-6 h-6 {{ $type == 'success' ? 'text-green-500' : 'text-red-500' }}" />
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ session($type) }}</p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/cms/js/dashboard.js'])

    <script>
        // Configuración del dashboard desde el servidor
        window.updateDashboardConfig({
            warningTime: {{ config('app.inactivity.warning_time', 600) }} * 1000,
            logoutTime: {{ config('app.inactivity.logout_time', 660) }} * 1000,
            logoutUrl: '{{ route("logout") }}',
            csrfToken: '{{ csrf_token() }}'
        });

        // Verificar que Toastify esté disponible
        if (typeof Toastify !== 'undefined') {
            console.log('Toastify está disponible en el dashboard');
        } else {
            console.warn('Toastify no está disponible en el dashboard');
        }

        // Test toast (comentar en producción)
        // setTimeout(() => {
        //     if (typeof Toastify !== 'undefined') {
        //         Toastify({
        //             text: "Toast de prueba en dashboard",
        //             duration: 3000,
        //             gravity: "top",
        //             position: "right",
        //             backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
        //         }).showToast();
        //     }
        // }, 2000);
    </script>

    @vite(['resources/cms/js/app.js'])
    @stack('scripts')
    @livewireScripts
</body>
</html>
