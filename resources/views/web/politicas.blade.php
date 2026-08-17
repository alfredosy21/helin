@extends('web.layouts.app')

@section('title', 'Políticas - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/politicas.css') }}">
@endsection

@section('content')
<hr class="hidden lg:block w-full" style="border:none;border-top:1px solid rgba(0,0,0,0.06);">

<main class="container mx-auto px-4 py-8">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Políticas']
        ]
    ])

    <header class="policies-header">
        <h1 class="page-title">Políticas</h1>
    </header>

    <section class="policies">

        @foreach($sections as $index => $section)
            @if($section->status_content)
                @php
                    $policyContent = match($section->title) {
                        'Políticas de envío y garantías' => '<p>En Helin trabajamos para que tus productos lleguen de forma segura y en el menor tiempo posible. Conoce nuestras condiciones de envío, cobertura de garantía y proceso de devoluciones.</p>
<div class="point"><div class="point-icon">🚚</div><h3>Envíos</h3><p>Realizamos envíos a todo el territorio nacional mediante empresas de transporte autorizadas. Los tiempos de entrega pueden variar según la ciudad y la disponibilidad del producto.</p></div>
<div class="point"><div class="point-icon">🛡️</div><h3>Garantía</h3><p>Todos nuestros productos cuentan con garantía por defectos de fabricación, siempre que hayan sido utilizados siguiendo las recomendaciones del fabricante.</p></div>
<div class="point"><div class="point-icon">🔄</div><h3>Cambios y devoluciones</h3><p>Si recibes un producto incorrecto o con daños atribuibles al transporte, podrás solicitar una revisión dentro del plazo establecido por nuestras políticas.</p></div>',
                        'Términos y condiciones' => '<p>Al navegar por nuestro sitio web y realizar una compra, aceptas los términos que regulan el uso de nuestros servicios y la relación comercial con Helin.</p>
<div class="point"><div class="point-icon">🌐</div><h3>Uso del sitio</h3><p>La información publicada tiene fines comerciales e informativos. El contenido del sitio no podrá ser reproducido sin autorización previa.</p></div>
<div class="point"><div class="point-icon">💳</div><h3>Compras y pagos</h3><p>Todos los pedidos están sujetos a confirmación de disponibilidad y validación del pago antes de iniciar el proceso de despacho.</p></div>
<div class="point"><div class="point-icon">✅</div><h3>Responsabilidades</h3><p>Helin procura mantener la información actualizada, aunque las especificaciones, imágenes y precios pueden modificarse sin previo aviso.</p></div>',
                        default => $section->content
                    };

                    /**
                     * Parse HTML content to extract policy points
                     */
                    $dom = new DOMDocument();
                    @$dom->loadHTML(mb_convert_encoding($policyContent, 'HTML-ENTITIES', 'UTF-8'));
                    $xpath = new DOMXPath($dom);

                    $introNode = $xpath->query("//body/p")->item(0);
                    $policyIntro = $introNode ? trim($introNode->textContent) : strip_tags($policyContent);

                    $policyPoints = [];
                    $pointNodes = $xpath->query("//div[@class='point']");

                    foreach($pointNodes as $pointNode) {
                        $iconNode = $xpath->query(".//div[@class='point-icon']", $pointNode)->item(0);
                        $titleNode = $xpath->query(".//h3", $pointNode)->item(0);
                        $descNode = $xpath->query(".//p", $pointNode)->item(0);

                        $policyPoints[] = [
                            'icon' => $iconNode ? trim($iconNode->textContent) : '•',
                            'title' => $titleNode ? trim($titleNode->textContent) : '',
                            'description' => $descNode ? trim($descNode->textContent) : ''
                        ];
                    }

                    /**
                     * Determine icon and ID based on title
                     */
                    $policyData = match($section->title) {
                        'Políticas de envío y garantías' => [
                            'policyId' => 'envio-garantias',
                            'policyIcon' => $section->image ?? '<i class="fas fa-truck" aria-hidden="true"></i>',
                            'policyNumber' => ($index + 1) . '.'
                        ],
                        'Términos y condiciones' => [
                            'policyId' => 'terminos-condiciones',
                            'policyIcon' => $section->image ?? '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
                            'policyNumber' => ($index + 1) . '.'
                        ],
                        'Políticas de privacidad' => [
                            'policyId' => 'privacidad',
                            'policyIcon' => $section->image ?? '<i class="fas fa-shield-alt" aria-hidden="true"></i>',
                            'policyNumber' => ($index + 1) . '.'
                        ],
                        default => [
                            'policyId' => 'policy-' . $section->id,
                            'policyIcon' => $section->image ?? '📋',
                            'policyNumber' => ($index + 1) . '.'
                        ]
                    };
                @endphp

                @include('web.components.policy-card', array_merge($policyData, [
                    'policyTitle' => $section->title,
                    'policyDescription' => $policyIntro,
                    'policyPoints' => $policyPoints
                ]))
            @endif
        @endforeach
    </section>
</main>
@endsection
