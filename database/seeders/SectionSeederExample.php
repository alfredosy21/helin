<?php

// EJEMPLOS DE SECCIONES ESTANDARIZADAS CON BOTONES EDITABLES

return [
    // 1. SECCIÓN CON BOTONES EDITABLES (Nuestra Empresa - Team)
[
    'title' => 'Un equipo que te acompaña',
    'subtitle' => null,
    'description' => 'Contamos con un equipo comprometido en ofrecer asesoría experta, alineada a calidad y novedad constante, porque en cada paso el camino prevalece.',
    'layout_type' => 'team_section',
    'icon_style' => 'emoji',
    'items' => json_encode([
        'team_visual' => [
            'type' => 'placeholder',
            'count' => 8
        ]
    ]),
    'buttons' => json_encode([
        [
            'text' => '☏ Conoce al equipo',
            'url' => 'web.contactanos',
            'style' => 'outline'
        ]
    ]),
    'content' => null,
    'name_button' => '☏ Conoce al equipo', // Compatibilidad
    'url_button' => 'web.contactanos', // Compatibilidad
    'status' => 1,
    'status_content' => 1,
],

// 2. SECCIÓN CON MÚLTIPLES BOTONES (CTA de Nuestra Empresa)
[
    'title' => '¿Listo para transformar tu práctica clínica?',
    'subtitle' => null,
    'description' => 'Somos tu aliado en cada paso hacia la excelencia de la salud bucal.',
    'layout_type' => 'cta_section',
    'icon_style' => 'emoji',
    'items' => null,
    'buttons' => json_encode([
        [
            'text' => '☏ Háblale con WhatsApp',
            'url' => 'https://wa.me/584241232025',
            'style' => 'primary',
            'target' => '_blank'
        ],
        [
            'text' => '✉ Permítenos por correo',
            'url' => 'web.contactanos',
            'style' => 'outline'
        ]
    ]),
    'content' => null,
    'name_button' => null, // No aplica para múltiples botones
    'url_button' => null, // No aplica para múltiples botones
    'status' => 1,
    'status_content' => 1,
],

// 3. SECCIÓN DE POLÍTICAS CON BOTONES EDITABLES
[
    'title' => 'Políticas de envío y garantías',
    'subtitle' => null,
    'description' => 'En Helin, nos comprometemos a que recibas tus productos en óptimas condiciones y con la máxima seguridad. Conoce nuestras condiciones de envío, tiempos de entrega y garantías.',
    'layout_type' => 'policy_points',
    'icon_style' => 'emoji',
    'items' => json_encode([
        'policy_points' => [
            [
                'icon' => '▱',
                'title' => 'Envíos',
                'description' => 'Realizamos envíos a todo el territorio nacional. Los tiempos de entrega varían entre 2 y 7 días hábiles según tu ubicación.'
            ],
            [
                'icon' => '♡',
                'title' => 'Garantía',
                'description' => 'Todos nuestros productos cuentan con garantía contra defectos de fabricación por un período de 30 días desde la fecha de compra.'
            ],
            [
                'icon' => '↻',
                'title' => 'Devoluciones',
                'description' => 'Puedes solicitar una devolución dentro de los 7 días posteriores a la recepción del pedido, si el producto no ha sido usado y está en su empaque original.'
            ]
        ]
    ]),
    'buttons' => json_encode([
        [
            'text' => 'Ver más',
            'url' => '#envio-garantias',
            'style' => 'primary'
        ]
    ]),
    'content' => null,
    'name_button' => 'Ver más',
    'url_button' => '#envio-garantias',
    'status' => 1,
    'status_content' => 1,
],

// 4. SECCIÓN DE CONTACTO (sin botones, texto simple)
[
    'title' => '¿Tienes preguntas? Hablemos.',
    'subtitle' => null,
    'description' => 'Estamos aquí para ayudarte. Escríbenos o utiliza el formulario y nuestro equipo se pondrá en contacto contigo lo antes posible.',
    'layout_type' => 'text_simple',
    'icon_style' => 'emoji',
    'items' => null,
    'buttons' => null,
    'content' => null,
    'name_button' => null,
    'url_button' => null,
    'status' => 1,
    'status_content' => 1,
],

// 5. SECCIÓN CON GRID DE VALORES (Nuestra Empresa - About)
[
    'title' => 'Soluciones que impulsan mejores resultados clínicos',
    'subtitle' => 'Quiénes somos',
    'description' => 'Somos más que una casa comercial: un aliado con visión quirúrgica, clínica y digital, trabajando junto a especialistas, con educación sin fronteras, ética, foco en respaldo y calidad real.',
    'layout_type' => 'value_grid',
    'icon_style' => 'emoji',
    'items' => json_encode([
        'value_items' => [
            ['icon' => '♡', 'text' => 'Calidad comprobada'],
            ['icon' => '☊', 'text' => 'Asesoría especializada'],
            ['icon' => '▱', 'text' => 'Portafolio completo'],
            ['icon' => '✓', 'text' => 'Respaldo y confianza']
        ]
    ]),
    'buttons' => null,
    'content' => null,
    'name_button' => null,
    'url_button' => null,
    'status' => 1,
    'status_content' => 1,
]
