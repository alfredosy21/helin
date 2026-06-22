<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Helin - Material Dental')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('helin/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('helin/css/helin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('helin/css/custom-container.css') }}">
    @yield('styles')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        turquesa: '#34b1b5',
                        'turquesa-light': '#97d5d4',
                        'turquesa-dark': '#289894'
                    }
                }
            }
        }
    </script>
</head>
<body style="background-color: var(--helin-bg);">

@include('web.partials.header')
@include('web.partials.mobile-nav')

@yield('content')

@include('web.partials.footer')

<script src="{{ asset('helin/js/helin-theme.js') }}"></script>
@yield('scripts')
</body>
</html>
