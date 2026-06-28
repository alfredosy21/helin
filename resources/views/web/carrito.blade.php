@extends('web.layouts.app')

@section('title', 'Carrito de Compras - Helin')

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'attributes' => 'class="text-sm mb-6"',
        'items' => [
            ['label' => 'Inicio', 'url' => route('home'), 'linkAttributes' => 'class="text-helin-text hover:text-turquesa"'],
            ['label' => 'Carrito', 'spanAttributes' => 'class="text-turquesa font-medium"']
        ],
        'separatorAttributes' => 'class="text-helin-text mx-2"'
    ])

    <div id="cart-page-root">
        <!-- Rendered dynamically by cart-ui.js -->
        <div class="flex items-center justify-center py-20">
            <i class="fas fa-spinner fa-spin text-turquesa text-3xl"></i>
        </div>
    </div>


</main>

@include('web.partials.beneficios')
@endsection
