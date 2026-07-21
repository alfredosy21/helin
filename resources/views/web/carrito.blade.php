@extends('web.layouts.app')

@section('title', 'Carrito de Compras - Helin')

@push('styles')
<style>
.qty-btn:hover { background-color: #6BC2C3 !important; color: #ffffff !important; }
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
input[type=number] { -moz-appearance: textfield; appearance: textfield; }
</style>
@endpush

@section('content')
<main class="container mx-auto px-4 pt-2 pb-8">
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
