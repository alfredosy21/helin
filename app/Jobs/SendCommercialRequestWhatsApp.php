<?php

namespace App\Jobs;

use App\Models\CommercialRequest;
use App\Models\Product;
use App\Models\Settings;
use App\Services\WhatsAppBusinessService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SendCommercialRequestWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public int $commercialRequestId
    ) {
        $this->onQueue('whatsapp');
    }

    public function handle(WhatsAppBusinessService $whatsapp): void
    {
        $commercialRequest = CommercialRequest::with([
            'customerType',
            'state',
            'city',
            'deliveryMethod',
            'paymentMethod',
            'whatsappNumber',
        ])->find($this->commercialRequestId);

        if (!$commercialRequest) {
            Log::warning('SendCommercialRequestWhatsApp: CommercialRequest no encontrado', [
                'id' => $this->commercialRequestId,
            ]);
            return;
        }

        // Reconstruir items del carrito
        $cartItems = $this->buildCartItems($commercialRequest);
        $subtotal = $this->calculateSubtotal($cartItems);

        // 1. Enviar mensaje comercial al ejecutivo asignado
        $this->sendExecutiveMessage($whatsapp, $commercialRequest, $cartItems, $subtotal);

        // 2. Generar y enviar PDF al ejecutivo
        $this->sendExecutivePdf($whatsapp, $commercialRequest, $cartItems, $subtotal);

        // 3. Enviar mensaje de seguimiento al cliente
        $this->sendClientMessage($whatsapp, $commercialRequest);
    }

    /**
     * Construye el mensaje comercial para el ejecutivo.
     */
    private function buildExecutiveMessage(CommercialRequest $request, array $cartItems, float $subtotal): string
    {
        $productCount = count($cartItems);
        $correlative = $request->correlative;
        $fullName = $request->full_name;
        $company = $request->company_name ?: 'N/D';
        $phone = $request->phone;
        $email = $request->email;
        $state = $request->state?->name ?? 'N/D';
        $city = $request->city?->name ?? 'N/D';
        $observations = $request->observations ?: 'Sin observaciones';
        $deliveryMethod = $request->deliveryMethod?->name ?? 'N/D';
        $paymentMethod = $request->paymentMethod?->name ?? 'N/D';
        $amountFormatted = number_format($subtotal, 2, ',', '.');

        $message = __('whatsapp.executive_header') . "\n";
        $message .= __('whatsapp.executive_title') . "\n";
        $message .= __('whatsapp.executive_order_number', ['correlative' => $correlative]) . "\n";
        $message .= __('whatsapp.executive_name', ['name' => $fullName]) . "\n";
        $message .= __('whatsapp.executive_company', ['company' => $company]) . "\n";
        $message .= __('whatsapp.executive_phone', ['phone' => $phone]) . "\n";
        $message .= __('whatsapp.executive_email', ['email' => $email]) . "\n";
        $message .= __('whatsapp.executive_state', ['state' => $state]) . "\n";
        $message .= __('whatsapp.executive_city', ['city' => $city]) . "\n";
        $message .= __('whatsapp.executive_products', ['count' => $productCount]) . "\n";
        $message .= __('whatsapp.executive_amount', ['amount' => $amountFormatted]) . "\n";
        $message .= __('whatsapp.executive_observation', ['observation' => $observations]) . "\n";
        $message .= __('whatsapp.executive_delivery', ['method' => $deliveryMethod]) . "\n";
        $message .= __('whatsapp.executive_payment', ['method' => $paymentMethod]) . "\n";
        $message .= __('whatsapp.executive_pdf_footer');

        return $message;
    }

    /**
     * Envía el mensaje de texto al ejecutivo.
     */
    private function sendExecutiveMessage(WhatsAppBusinessService $whatsapp, CommercialRequest $request, array $cartItems, float $subtotal): void
    {
        $whatsappNumber = $request->whatsappNumber;

        if (!$whatsappNumber) {
            Log::info('SendCommercialRequestWhatsApp: Sin número de WhatsApp asignado para el ejecutivo', [
                'request_id' => $request->id,
                'state_id' => $request->state_id,
            ]);
            return;
        }

        $to = WhatsAppBusinessService::normalizePhone($whatsappNumber->phone_number);
        $message = $this->buildExecutiveMessage($request, $cartItems, $subtotal);

        $response = $whatsapp->sendTextMessage($to, $message);

        if ($response && !$response->successful()) {
            Log::error('SendCommercialRequestWhatsApp: Error enviando mensaje al ejecutivo', [
                'request_id' => $request->id,
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    /**
     * Genera el PDF y lo envía al ejecutivo.
     */
    private function sendExecutivePdf(WhatsAppBusinessService $whatsapp, CommercialRequest $request, array $cartItems, float $subtotal): void
    {
        $whatsappNumber = $request->whatsappNumber;

        if (!$whatsappNumber) {
            return;
        }

        try {
            // Generar el PDF
            $pdfContent = $this->generatePdf($request, $cartItems, $subtotal);

            // Guardar PDF en disco public para acceso vía URL
            $filename = "quotations/cotizacion-{$request->uuid}.pdf";
            Storage::disk('public')->put($filename, $pdfContent);

            // URL pública firmada del PDF (accesible para WhatsApp, protegida contra manipulación)
            $sitePdfUrl = URL::signedRoute('pdf.cotizacion', ['uuid' => $request->uuid]);

            $to = WhatsAppBusinessService::normalizePhone($whatsappNumber->phone_number);
            $caption = __('whatsapp.pdf_caption', [
                'correlative' => $request->correlative,
                'name' => $request->full_name,
            ]);

            // Usar la URL del sitio (accesible públicamente) para WhatsApp
            $response = $whatsapp->sendDocumentMessage(
                $to,
                $sitePdfUrl,
                $caption,
                "cotizacion-{$request->correlative}.pdf"
            );

            if ($response && !$response->successful()) {
                Log::error('SendCommercialRequestWhatsApp: Error enviando PDF al ejecutivo', [
                    'request_id' => $request->id,
                    'to' => $to,
                    'pdf_url' => $sitePdfUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SendCommercialRequestWhatsApp: Excepción generando/enviando PDF', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envía el mensaje de seguimiento al cliente.
     *
     * Importante: WhatsApp Business API solo permite mensajes free-form dentro
     * de la ventana de 24h después de que el cliente escribe primero. Como el
     * cliente envió un formulario web (no un mensaje WhatsApp), se debe usar
     * un template aprobado por Meta para iniciar la conversación.
     *
     * El template debe tener 2 variables en el body:
     *   {{1}} = número de orden (correlative)
     *   {{2}} = nombre del ejecutivo asignado
     */
    private function sendClientMessage(WhatsAppBusinessService $whatsapp, CommercialRequest $request): void
    {
        $clientPhone = WhatsAppBusinessService::normalizePhone($request->phone);

        if (empty($clientPhone)) {
            Log::info('SendCommercialRequestWhatsApp: Cliente sin teléfono válido', [
                'request_id' => $request->id,
            ]);
            return;
        }

        $correlative = $request->correlative;
        $executiveName = $request->whatsappNumber?->executive_name ?? 'nuestro equipo';

        $templateName = config('services.whatsapp.client_followup_template', 'order_followup');
        $languageCode = config('services.whatsapp.client_followup_language', 'es');

        // Usar template aprobado por Meta (requerido para iniciar conversación)
        $response = $whatsapp->sendTemplateMessage(
            $clientPhone,
            $templateName,
            $languageCode,
            [
                [
                    'type' => 'body',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $correlative,
                        ],
                        [
                            'type' => 'text',
                            'text' => $executiveName,
                        ],
                    ],
                ],
            ]
        );

        if ($response && !$response->successful()) {
            Log::error('SendCommercialRequestWhatsApp: Error enviando template al cliente', [
                'request_id' => $request->id,
                'to' => $clientPhone,
                'template' => $templateName,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    /**
     * Reconstruye los items del carrito desde cart_data.
     */
    private function buildCartItems(CommercialRequest $request): array
    {
        $cartItems = [];
        $cartData = is_array($request->cart_data) ? $request->cart_data : json_decode($request->cart_data, true) ?? [];

        foreach ($cartData as $item) {
            $slug = explode('::', $item['id'] ?? '')[0];
            $product = $slug ? Product::where('slug', $slug)->first() : null;
            if ($product) {
                $cartItems[] = (object) [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                ];
            }
        }

        return $cartItems;
    }

    /**
     * Calcula el subtotal de los items.
     */
    private function calculateSubtotal(array $cartItems): float
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        return $subtotal;
    }

    /**
     * Genera el PDF de cotización usando dompdf.
     */
    private function generatePdf(CommercialRequest $request, array $cartItems, float $subtotal): string
    {
        $settings = Settings::getSettings();

        $html = view('pdfs.cotizacion', [
            'commercialRequest' => $request,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'settings' => $settings,
        ])->render();

        return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->output();
    }
}
