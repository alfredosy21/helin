<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $settings = \App\Models\Settings::getSettings();
    @endphp
    <title>@yield('title', $settings->name ?? 'Helin - Material Dental')</title>
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">

    {{-- SEO Meta Tags --}}
    @if($settings)
        <meta name="description" content="{{ $settings->description ?? 'Soluciones médicas de alta calidad para profesionales de la salud. Especialistas en implantología, reingeniería y cirugía guiada.' }}">
        <meta name="keywords" content="{{ $settings->keywords ?? 'implantes, cirugía odontológica, material dental, helin, productos médicos' }}">
        <meta name="author" content="{{ $settings->name ?? 'Helin' }}">
        <meta property="og:title" content="@yield('title', $settings->name ?? 'Helin - Material Dental')">
        <meta property="og:description" content="{{ $settings->description ?? 'Soluciones médicas de alta calidad para profesionales de la salud' }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ $settings->image ? asset('storage/' . $settings->image) : asset('images/helin-og-default.jpg') }}">
        <meta property="og:site_name" content="{{ $settings->name ?? 'Helin' }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', $settings->name ?? 'Helin - Material Dental')">
        <meta name="twitter:description" content="{{ $settings->description ?? 'Soluciones médicas de alta calidad para profesionales de la salud' }}">
        <meta name="twitter:image" content="{{ $settings->image ? asset('storage/' . $settings->image) : asset('images/helin-twitter-default.jpg') }}">
    @else
        <meta name="description" content="Soluciones médicas de alta calidad para profesionales de la salud. Especialistas en implantología, reingeniería y cirugía guiada.">
        <meta name="keywords" content="implantes, cirugía odontológica, material dental, helin, productos médicos">
        <meta name="author" content="Helin">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('helin/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('helin/css/helin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('helin/css/custom-container.css') }}">
    @yield('styles')

    {{-- Google Analytics --}}
    @if($settings && $settings->analytics_code)
        {!! $settings->analytics_code !!}
    @endif

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
