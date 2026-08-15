@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Políticas - Helin')

@section('styles')
<link rel="stylesheet" href="@minAsset('helin/css/politicas.css')">
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
                     * Policy ID from url_button (e.g. "#envio-garantias" -> "envio-garantias"),
                     * fallback to "policy-{id}". Icon and number come from the section record.
                     */
                    $policyId = $section->url_button
                        ? ltrim($section->url_button, '#')
                        : 'policy-' . $section->id;

                    $policyData = [
                        'policyId' => $policyId,
                        'policyIcon' => $section->image,
                        'policyNumber' => ($index + 1) . '.',
                    ];
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
