<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mensajes de WhatsApp Business API
    |--------------------------------------------------------------------------
    | Plantillas de mensajes enviados automáticamente vía WhatsApp Business
    | Cloud API al procesar solicitudes comerciales.
    |
    */

    // Mensaje comercial automático al ejecutivo asignado
    'executive_header' => 'Mensaje comercial automático al ejecutivo asignado:',
    'executive_title' => 'Nuevo pedido de cotización - Canal WEB',
    'executive_order_number' => 'N. Orden: :correlative',
    'executive_name' => 'Nombre: :name',
    'executive_company' => 'Empresa: :company',
    'executive_phone' => 'Teléfono: :phone',
    'executive_email' => 'Correo: :email',
    'executive_state' => 'Estado: :state',
    'executive_city' => 'Ciudad: :city',
    'executive_products' => 'Productos: :count producto(s)',
    'executive_amount' => 'Monto estimado: $:amount',
    'executive_observation' => 'Observación: :observation',
    'executive_delivery' => 'Método de entrega: :method',
    'executive_payment' => 'Método de pago: :method',
    'executive_pdf_footer' => 'Ver detalle del pedido en PDF',

    // Mensaje de seguimiento al cliente (texto free-form usado en logs/fallback)
    'client_followup' => 'Hola, realicé una orden a través del sitio web de Helin y quiero conocer su estado. ¿Podrían ayudarme con el seguimiento?',
    'client_order_number' => 'Número de orden: :correlative',
    'client_executive' => 'Ejecutivo asignado: :executive',

    // Template de Meta para iniciar conversación con el cliente (ventana de 24h)
    // El template debe estar aprobado en Meta Business Manager
    // Variables del template: {{1}} = número de orden, {{2}} = nombre del ejecutivo
    'client_template_name' => 'order_followup',
    'client_template_language' => 'es',

    // Caption del PDF
    'pdf_caption' => 'Cotización :correlative - :name',
];
