<!DOCTYPE html>
<html lang="es" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Helin Latam CMS' }}</title>

        {{-- Favicon --}}
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">

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
    <body class="min-h-screen bg-soft text-body transition-colors duration-200" x-data="{ sidebarOpen: false }">
        <div class="flex min-h-screen">

            {{-- Mobile Sidebar Backdrop --}}
            <div x-show="sidebarOpen"
                 x-cloak
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black/30 lg:hidden"></div>

            {{-- Ultra Clean & Modern Sidebar --}}
            <aside class="fixed inset-y-0 left-0 z-50 w-60 sm:w-64 lg:static lg:z-40 lg:inset-0 flex-shrink-0 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="flex flex-col flex-grow h-full bg-white overflow-hidden">

                    {{-- Logo Area - Identical to your exact brand header --}}
                    <div class="flex items-center h-14 sm:h-16 px-4 sm:px-6 bg-primary-500 text-white flex-shrink-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                <span class="text-primary-500 font-bold text-lg">H</span>
                            </div>
                            <div>
                                <h1 class="text-base font-bold tracking-tight leading-none">Helin CMS</h1>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation Loop Options --}}
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar" @click="if ($event.target.closest('a')) sidebarOpen = false">

                        {{-- Dashboard Link (With Custom Monitor/Desk Icon) --}}
                        @php
                        $isDashboard = Request::is('cms/dashboard') || Request::is('dashboard');
                        @endphp
                        <a href="{{ route('dashboard') }}" wire:navigate
                           class="flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 group {{ $isDashboard ? 'bg-primary-500/10 text-primary-600 font-semibold border-l-2 border-primary-500' : 'text-body hover:bg-slate-50 hover:text-heading' }}">
                            <x-ui-icon name="home" class="w-4 h-4 transition-transform group-hover:scale-105 {{ $isDashboard ? 'text-primary-600' : 'text-body group-hover:text-body' }}" />
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
                                     class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 group {{ $hasActiveSubmodule ? 'text-primary-600 bg-primary-500/5' : 'text-body hover:bg-slate-50 hover:text-heading' }}">

                                <div class="flex items-center space-x-3">
                                    {{-- Dynamic Module Category Custom Icon Class --}}
                                    <x-ui-icon name="{{ $module['class'] }}" class="w-4 h-4 {{ $hasActiveSubmodule ? 'text-primary-600' : 'text-body group-hover:text-body' }}" />
                                    <span>{{ $module['name'] }}</span>
                                </div>

                                {{-- Sleek Expandable Angle Dropdown Arrow --}}
                                @if(isset($module['submodules']) && count($module['submodules']) > 0)
                                <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-body group-hover:text-body"
                                     :class="{ 'rotate-180 text-primary-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                   class="flex items-center space-x-2.5 py-2 px-3 text-xs font-medium rounded-lg transition-all duration-150 {{ $isSubActive ? 'text-primary-600 bg-primary-500/10 font-semibold border-l-2 border-primary-500' : 'text-body hover:text-heading hover:bg-slate-50' }}">

                                    {{-- Dynamic Submodule Custom Icon Class from Database Attributes --}}
                                    @if(!empty($submodule['icon']))
                                    <x-ui-icon name="{{ $submodule['icon'] }}" class="w-3.5 h-3.5 {{ $isSubActive ? 'text-primary-600' : 'text-body group-hover:text-body' }}" />
                                    @else
                                    {{-- Fallback indicator element when icon structural context is null --}}
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isSubActive ? 'bg-primary-500' : 'bg-slate-300' }}"></span>
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
                <header class="bg-white z-30 sticky top-0">
                    <div class="flex items-center justify-between h-14 sm:h-16 px-3 sm:px-6">
                        {{-- Mobile View Menu Open Button --}}
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-body hover:text-heading">
                            <x-ui-icon name="menu" class="w-6 h-6" />
                        </button>

                        {{-- Actions Container Panel --}}
                        <div class="flex items-center space-x-2 sm:space-x-4 ml-auto">
                            {{-- Fullscreen Utility Screen Toggle --}}
                            <button onclick="toggleFullscreen()" id="fullscreen-toggle" class="hidden sm:block p-2 text-body hover:text-primary-600">
                                <x-ui-icon name="maximize" class="w-5 h-5 block" />
                                <x-ui-icon name="minimize" class="w-5 h-5 hidden" />
                            </button>

                            {{-- User Account Profile Navigation Dropdown Menu --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 transition-all border border-transparent hover:border-gray-200">
                                    @if(auth()->user()->image)
                                    <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-primary-500/20">
                                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    </div>
                                    @else
                                    <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center ring-2 ring-primary-500/20">
                                        <span class="text-white text-sm font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                    <span class="hidden md:block text-sm font-medium text-body">{{ auth()->user()->name }}</span>
                                </button>

                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    @click.away="open = false"
                                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-md border border-line py-2 z-50"
                                    >
                                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                        <p class="text-[13px] text-body uppercase font-bold tracking-tighter">{{ __('cms.general.my_account') }}</p>
                                    </div>
                                    <a href="{{ route('profile.show') }}" wire:navigate class="flex items-center space-x-3 px-4 py-2 text-sm text-body hover:bg-primary-500/10 hover:text-primary-600">
                                        <x-ui-icon name="user" class="w-4 h-4" />
                                        <span>{{ __('cms.general.my_profile') }}</span>
                                    </a>
                                    <button onclick="window.showLogoutAlert()" class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
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
                    <div class="flex-1 overflow-auto py-2 sm:py-3 px-1 sm:px-2 lg:px-3 bg-soft">
                        @isset($slot)
                        {{ $slot }}
                        @else
                        @yield('content')
                        @endisset
                    </div>

                    {{-- Footer Section Details --}}
                    <footer class="bg-soft border-t border-line">
                        <div class="px-3 sm:px-4 py-2.5">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-1 sm:gap-0">
                                <div class="text-[11px] sm:text-[13px] text-body">
                                    © {{ date('Y') }} Helin CMS. Todos los derechos reservados.
                                </div>
                                <div class="text-[11px] sm:text-[13px] text-body">
                                    Desarrollado por <span class="font-semibold text-body">SyEvolution</span>
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
            @php
            $flashBorder = match($type) {
                'success' => 'border-primary-500',
                'error' => 'border-red-500',
                'warning' => 'border-amber-500',
                default => 'border-primary-500',
            };
            $flashIcon = match($type) {
                'success' => 'text-primary-600',
                'error' => 'text-red-500',
                'warning' => 'text-amber-500',
                default => 'text-primary-600',
            };
            @endphp
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="flash-message">
                <div class="bg-white border-l-4 {{ $flashBorder }} rounded-xl p-4 shadow-md flex items-center space-x-4 min-w-[300px]">
                    <x-ui-icon name="{{ $type == 'success' ? 'check-circle' : 'alert-circle' }}" class="w-6 h-6 {{ $flashIcon }}" />
                    <p class="text-sm font-semibold text-body">{{ session($type) }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Third Party Core Production Assets Scripts --}}
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @vite(['resources/cms/js/dashboard.js'])

        <script>
                                        // Wait for dashboard.js to be loaded before calling updateDashboardConfig
                                        if (typeof window.updateDashboardConfig === 'function') {
                                        window.updateDashboardConfig({
                                        warningTime: {{ config('app.inactivity.warning_time', 600) }} * 1000,
                                                logoutTime: {{ config('app.inactivity.logout_time', 660) }} * 1000,
                                                logoutUrl: '{{ route("logout") }}',
                                                csrfToken: '{{ csrf_token() }}'
                                        });
                                        } else {
                                        // If not loaded yet, wait for it
                                        document.addEventListener('DOMContentLoaded', function() {
                                        if (typeof window.updateDashboardConfig === 'function') {
                                        window.updateDashboardConfig({
                                        warningTime: {{ config('app.inactivity.warning_time', 600) }} * 1000,
                                                logoutTime: {{ config('app.inactivity.logout_time', 660) }} * 1000,
                                                logoutUrl: '{{ route("logout") }}',
                                                csrfToken: '{{ csrf_token() }}'
                                        });
                                        }
                                        });
                                        }
        </script>

        @vite(['resources/cms/js/app.js'])
        @stack('scripts')
        @livewireScripts
    </body>
</html>
