<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppBusinessService
{
    private string $baseUrl;
    private string $apiVersion;
    private ?string $accessToken;
    private ?string $phoneNumberId;
    private bool $enabled;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url', 'https://graph.facebook.com');
        $this->apiVersion = config('services.whatsapp.api_version', 'v21.0');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->enabled = (bool) config('services.whatsapp.enabled', false);
    }

    /**
     * Verifica si el servicio está habilitado y configurado.
     */
    public function isConfigured(): bool
    {
        return $this->enabled
            && !empty($this->accessToken)
            && !empty($this->phoneNumberId);
    }

    /**
     * Envía un mensaje de texto simple a un número de WhatsApp.
     *
     * @param string $to Número en formato internacional sin "+" (ej: 584242789481)
     * @param string $body Texto del mensaje
     */
    public function sendTextMessage(string $to, string $body): ?Response
    {
        if (!$this->isConfigured()) {
            Log::info('WhatsApp Business API no configurado. Mensaje no enviado.', [
                'to' => $to,
                'body_preview' => mb_substr($body, 0, 80),
            ]);
            return null;
        }

        $url = "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        return Http::withToken($this->accessToken)
            ->timeout(30)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $body,
                ],
            ]);
    }

    /**
     * Envía un documento (PDF u otro) con un caption opcional.
     *
     * @param string $to Número en formato internacional sin "+"
     * @param string $documentUrl URL pública del documento (WhatsApp debe poder accederlo)
     * @param string $caption Texto adjunto al documento
     * @param string|null $filename Nombre del archivo a mostrar
     */
    public function sendDocumentMessage(string $to, string $documentUrl, string $caption = '', ?string $filename = null): ?Response
    {
        if (!$this->isConfigured()) {
            Log::info('WhatsApp Business API no configurado. Documento no enviado.', [
                'to' => $to,
                'document_url' => $documentUrl,
            ]);
            return null;
        }

        $url = "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'link' => $documentUrl,
                'caption' => $caption,
            ],
        ];

        if ($filename) {
            $payload['document']['filename'] = $filename;
        }

        return Http::withToken($this->accessToken)
            ->timeout(30)
            ->post($url, $payload);
    }

    /**
     * Envía una plantilla (template) aprobada por Meta.
     *
     * @param string $to Número de destino
     * @param string $templateName Nombre de la plantilla aprobada en Meta Business
     * @param string $languageCode Código de idioma (ej: es, en_US)
     * @param array $components Componentes dinámicos (header, body, buttons)
     */
    public function sendTemplateMessage(string $to, string $templateName, string $languageCode = 'es', array $components = []): ?Response
    {
        if (!$this->isConfigured()) {
            Log::info('WhatsApp Business API no configurado. Template no enviado.', [
                'to' => $to,
                'template' => $templateName,
            ]);
            return null;
        }

        $url = "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        return Http::withToken($this->accessToken)
            ->timeout(30)
            ->post($url, $payload);
    }

    /**
     * Normaliza un número de teléfono al formato internacional sin "+" ni espacios.
     * Ej: "+58 412-555-0000" => "584125550000"
     *
     * Si el número no tiene código de país y tiene 10 dígitos (formato Venezuela
     * móvil: 4XX-XXX-XXXX), se asume código 58 (Venezuela).
     */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Números venezolanos móviles tienen 10 dígitos (4XX XXX XXXX)
        // sin código de país. Agregar 58.
        if (strlen($digits) === 10 && str_starts_with($digits, '4')) {
            $digits = '58' . $digits;
        }

        // Números venezolanos fijos tienen 10 dígitos (2XX XXX XXXX)
        // sin código de país. Agregar 58.
        if (strlen($digits) === 10 && str_starts_with($digits, '2')) {
            $digits = '58' . $digits;
        }

        return $digits;
    }
}
