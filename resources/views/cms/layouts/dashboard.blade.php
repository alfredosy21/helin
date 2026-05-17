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
<body class="min-h-screen bg-[#f8fafc] dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <div class="flex min-h-screen">

        {{-- Ultra Clean & Modern Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:static lg:inset-0 flex-shrink-0">
            <div class="flex flex-col flex-grow bg-[#1e293b] dark:bg-slate-950 overflow-hidden border-r border-slate-800/50">

                {{-- Logo Area - Identical to your exact brand header --}}
                <div class="flex items-center h-16 px-6 bg-[#09b6a2] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                            <span class="text-[#09b6a2] font-bold text-lg">H</span>
                        </div>
                        <div>
                            <h1 class="text-base font-bold tracking-tight leading-none">Helin CMS</h1>
                            <p class="text-[10px] uppercase tracking-widest text-white/90 mt-1">Gestión Médica</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Loop Options --}}
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">

                    {{-- Dashboard Link (With Custom Monitor/Desk Icon) --}}
                    @php
                        $isDashboard = Request::is('cms/dashboard') || Request::is('dashboard');
                    @endphp
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 group {{ $isDashboard ? 'bg-white/10 text-white font-semibold' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                        <x-ui-icon name="monitor" class="w-4 h-4 transition-transform group-hover:scale-105 {{ $isDashboard ? 'text-[#09b6a2]' : 'text-slate-400 group-hover:text-slate-300' }}" />
                        <span>{{ __('cms.general.desktop') }}</span>
                    </a>

                    @php
                        $modules = \App\Models\Module::getModules();
                    @endphp

                    @foreach($modules as $module)
                        @php
                            $hasActiveSubmodule = false;
                            if(isset($module['submodules'])) {
                                foreach($module['submodules'] as $sub) {
                                    if(Request::is(trim($sub['url'], '/')) || Request::is(trim($sub['url'], '/') . '/*')) {
                                        $hasActiveSubmodule = true;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        <div class="space-y-0.5" x-data="{ open: {{ $hasActiveSubmodule ? 'true' : 'false' }} }">
                            {{-- Main Module Action Row --}}
                            <button @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 group {{ $hasActiveSubmodule ? 'text-white bg-white/[0.02]' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">

                                <div class="flex items-center space-x-3">
                                    {{-- Dynamic Module Category Custom Icon Class --}}
                                    <x-ui-icon name="{{ $module['class'] }}" class="w-4 h-4 {{ $hasActiveSubmodule ? 'text-[#09b6a2]' : 'text-slate-400 group-hover:text-slate-300' }}" />
                                    <span>{{ $module['name'] }}</span>
                                </div>

                                {{-- Sleek Expandable Angle Dropdown Arrow --}}
                                @if(isset($module['submodules']) && count($module['submodules']) > 0)
                                    <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-slate-500 group-hover:text-slate-300"
                                         :class="{ 'rotate-180 text-[#09b6a2]': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>

                            {{-- Submodules Dropdown Container --}}
                            @if(isset($module['submodules']) && count($module['submodules']) > 0)
                                <div x-show="open"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 max-h-0 overflow-hidden"
                                     x-transition:enter-end="opacity-100 max-h-96 overflow-hidden"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 max-h-96"
                                     x-transition:leave-end="opacity-0 max-h-0"
                                     class="pl-7 pr-2 py-1 space-y-0.5">
                                    {{-- Reduced left indentation padding class from pl-11 to pl-7 --}}

                                    @foreach($module['submodules'] as $submodule)
                                        @php
                                            $isSubActive = Request::is(trim($submodule['url'], '/')) || Request::is(trim($submodule['url'], '/') . '/*');
                                        @endphp
                                        <a href="{{ url($submodule['url']) }}" wire:navigate
                                           class="flex items-center space-x-2.5 py-2 px-3 text-xs font-medium rounded-lg transition-all duration-150 {{ $isSubActive ? 'text-[#09b6a2] bg-white/5 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-white/[0.02]' }}">

                                            {{-- Dynamic Submodule Custom Icon Class from Database Attributes --}}
                                            @if(!empty($submodule['icon']))
                                                <x-ui-icon name="{{ $submodule['icon'] }}" class="w-3.5 h-3.5 {{ $isSubActive ? 'text-[#09b6a2]' : 'text-slate-500 group-hover:text-slate-400' }}" />
                                            @else
                                                {{-- Fallback indicator element when icon structural context is null --}}
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isSubActive ? 'bg-[#09b6a2]' : 'bg-slate-600' }}"></span>
                                            @endif

                                            <span>{{ $submodule['name'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </div>
        </aside>

        {{-- Main Content Window Area --}}
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Header Global Element --}}
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-[0_1px_2px_0_rgba(0,0,0,0.03)] z-30">
                <div class="flex items-center justify-between h-16 px-6">
                    {{-- Mobile View Menu Open Button --}}
                    <button class="lg:hidden p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <x-ui-icon name="menu" class="w-6 h-6" />
                    </button>

                    {{-- Actions Container Panel --}}
                    <div class="flex items-center space-x-4 ml-auto">
                        {{-- Fullscreen Utility Screen Toggle --}}
                        <button onclick="toggleFullscreen()" id="fullscreen-toggle" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600">
                            <x-ui-icon name="maximize" class="w-5 h-5 block" />
                            <x-ui-icon name="minimize" class="w-5 h-5 hidden" />
                        </button>

                        {{-- User Account Profile Navigation Dropdown Menu --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-transparent hover:border-gray-200">
                                @if(auth()->user()->image)
                                    <div class="w-8 h-8 rounded-lg overflow-hidden shadow-sm">
                                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-[#09b6a2] rounded-lg flex items-center justify-center shadow-md shadow-[#09b6a2]/10">
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
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-tighter">{{ __('cms.general.my_account') }}</p>
                                </div>
                                <a href="{{ route('profile.show') }}" wire:navigate class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#09b6a2]/10 hover:text-[#09b6a2]">
                                    <x-ui-icon name="user" class="w-4 h-4" />
                                    <span>{{ __('cms.general.my_profile') }}</span>
                                </a>
                                <button onclick="window.showLogoutAlert()" class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <x-ui-icon name="log-out" class="w-4 h-4" />
                                    <span>{{ __('cms.general.logout') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Application Render Slot View Engine --}}
            <main class="flex-1 flex flex-col min-h-0">
                <div class="flex-1 overflow-auto p-6 bg-[#f8fafc] dark:bg-gray-900">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>

                {{-- Footer Section Details --}}
                <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
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


    {{-- Flash Notifications Dynamic Banner Popups Container --}}
    <div class="fixed top-4 right-4 z-[100] flex flex-col space-y-3">
        @foreach (['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="flash-message">
                    <div class="bg-white dark:bg-gray-800 border-l-4 {{ $type == 'success' ? 'border-green-500' : ($type == 'error' ? 'border-red-500' : 'border-blue-500') }} rounded-xl p-4 shadow-2xl flex items-center space-x-4 min-w-[300px]">
                        <x-ui-icon name="{{ $type == 'success' ? 'check-circle' : 'alert-circle' }}" class="w-6 h-6 {{ $type == 'success' ? 'text-green-500' : 'text-red-500' }}" />
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ session($type) }}</p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Third Party Core Production Assets Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/cms/js/dashboard.js'])

    <script>
        window.updateDashboardConfig({
            warningTime: {{ config('app.inactivity.warning_time', 600) }} * 1000,
            logoutTime: {{ config('app.inactivity.logout_time', 660) }} * 1000,
            logoutUrl: '{{ route("logout") }}',
            csrfToken: '{{ csrf_token() }}'
        });
    </script>

    @vite(['resources/cms/js/app.js'])
    @stack('scripts')
    @livewireScripts
</body>
</html>
