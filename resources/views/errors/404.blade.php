@extends('web.layouts.app')

@section('title', 'Página no encontrada - Helin')

@section('content')
@php
    $settings = \App\Models\Settings::getSettings();
    $whatsappNumber = $settings && $settings->phone ? preg_replace('/[^0-9]/', '', $settings->phone) : null;
    $whatsappUrl = $whatsappNumber ? 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode('Hola, estoy en el sitio de Helin y necesito ayuda.') : route('contactanos');
@endphp

<main class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-helin-soft">
    <div class="max-w-2xl w-full text-center">
        <div class="mb-8">
            <span class="inline-block text-[8rem] md:text-[10rem] leading-none font-heading font-bold text-turquesa/20 select-none" aria-hidden="true">404</span>
        </div>

        <h1 class="text-3xl md:text-4xl font-heading font-bold text-helin-heading mb-4">
            Esta página no está disponible
        </h1>

        <p class="text-lg text-helin-text/80 mb-8 max-w-lg mx-auto leading-relaxed">
            Parece que el contenido fue movido o ya no existe. Nuestro equipo sigue listo para ayudarte a encontrar la solución adecuada para cada procedimiento clínico.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-turquesa hover:bg-turquesa-dark text-white font-bold text-sm rounded-full transition-colors shadow-lg shadow-turquesa/25">
                <i class="fas fa-th-large"></i>
                Explorar productos
            </a>

            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 border-2 border-turquesa text-turquesa hover:bg-turquesa hover:text-white font-bold text-sm rounded-full transition-colors">
                <i class="fab fa-whatsapp"></i>
                Hablar con un asesor
            </a>
        </div>

        <div class="mt-12 pt-8 border-t border-helin-border/50">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-helin-text hover:text-turquesa transition-colors">
                <i class="fas fa-arrow-left"></i>
                Volver al inicio
            </a>
        </div>
    </div>
</main>
@endsection
