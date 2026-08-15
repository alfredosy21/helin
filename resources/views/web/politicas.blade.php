@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Políticas - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/politicas.css') }}">
@endsection

@section('content')
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
                    $policyContent = $section->content;

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
