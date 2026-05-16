<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - Helin CMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/cms/css/app.css'])
    @livewireStyles
    @stack('head')
</head>
<body class="h-full bg-[#f8fafc] dark:bg-gray-900">

    <!-- Flash Messages (Mantenemos tu lógica intacta) -->
    @foreach (['error', 'success', 'warning', 'info'] as $msg)
        @if (session($msg))
            <div class="fixed top-4 right-4 z-50 max-w-sm flash-message">
                @php
                    $colors = [
                        'error' => 'red', 'success' => 'green',
                        'warning' => 'yellow', 'info' => 'blue'
                    ];
                    $icon = [
                        'error' => 'alert-circle', 'success' => 'check-circle',
                        'warning' => 'alert-triangle', 'info' => 'info'
                    ];
                @endphp
                <div class="bg-{{ $colors[$msg] }}-50 dark:bg-{{ $colors[$msg] }}-900/20 border border-{{ $colors[$msg] }}-20 dark:border-{{ $colors[$msg] }}-800 rounded-lg p-4 shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-ui-icon name="{{ $icon[$msg] }}" class="w-5 h-5 text-{{ $colors[$msg] }}-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-{{ $colors[$msg] }}-800 dark:text-{{ $colors[$msg] }}-200">
                                {{ session($msg) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Main Content -->
    <main>
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

    @if (Route::is(['login', 'register']))
        <footer class="absolute bottom-0 left-0 right-0 text-center py-4 text-sm text-gray-500 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} Helin Latam. Todos los derechos reservados.</p>
        </footer>
    @endif

    @vite(['resources/cms/js/auth.js'])
@vite(['resources/cms/js/app.js'])

    <!-- Flash Messages Auto-hide -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => message.remove(), 500);
                }, 3000);
            });
        });
    </script>

    @stack('scripts')

    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>

    @livewireScripts
</body>
</html>
