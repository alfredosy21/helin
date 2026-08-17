<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ $commercialRequest->correlative }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 3px solid #00a89c;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .header-table td.right {
            text-align: right;
        }
        .header-left h1 {
            font-size: 24px;
            color: #00a89c;
            margin-bottom: 4px;
        }
        .header-left p {
            font-size: 11px;
            color: #666;
        }
        .header-right .order-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .header-right .order-date {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #00a89c;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 4px 8px 4px 0;
            vertical-align: top;
        }
        .info-grid .label {
            font-weight: bold;
            color: #555;
            width: 35%;
        }
        .info-grid .value {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .products-table th {
            background: #00a89c;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .products-table th.right { text-align: right; }
        .products-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .products-table td.right { text-align: right; }
        .products-table tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .totals {
            margin-top: 15px;
            width: 100%;
        }
        .totals-table {
            width: 300px;
            border-collapse: collapse;
            margin-left: auto;
        }
        .totals-table td {
            padding: 4px 0;
        }
        .totals-table td.label {
            text-align: left;
        }
        .totals-table td.value {
            text-align: right;
        }
        .totals-table tr.final td {
            border-top: 2px solid #00a89c;
            padding-top: 8px;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            background: #fff3cd;
            color: #856404;
        }
        .observations-text {
            padding: 8px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <h1>{{ $settings->name ?? 'Helin' }}</h1>
                    <p>{{ $settings->tagline ?? 'Sistemas de implantes dentales' }}</p>
                    @if(!empty($settings->email))
                    <p>Email: {{ $settings->email }}</p>
                    @endif
                    @if(!empty($settings->phone))
                    <p>Tel: {{ $settings->phone }}</p>
                    @endif
                </td>
                <td class="header-right right">
                    <div class="order-number">{{ $commercialRequest->correlative }}</div>
                    <div class="order-date">{{ $commercialRequest->created_at->format('d/m/Y H:i') }}</div>
                    <div style="margin-top: 8px;">
                        <span class="status-badge">{{ ucfirst($commercialRequest->status) }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Datos del Cliente</div>
        <table class="info-grid">
            <tr>
                <td class="label">Nombre:</td>
                <td class="value">{{ $commercialRequest->full_name }}</td>
            </tr>
            @if($commercialRequest->customerType)
            <tr>
                <td class="label">Tipo de cliente:</td>
                <td class="value">{{ $commercialRequest->customerType->name }}</td>
            </tr>
            @endif
            @if($commercialRequest->company_name)
            <tr>
                <td class="label">Empresa:</td>
                <td class="value">{{ $commercialRequest->company_name }}</td>
            </tr>
            @endif
            @if($commercialRequest->cedula)
            <tr>
                <td class="label">Cédula:</td>
                <td class="value">{{ $commercialRequest->cedula }}</td>
            </tr>
            @endif
            @if($commercialRequest->rif)
            <tr>
                <td class="label">RIF:</td>
                <td class="value">{{ $commercialRequest->rif }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Teléfono:</td>
                <td class="value">{{ $commercialRequest->phone }}</td>
            </tr>
            <tr>
                <td class="label">Correo:</td>
                <td class="value">{{ $commercialRequest->email }}</td>
            </tr>
            @if($commercialRequest->state)
            <tr>
                <td class="label">Estado:</td>
                <td class="value">{{ $commercialRequest->state->name }}</td>
            </tr>
            @endif
            @if($commercialRequest->city)
            <tr>
                <td class="label">Ciudad:</td>
                <td class="value">{{ $commercialRequest->city->name }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Dirección:</td>
                <td class="value">{{ $commercialRequest->address }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Productos Solicitados</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="right">Cantidad</th>
                    <th class="right">Precio Unit. (USD)</th>
                    <th class="right">Subtotal (USD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">${{ number_format($item->product->price, 2) }}</td>
                    <td class="right">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="value">${{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr class="final">
                    <td class="label">Total Estimado:</td>
                    <td class="value">${{ number_format($subtotal, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Entrega y Pago</div>
        <table class="info-grid">
            <tr>
                <td class="label">Método de entrega:</td>
                <td class="value">{{ $commercialRequest->deliveryMethod->name ?? 'N/D' }}</td>
            </tr>
            @if($commercialRequest->other_delivery_company)
            <tr>
                <td class="label">Otra empresa:</td>
                <td class="value">{{ $commercialRequest->other_delivery_company }}</td>
            </tr>
            @endif
            @if($commercialRequest->recipient_name)
            <tr>
                <td class="label">Destinatario:</td>
                <td class="value">{{ $commercialRequest->recipient_name }}</td>
            </tr>
            @endif
            @if($commercialRequest->recipient_document)
            <tr>
                <td class="label">Doc. destinatario:</td>
                <td class="value">{{ $commercialRequest->recipient_document }}</td>
            </tr>
            @endif
            @if($commercialRequest->recipient_phone)
            <tr>
                <td class="label">Tel. destinatario:</td>
                <td class="value">{{ $commercialRequest->recipient_phone }}</td>
            </tr>
            @endif
            @if($commercialRequest->shippingState)
            <tr>
                <td class="label">Estado de envío:</td>
                <td class="value">{{ $commercialRequest->shippingState->name }}</td>
            </tr>
            @endif
            @if($commercialRequest->shippingCity)
            <tr>
                <td class="label">Ciudad de envío:</td>
                <td class="value">{{ $commercialRequest->shippingCity->name }}</td>
            </tr>
            @endif
            @if($commercialRequest->destination_agency)
            <tr>
                <td class="label">Agencia destino:</td>
                <td class="value">{{ $commercialRequest->destination_agency }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Método de pago:</td>
                <td class="value">{{ $commercialRequest->paymentMethod->name ?? 'N/D' }}</td>
            </tr>
            @if($commercialRequest->payment_receipt_number)
            <tr>
                <td class="label">Nro. comprobante:</td>
                <td class="value">{{ $commercialRequest->payment_receipt_number }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($commercialRequest->observations)
    <div class="section">
        <div class="section-title">Observaciones</div>
        <p class="observations-text">{{ $commercialRequest->observations }}</p>
    </div>
    @endif

    @if($commercialRequest->whatsappNumber)
    <div class="section">
        <div class="section-title">Ejecutivo Asignado</div>
        <table class="info-grid">
            <tr>
                <td class="label">Ejecutivo:</td>
                <td class="value">{{ $commercialRequest->whatsappNumber->executive_name }}</td>
            </tr>
            <tr>
                <td class="label">WhatsApp:</td>
                <td class="value">{{ $commercialRequest->whatsappNumber->formatted_number }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>{{ $settings->name ?? 'Helin' }} — {{ $settings->copy ?? 'Todos los derechos reservados' }}</p>
        <p>Este documento es una cotización comercial y no constituye una confirmación de pedido.</p>
    </div>
</body>
</html>
