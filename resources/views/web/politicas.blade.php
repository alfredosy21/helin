@extends('web.layouts.app')

@section('title', 'Políticas - Helin')

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

    <h1 class="page-title">Políticas</h1>

    <section class="policies">

        @foreach($sections as $index => $section)
            @if($section->status_content)
                @php
                    /**
                     * Parse HTML content to extract policy points
                     */
                    $dom = new DOMDocument();
                    @$dom->loadHTML($section->content);
                    $xpath = new DOMXPath($dom);

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
                            'policyIcon' => $section->image ?? '🚚',
                            'policyNumber' => ($index + 1) . '.'
                        ],
                        'Términos y condiciones' => [
                            'policyId' => 'terminos-condiciones',
                            'policyIcon' => $section->image ?? '▤',
                            'policyNumber' => ($index + 1) . '.'
                        ],
                        'Políticas de privacidad' => [
                            'policyId' => 'privacidad',
                            'policyIcon' => $section->image ?? '♙',
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
                    'policyDescription' => strip_tags($section->content),
                    'policyPoints' => $policyPoints
                ]))
            @endif
        @endforeach
    </section>
</main>
@endsection
