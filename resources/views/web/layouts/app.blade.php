<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Helin - Material Dental')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('helin/css/helin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('helin/css/custom-container.css') }}">
    @yield('styles')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        turquesa: '#00A3A0',
                        'turquesa-light': '#4DD4D1',
                        'turquesa-dark': '#007A78'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">

@include('web.partials.header')
@include('web.partials.mobile-nav')

@yield('content')

@include('web.partials.footer')

<script src="{{ asset('helin/js/helin-theme.js') }}"></script>
@yield('scripts')
</body>
</html>
