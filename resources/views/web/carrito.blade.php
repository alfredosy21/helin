@extends('web.layouts.app')

@section('title', 'Carrito de Compras - Helin')

@push('styles')
<link rel="stylesheet" href="@minAsset('helin/css/carrito.css')">
@endpush

@section('content')
<hr class="hidden lg:block w-full" style="border:none;border-top:1px solid rgba(0,0,0,0.06);">

<main class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10 pt-2 pb-8">
    <div class="mb-8">
        @include('web.components.breadcrumb', [
            'attributes' => 'text-sm',
            'items' => [
                ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
                ['label' => 'Carrito', 'spanAttributes' => 'class="text-turquesa font-medium"']
            ],
            'separatorAttributes' => 'class="text-helin-text mx-2"'
        ])
    </div>

    <div id="cart-page-root">
        <!-- Rendered dynamically by cart-ui.js -->
        <div class="flex items-center justify-center py-20">
            <i class="fas fa-spinner fa-spin text-turquesa text-3xl"></i>
        </div>
    </div>


</main>

@include('web.partials.beneficios')
@endsection
