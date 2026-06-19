@extends('web.layouts.app')

@section('title', 'Políticas - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/politicas.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('web.home')],
            ['label' => 'Políticas']
        ]
    ])

    <h1 class="page-title">Políticas</h1>

    <section class="policies">
        @include('web.components.policy-card', [
            'policyId' => 'envio-garantias',
            'policyIcon' => '🚚',
            'policyNumber' => '1.',
            'policyTitle' => 'Políticas de envío y garantías',
            'policyDescription' => 'En Helin, nos comprometemos a que recibas tus productos en óptimas condiciones y con la máxima seguridad. Conoce nuestras condiciones de envío, tiempos de entrega y garantías.',
            'policyPoints' => [
                ['icon' => '▱', 'title' => 'Envíos', 'description' => 'Realizamos envíos a todo el territorio nacional. Los tiempos de entrega varían entre 2 y 7 días hábiles según tu ubicación.'],
                ['icon' => '♡', 'title' => 'Garantía', 'description' => 'Todos nuestros productos cuentan con garantía contra defectos de fabricación por un período de 30 días desde la fecha de compra.'],
                ['icon' => '↻', 'title' => 'Devoluciones', 'description' => 'Puedes solicitar una devolución dentro de los 7 días posteriores a la recepción del pedido, si el producto no ha sido usado y está en su empaque original.']
            ]
        ])

        @include('web.components.policy-card', [
            'policyId' => 'terminos-condiciones',
            'policyIcon' => '▤',
            'policyNumber' => '2.',
            'policyTitle' => 'Términos y condiciones',
            'policyDescription' => 'Al acceder y utilizar nuestro sitio web y servicios, aceptas cumplir con los siguientes términos y condiciones. Te recomendamos leerlos cuidadosamente.',
            'policyPoints' => [
                ['icon' => '♙', 'title' => 'Uso del sitio', 'description' => 'El contenido de este sitio es para fines informativos y de compra personal. Queda prohibido su uso comercial no autorizado.'],
                ['icon' => '▭', 'title' => 'Pedidos y pagos', 'description' => 'Los pedidos están sujetos a disponibilidad y confirmación de pago. Aceptamos pagos con tarjeta de crédito, débito y otros métodos seguros.'],
                ['icon' => '▢', 'title' => 'Responsabilidades', 'description' => 'Helin no se hace responsable por el uso indebido de los productos adquiridos ni por daños derivados de causas fuera de nuestro control.']
            ]
        ])

        @include('web.components.policy-card', [
            'policyId' => 'privacidad',
            'policyIcon' => '♙',
            'policyNumber' => '3.',
            'policyTitle' => 'Políticas de privacidad',
            'policyDescription' => 'En Helin, tu privacidad es nuestra prioridad. Esta política explica cómo recopilamos, usamos y protegemos tu información personal de acuerdo con la normativa aplicable.',
            'policyPoints' => [
                ['icon' => '▤', 'title' => 'Información que recopilamos', 'description' => 'Recopilamos datos personales como nombre, correo electrónico, dirección de envío y método de pago para procesar tus pedidos y mejorar tu experiencia.'],
                ['icon' => '▢', 'title' => 'Uso de la información', 'description' => 'Utilizamos tu información únicamente para gestionar tus pedidos, brindarte soporte y enviarte comunicaciones relevantes sobre nuestros productos y servicios.'],
                ['icon' => '♡', 'title' => 'Protección de datos', 'description' => 'Implementamos medidas técnicas y organizativas para proteger tu información personal. No compartimos tus datos con terceros sin tu consentimiento.']
            ]
        ])
    </section>
</main>
@endsection
