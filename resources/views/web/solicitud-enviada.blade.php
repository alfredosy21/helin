@extends('web.layouts.app')

@section('title', $pageSeo?->seo_title ?? 'Solicitud Comercial Enviada - Helin')

@section('styles')
<link rel="stylesheet" href="@minAsset('helin/css/solicitud-enviada.css')">
@endsection

@section('content')
<main class="page">
    @include('web.components.breadcrumb', [
        'items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('carrito')],
            ['label' => 'Solicitud Comercial'],
            ['label' => 'Enviada', 'spanAttributes' => 'class="text-turquesa font-medium"']
        ]
    ])

    <section class="success-card">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h1>¡Hemos recibido tu solicitud!</h1>
        <p>Número de solicitud: <span class="request-number">{{ $commercialRequest->correlative }}</span></p>
        <p>Nuestro equipo comercial revisará tu pedido<br>y te contactará para continuar con la atención.</p>
        @php
            $orderWhatsApp = $commercialRequest->whatsappNumber->phone_number ?? null;
            if (!$orderWhatsApp) {
                $orderSettings = \App\Models\Settings::getSettings();
                $orderWhatsApp = ($orderSettings && !empty($orderSettings->valencia_whatsapp)) ? preg_replace('/[^0-9]/', '', $orderSettings->valencia_whatsapp) : null;
            }
            $orderWhatsAppMessage = 'Hola, he enviado una solicitud comercial ' . $commercialRequest->correlative . ' y me gustaría seguir con el proceso.';
        @endphp
        @if($orderWhatsApp)
        <a href="https://wa.me/{{ $orderWhatsApp }}?text={{ urlencode($orderWhatsAppMessage) }}" target="_blank" class="whatsapp-btn">
            <span class="whatsapp-mark"><i class="fab fa-whatsapp"></i></span>
            Enviar orden al WhatsApp de Helin
        </a>
        @endif
    </section>

    <section class="summary-layout">
        <article class="box">
            <div class="box-head">
                <div class="box-icon"><i class="fas fa-file-invoice"></i></div>
                <h2>Resumen de la orden</h2>
            </div>

            <div class="order-table">
                <div class="order-head">
                    <div>Producto</div>
                    <div>Precio (USD)</div>
                </div>

                @if(isset($cartItems) && count($cartItems) > 0)
                    @foreach($cartItems as $item)
                        <div class="order-row">
                            <div class="order-product">
                                <div class="mini-thumb">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width:36px;height:44px;object-fit:contain;">
                                    @else
                                        <div class="implant"></div>
                                    @endif
                                </div>
                                <div class="product-name">{{ $item->product->name }}<br>x {{ $item->quantity }}</div>
                            </div>
                            <div class="price">${{ number_format($item->product->price, 2) }}</div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="totals">
                <div class="total-line"><span>Subtotal:</span><strong>${{ number_format($subtotal, 2) }}</strong></div>
                <div class="total-line"><span>Tasa en Bs:</span><strong>Bs. {{ number_format($tasa ?? 0, 2) }}</strong></div>
                <div class="total-line final"><span>Total a pagar en Bs:</span><strong>Bs. {{ number_format($total ?? 0, 1) }}</strong></div>
            </div>
        </article>

        <article class="box">
            <div class="box-head">
                <div class="box-icon"><i class="fas fa-user"></i></div>
                <h2>Datos del cliente</h2>
            </div>

            @if(isset($commercialRequest))
                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-user"></i></div>
                    <div class="client-label">Cliente:</div>
                    <div class="client-value">{{ $commercialRequest->first_name }} {{ $commercialRequest->last_name }}</div>
                </div>

                @if($commercialRequest->company_name)
                    <div class="client-row">
                        <div class="client-icon"><i class="fas fa-building"></i></div>
                        <div class="client-label">Nombre de empresa:</div>
                        <div class="client-value">{{ $commercialRequest->company_name }}</div>
                    </div>
                @endif

                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-phone"></i></div>
                    <div class="client-label">Teléfono:</div>
                    <div class="client-value">{{ $commercialRequest->phone }}</div>
                </div>

                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-envelope"></i></div>
                    <div class="client-label">Email:</div>
                    <div class="client-value">{{ $commercialRequest->email }}</div>
                </div>

                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-truck"></i></div>
                    <div class="client-label">Método de entrega:</div>
                    <div class="client-value">{{ $commercialRequest->deliveryMethod->name ?? $commercialRequest->delivery_method }}</div>
                </div>

                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="client-label">Dirección de entrega:</div>
                    <div class="client-value">{{ $commercialRequest->address }}</div>
                </div>

                <div class="client-row">
                    <div class="client-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="client-label">Método de pago:</div>
                    <div class="client-value">{{ $commercialRequest->paymentMethod->name ?? $commercialRequest->payment_method }}</div>
                </div>

                @if($commercialRequest->payment_receipt_number)
                    <div class="client-row">
                        <div class="client-icon"><i class="fas fa-receipt"></i></div>
                        <div class="client-label">Nro. de comprobante:</div>
                        <div class="client-value">{{ $commercialRequest->payment_receipt_number }}</div>
                    </div>
                @endif
            @endif
        </article>
    </section>

    <a href="{{ URL::signedRoute('pdf.cotizacion', ['uuid' => $commercialRequest->uuid]) }}" target="_blank" class="download-btn">
        <span class="download-icon"><i class="fas fa-download"></i></span>
        Descargar cotización en PDF
    </a>
</main>

@include('web.partials.beneficios')
@endsection
