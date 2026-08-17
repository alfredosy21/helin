<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
        <title>@yield('title', 'Helin CMS')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/cms/css/app.css'])
        @livewireStyles
        @stack('head')
    </head>
    <body class="h-full bg-soft">

        <!-- Flash Messages (estilo toast Helin - misma tarjeta que el carrito web) -->
        @foreach (['error', 'success', 'warning', 'info'] as $msg)
        @if (session($msg))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.CmsToast) {
                    window.CmsToast.show({ message: @json(session($msg)), type: @json($msg === 'error' ? 'error' : ($msg === 'success' ? 'success' : ($msg === 'warning' ? 'warning' : 'info'))) });
                }
            });
        </script>
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
            <div class="lg:hidden flex items-center justify-center gap-3 bg-primary-500 text-white py-5">
                <div class="h-9 w-9 flex items-center justify-center rounded-xl bg-white shadow-sm">
                    <span class="text-primary-600 font-bold text-base">H</span>
                </div>
                <span class="text-base font-bold">Helin CMS</span>
            </div>

            <!-- Right Form Panel -->
            <main class="flex-1 flex items-center justify-center bg-soft px-4 py-8 sm:py-12 sm:px-6 lg:px-8">
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

        @vite(['resources/cms/js/cms-toast.js', 'resources/cms/js/auth.js', 'resources/cms/js/app.js'])

        @stack('scripts')

        <script>
            window.App = {csrfToken: '{{ csrf_token() }}'};
        </script>

        @livewireScripts
    </body>
</html>
