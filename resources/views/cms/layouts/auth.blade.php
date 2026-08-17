<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Helin CMS')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/cms/css/app.css'])
        @livewireStyles
        @stack('head')
    </head>
    <body class="h-full bg-soft">

        <!-- Flash Messages (Mantenemos tu lógica intacta) -->
        @foreach (['error', 'success', 'warning', 'info'] as $msg)
        @if (session($msg))
        <div class="fixed top-4 right-4 z-50 max-w-sm flash-message">
            @php
            $colors = [
            'error' => 'red', 'success' => 'primary',
            'warning' => 'amber', 'info' => 'primary'
            ];
            $icon = [
            'error' => 'alert-circle', 'success' => 'check-circle',
            'warning' => 'alert-triangle', 'info' => 'info'
            ];
            @endphp
            <div class="bg-{{ $colors[$msg] }}-50 border border-{{ $colors[$msg] }}-200 rounded-lg p-4 shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-ui-icon name="{{ $icon[$msg] }}" class="w-5 h-5 text-{{ $colors[$msg] }}-500" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-{{ $colors[$msg] }}-800">
                            {{ session($msg) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <!-- Split-screen Shell -->
        <div class="min-h-screen flex flex-col lg:flex-row">

            <!-- Left Branding Panel (desktop only) -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-500 to-primary-700 relative overflow-hidden flex-col items-center justify-center p-12 text-white">
                <div class="absolute inset-0 bg-grid-white/10 opacity-20 pointer-events-none"></div>
                <div class="relative z-10 text-center max-w-sm">
                    <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-2xl bg-white shadow-lg mb-6">
                        <span class="text-primary-600 font-bold text-3xl">H</span>
                    </div>
                    <h2 class="text-2xl font-bold">Helin CMS</h2>
                    <p class="mt-3 text-sm text-white/80 leading-relaxed">
                        Administra tu catálogo y solicitudes en un solo lugar.
                    </p>
                </div>
            </div>

            <!-- Mobile Compact Header (hidden on desktop) -->
            <div class="lg:hidden flex items-center justify-center gap-3 bg-primary-500 text-white py-6">
                <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-white shadow-sm">
                    <span class="text-primary-600 font-bold text-lg">H</span>
                </div>
                <span class="text-lg font-bold">Helin CMS</span>
            </div>

            <!-- Right Form Panel -->
            <main class="flex-1 flex items-center justify-center bg-soft px-4 py-12 sm:px-6 lg:px-8">
                {{--
                IMPORTANTE: Livewire 3 inyecta el contenido aquí.
                Dejamos ambos para que sea compatible con componentes antiguos y nuevos.
                --}}
                @isset($slot)
                {{ $slot }}
                @else
                @yield('content')
                @endisset
            </main>
        </div>

        @if (Route::is(['login', 'register']))
        <footer class="text-center py-4 text-sm text-body">
            <p>&copy; {{ date('Y') }} Helin Latam. Todos los derechos reservados.</p>
        </footer>
        @endif

        @vite(['resources/cms/js/auth.js'])
        @vite(['resources/cms/js/app.js'])

        <!-- Flash Messages Auto-hide -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(function (message) {
                    setTimeout(function () {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 0.5s ease-out';
                        setTimeout(() => message.remove(), 500);
                    }, 3000);
                });
            });
        </script>

        @stack('scripts')

        <script>
            window.App = {csrfToken: '{{ csrf_token() }}'};
        </script>

        @livewireScripts
    </body>
</html>
