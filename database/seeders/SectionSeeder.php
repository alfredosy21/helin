<?php

namespace Database\Seeders;

use App\Models\Sections;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{


    // ==========================================
    // ---------- TIPOS DE LAYOUT ESTÁNDAR ----------
    // ==========================================

    // LAYOUT TYPES
    const LAYOUT_TEXT_SIMPLE = 'text_simple'; // Texto simple (título + descripción)
    const LAYOUT_HERO_BADGES = 'hero_badges'; // Hero con badges verticales
    const LAYOUT_FEEDBACK_BADGES = 'feedback_badges'; // Badges de feedback
    const LAYOUT_STATS_GRID = 'stats_grid'; // Grid de estadísticas
    const LAYOUT_SEARCH_FEATURES = 'search_features'; // Features de búsqueda
    const LAYOUT_POLICY_POINTS = 'policy_points'; // Puntos de políticas
    const LAYOUT_MISSION_VISION = 'mission_vision'; // Misión y visión
    const LAYOUT_VALUE_GRID = 'value_grid'; // Grid de valores
    const LAYOUT_BRAND_GRID = 'brand_grid'; // Grid de marcas
    const LAYOUT_CITIES_LIST = 'cities_list'; // Lista de ciudades
    const LAYOUT_FEATURE_BOX = 'feature_box'; // Caja de características
    const LAYOUT_HERO_BUTTONS = 'hero_buttons'; // Hero con botones
    const LAYOUT_TESTIMONIALS = 'testimonials'; // Sección de testimonios

    // ICON STYLES
    const ICON_EMOJI = 'emoji'; // Iconos emoji
    const ICON_LUCIDE = 'lucide'; // Iconos Lucide
    const ICON_CUSTOM = 'custom'; // Iconos personalizados
    const ICON_NONE = 'none'; // Sin iconos
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Limpiar la tabla antes de sembrar para evitar duplicados si se corre varias veces
        DB::table('sections')->truncate();

        $sections = [
            // ==========================================
            // ---------- SECCIONES DE HOME --------------
            // ==========================================
            Sections::HERO_HOME => [
                'title' => 'helin.',
                'subtitle' => 'Soluciones que cuidan.',
                'description' => 'Instrumental, insumos y soluciones diseñadas para procedimientos quirúrgicos seguros, precisos y eficientes.',
                'image' => 'hero-home.jpg',
                'layout_type' => self::LAYOUT_HERO_BADGES,
                'icon_style' => self::ICON_EMOJI,
                'items' => json_encode([
                    'hero_badges' => [
                        ['icon' => '✓', 'text' => 'Calidad garantizada'],
                        ['icon' => '△', 'text' => 'Alta precisión'],
                        ['icon' => '◎', 'text' => 'Soluciones quirúrgicas'],
                        ['icon' => '✚', 'text' => 'Asesoría especializada']
                    ]
                ]),
                'buttons' => json_encode([
                    [
                        'text' => 'Ir a productos →',
                        'url' => 'catalogo',
                        'style' => 'primary'
                    ],
                    [
                        'text' => 'Hablar con un asesor',
                        'url' => 'contactanos',
                        'style' => 'secondary'
                    ]
                ]),
                'content' => 'TODO EN CIRUGÍA<br>ODONTOLÓGICA<br><span style="color: #123F4A;">ESPECIALIZADA.</span>',
                'name_button' => 'Ir a productos →', // Mantener para compatibilidad temporal
                'url_button' => 'catalogo', // Mantener para compatibilidad temporal
                'status' => 1,
                'status_content' => 1,
            ],
            Sections::FEEDBACK_BANNER => [
                'title' => '¡Nos encantaría conocer tu opinión!',
                'image' => null,
                'name_button' => 'Compartir comentario',
                'url_button' => 'web.contactanos',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Tu experiencia es importante para nosotros. Comparte tu opinión y ayúdanos a mejorar nuestros servicios y productos.</p>
<div class="feedback-badges">
    <div class="badge">
        <div class="mini-icon">⭐</div>
        <span>Califica nuestro<br>servicio</span>
    </div>
    <div class="badge">
        <div class="mini-icon">💬</div>
        <span>Comparte tu<br>experiencia</span>
    </div>
    <div class="badge">
        <div class="mini-icon">📝</div>
        <span>Sugerencias<br>de mejora</span>
    </div>
</div>
HTML,
            ],
            Sections::CLINICAL_RESOURCES_HERO => [
                'title' => 'Centro de conocimiento clínico',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<span class="hero-badge">Centro de conocimiento clínico</span>
<h1>Recursos clínicos para decisiones más precisas.</h1>
<p>Explora casos clínicos, videos, manuales técnicos, fichas descargables y guías de referencia para profesionales odontológicos.</p>
<div class="hero-buttons">
    <a href="#recursos" class="hero-btn-primary">Explorar recursos →</a>
    <a href="#casos" class="hero-btn-secondary">Ver casos clínicos</a>
</div>
HTML,
            ],
            Sections::CLINICAL_LIBRARY => [
                'title' => 'Biblioteca clínica Helin',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<div>
    <small>Biblioteca clínica Helin</small>
    <h2>Busca, filtra y consulta recursos especializados.</h2>
</div>
<p>Una experiencia organizada para acceder rápidamente a contenido clínico por especialidad, formato y tipo de recurso.</p>
HTML,
            ],
            Sections::CLINICAL_CONTENT_FEATURE => [
                'title' => 'Contenido clínico pensado para acompañar tu práctica.',
                'image' => null,
                'name_button' => 'Contactar asesor',
                'url_button' => 'web.contactanos',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Centraliza recursos técnicos, casos clínicos y materiales descargables en una plataforma clara, rápida y alineada al portafolio de Helin.</p>
<div class="feature-box">
    <strong>Asesoría especializada</strong>
    <p>Conecta cada recurso con productos, casos de uso y soporte comercial para profesionales.</p>
</div>
HTML,
            ],
            Sections::IMPLANTOLOGY_PRODUCTS => [
                'title' => 'Más vendidos en Implantología',
                'image' => null,
                'name_button' => 'Ver todos los productos →',
                'url_button' => 'catalogo',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Selección de productos destacados</p>
<div class="product-highlights">
    <div class="highlight-item">
        <div class="highlight-icon">🦷</div>
        <span>Implantes de<br>alta calidad</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">⚙️</div>
        <span>Instrumental<br>quirúrgico</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">💎</div>
        <span>Biomateriales<br>especializados</span>
    </div>
</div>
HTML,
            ],
            Sections::GBR_PRODUCTS => [
                'title' => 'Más vendidos en Regeneración Ósea Guiada',
                'image' => null,
                'name_button' => 'Ver todos los productos →',
                'url_button' => 'catalogo',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Biomateriales y soluciones especializadas</p>
<div class="product-highlights">
    <div class="highlight-item">
        <div class="highlight-icon">🦴</div>
        <span>Membranas<br>reabsorbibles</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">🧬</div>
        <span>Injertos<br>óseos</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">🔬</div>
        <span>Factores de<br>crecimiento</span>
    </div>
</div>
HTML,
            ],
            Sections::INSTRUMENTS_PRODUCTS => [
                'title' => 'Más vendidos en Instrumentos y Equipos',
                'image' => null,
                'name_button' => 'Ver todos los productos →',
                'url_button' => 'catalogo',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Precisión clínica para tu práctica</p>
<div class="product-highlights">
    <div class="highlight-item">
        <div class="highlight-icon">🔧</div>
        <span>Motrices y<br>contrángulos</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">⚕️</div>
        <span>Sistemas de<br>osteosíntesis</span>
    </div>
    <div class="highlight-item">
        <div class="highlight-icon">📡</div>
        <span>Equipos de<br>cirugía</span>
    </div>
</div>
HTML,
            ],
            Sections::TESTIMONIALS => [
                'title' => 'Testimonios',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Lo que dicen nuestros clientes</p>
<div class="testimonials-preview">
    <div class="testimonial-item">
        <div class="testimonial-icon">⭐</div>
        <div class="testimonial-content">
            <p>"Excelente atención y muy buen acompañamiento comercial. Encontramos los productos necesarios para implantología."</p>
            <div class="testimonial-author">Dra. María Fernanda López</div>
            <div class="testimonial-role">Odontóloga implantóloga</div>
        </div>
    </div>
    <div class="testimonial-item">
        <div class="testimonial-icon">⭐</div>
        <div class="testimonial-content">
            <p>"Helin nos ha brindado soluciones confiables y un portafolio muy completo. Destaco la rapidez en la atención."</p>
            <div class="testimonial-author">Dr. José Andrés Rivas</div>
            <div class="testimonial-role">Especialista en cirugía bucal</div>
        </div>
    </div>
    <div class="testimonial-item">
        <div class="testimonial-icon">⭐</div>
        <div class="testimonial-content">
            <p>"Muy buena experiencia de compra. La plataforma es fácil de usar y el equipo comercial responde con rapidez."</p>
            <div class="testimonial-author">Clínica Sonrisa Integral</div>
            <div class="testimonial-role">Centro odontológico</div>
        </div>
    </div>
</div>
HTML,
            ],
            Sections::FLOW_HOW_TO => [
                'title' => '¿Cómo solicitar productos Helin?',
                'subtitle' => null,
                'description' => null,
                'image' => null,
                'layout_type' => self::LAYOUT_FEATURE_BOX,
                'icon_style' => self::ICON_EMOJI,
                'items' => json_encode([
                    'steps' => [
                        [
                            'icon' => '⌕',
                            'title' => 'Busca tus productos',
                            'description' => 'Explora implantes, instrumentos y kits.',
                            'number' => '1'
                        ],
                        [
                            'icon' => '🛒',
                            'title' => 'Agrega al carrito',
                            'description' => 'Selecciona cantidades y arma tu solicitud.',
                            'number' => '2'
                        ],
                        [
                            'icon' => '☏',
                            'title' => 'Atención personalizada',
                            'description' => 'Un ejecutivo comercial te contactará para continuar.',
                            'number' => '3'
                        ]
                    ]
                ]),
                'content' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
            ],
            Sections::CTA_HOME => [
                'title' => '¿Listo para transformar tu práctica clínica?',
                'image' => 'CTA',
                'name_button' => '☏ Háblale con WhatsApp',
                'url_button' => 'https://wa.me/584241232025',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Somos tu aliado en cada paso hacia la excelencia de la salud bucal.</p>
<div class="cta-actions">
    <a href="https://wa.me/584241232025" target="_blank" class="btn-primary">☏ Háblale con WhatsApp</a>
    <a href="{{ route('contactanos') }}" class="btn-outline">✉ Permítenos por correo</a>
</div>
HTML,
            ],
            // ==========================================
            // ---------- SECCIONES DE TESTIMONIOS --------
            // ==========================================
            Sections::TESTIMONIALS => [
                'title' => 'Lo que dicen nuestros clientes',
                'subtitle' => 'Testimonios',
                'description' => null,
                'image' => null,
                'layout_type' => self::LAYOUT_TESTIMONIALS,
                'icon_style' => self::ICON_NONE,
                'items' => null,
                'buttons' => null,
                'content' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
            ],
            // ==========================================
            // ---------- SECCIONES DE NUESTRA EMPRESA --------
            // ==========================================
            Sections::COMPANY_HERO => [
                'title' => 'Comprometidos con la excelencia en cada solución',
                'subtitle' => 'Nuestra empresa',
                'description' => 'En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.',
                'image' => 'company-hero',
                'layout_type' => self::LAYOUT_HERO_BUTTONS,
                'icon_style' => self::ICON_NONE,
                'buttons' => json_encode([
                    [
                        'text' => 'Conoce nuestro portafolio →',
                        'url' => 'catalogo',
                        'style' => 'primary'
                    ],
                    [
                        'text' => '☏ Háblale con un asesor',
                        'url' => 'contactanos',
                        'style' => 'outline'
                    ]
                ]),
                'content' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
            ],
            // ==========================================
            // ---------- SECCIONES DE POLÍTICAS --------
            // ==========================================
            Sections::SHIPPING_POLICIES => [
                'title' => 'Políticas de envío y garantías',
                'image' => '🚚',
                'name_button' => 'Ver más',
                'url_button' => '#envio-garantias',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>
    En Helin, nos comprometemos a que recibas tus productos en óptimas condiciones y con la máxima seguridad. Conoce nuestras condiciones de envío, tiempos de entrega y garantías.
</p>

<div class="policy-points">
    <div class="point">
        <div class="point-icon">▱</div>
        <div>
            <h3>Envíos</h3>
            <p>Realizamos envíos a todo el territorio nacional. Los tiempos de entrega varían entre 2 y 7 días hábiles según tu ubicación.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">♡</div>
        <div>
            <h3>Garantía</h3>
            <p>Todos nuestros productos cuentan con garantía contra defectos de fabricación por un período de 30 días desde la fecha de compra.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">↻</div>
        <div>
            <h3>Devoluciones</h3>
            <p>Puedes solicitar una devolución dentro de los 7 días posteriores a la recepción del pedido, si el producto no ha sido usado y está en su empaque original.</p>
        </div>
    </div>
</div>
HTML,
            ],
            Sections::TERMS_CONDITIONS => [
                'title' => 'Términos y condiciones',
                'image' => '▤',
                'name_button' => 'Ver más',
                'url_button' => '#terminos-condiciones',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>
    Al acceder y utilizar nuestro sitio web y servicios, aceptas cumplir con los siguientes términos y condiciones. Te recomendamos leerlos cuidadosamente.
</p>

<div class="policy-points">
    <div class="point">
        <div class="point-icon">♙</div>
        <div>
            <h3>Uso del sitio</h3>
            <p>El contenido de este sitio es para fines informativos y de compra personal. Queda prohibido su uso comercial no autorizado.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">▭</div>
        <div>
            <h3>Pedidos y pagos</h3>
            <p>Los pedidos están sujetos a disponibilidad y confirmación de pago. Aceptamos pagos con tarjeta de crédito, débito y otros métodos seguros.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">▢</div>
        <div>
            <h3>Responsabilidades</h3>
            <p>Helin no se hace responsable por el uso indebido de los productos adquiridos ni por daños derivados de causas fuera de nuestro control.</p>
        </div>
    </div>
</div>
HTML,
            ],
            Sections::PRIVACY_POLICIES => [
                'title' => 'Políticas de privacidad',
                'image' => '♙',
                'name_button' => 'Ver más',
                'url_button' => '#privacidad',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>
    En Helin, tu privacidad es nuestra prioridad. Esta política explica cómo recopilamos, usamos y protegemos tu información personal de acuerdo con la normativa aplicable.
</p>

<div class="policy-points">
    <div class="point">
        <div class="point-icon">▤</div>
        <div>
            <h3>Información que recopilamos</h3>
            <p>Recopilamos datos personales como nombre, correo electrónico, dirección de envío y método de pago para procesar tus pedidos y mejorar tu experiencia.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">▢</div>
        <div>
            <h3>Uso de la información</h3>
            <p>Utilizamos tu información únicamente para gestionar tus pedidos, brindarte soporte y enviarte comunicaciones relevantes sobre nuestros productos y servicios.</p>
        </div>
    </div>

    <div class="point">
        <div class="point-icon">♡</div>
        <div>
            <h3>Protección de datos</h3>
            <p>Implementamos medidas técnicas y organizativas para proteger tu información personal. No compartimos tus datos con terceros sin tu consentimiento.</p>
        </div>
    </div>
</div>
HTML,
            ],

            // ==========================================
            // ---------- SECCIONES DE NOSOTROS ---------
            // ==========================================
            Sections::ABOUT_US => [
                'title' => 'Soluciones que impulsan mejores resultados clínicos',
                'image' => 'Quiénes somos',
                'name_button' => null,
                'url_button' => '#quienes-somos',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Somos más que una casa comercial: un aliado con visión quirúrgica, clínica y digital, trabajando junto a especialistas, con educación sin fronteras, ética, foco en respaldo y calidad real.</p>
<p>Seleccionamos e importamos lo mejor en odontología y trabajamos codo a codo con ustedes para que cada procedimiento sea un reflejo de la diferencia real: la sonrisa clínica.</p>
<div class="value-grid">
    <div class="value-item"><div class="value-icon">♡</div>Calidad<br>comprobada</div>
    <div class="value-item"><div class="value-icon">☊</div>Asesoría<br>especializada</div>
    <div class="value-item"><div class="value-icon">▱</div>Portafolio<br>completo</div>
    <div class="value-item"><div class="value-icon">✓</div>Respaldo y<br>confianza</div>
</div>
HTML,
            ],
            Sections::MISSION_VISION => [
                'title' => 'Misión y Visión',
                'image' => 'Misión y visión',
                'name_button' => null,
                'url_button' => '#mision-vision',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<div class="mission-vision">
    <article class="mv-card">
        <div class="mv-icon">◎</div>
        <div>
            <h3>Misión</h3>
            <p>Brindar soluciones odontológicas especializadas con excelencia, calidad e innovación, impulsando la transformación y progreso real a futuro.</p>
        </div>
    </article>
    <article class="mv-card">
        <div class="mv-icon">⚑</div>
        <div>
            <h3>Visión</h3>
            <p>Ser un referente en el sector odontológico especializado, reconocido por nuestro espíritu innovador, calidad y compromiso con el crecimiento de los profesionales de la salud bucal.</p>
        </div>
    </article>
</div>
HTML,
            ],
            Sections::TEAM => [
                'title' => 'Un equipo que te acompaña',
                'image' => 'Nuestro team',
                'name_button' => '☏ Conoce al equipo',
                'url_button' => '/contactanos',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Contamos con un equipo comprometido en ofrecer asesoría experta, alineada a calidad y novedad constante, porque en cada paso el camino prevalece.</p>
<div class="team-photo">
    <div class="people">
        <div class="person"></div><div class="person"></div><div class="person"></div><div class="person"></div>
        <div class="person"></div><div class="person"></div><div class="person"></div><div class="person"></div>
    </div>
</div>
HTML,
            ],
            Sections::ALLIES => [
                'title' => 'Trabajamos junto a marcas líderes',
                'image' => 'Nuestros aliados',
                'name_button' => null,
                'url_button' => '#nuestros-aliados',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Aliados estratégicos de reconocimiento mundial, que comparten los valores y los mismos de ética, y calidad clínica.</p>
<div>
    <div class="logos-grid">
        <div class="brand-card">GDT</div>
        <div class="brand-card">AB</div>
        <div class="brand-card">b:u</div>
        <div class="brand-card">NSK</div>
        <div class="brand-card">WOODPECKER</div>
        <div class="brand-card">Dentistry<br>Sironа</div>
        <div class="brand-card">Bio-Oss®</div>
        <div class="brand-card">Geistlich</div>
    </div>
    <div class="slider-dot"></div>
</div>
HTML,
            ],
            Sections::NEAR_YOU => [
                'title' => 'Estamos cerca de ti',
                'image' => null,
                'name_button' => null,
                'url_button' => '#',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<div class="location-icon">⌖</div>
<p>donde construyes salud oral</p>
<div class="cities-list">
    <div class="city-item">Caracas</div>
    <div class="city-item">Valencia</div>
    <div class="city-item">Barquisimeto</div>
    <div class="city-item">Maracaibo</div>
</div>
HTML,
            ],
            Sections::CTA_COMPANY => [
                'title' => '¿Listo para transformar tu práctica clínica?',
                'image' => 'CTA',
                'name_button' => 'Acciones',
                'url_button' => '#',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Somos tu aliado en cada paso hacia la excelencia de la salud bucal.</p>
HTML,
            ],

            // ==========================================
            // ---------- SECCIONES DE CONTACTO ----------
            // ==========================================
            Sections::CONTACT_HERO => [
                'title' => '¿Tienes alguna consulta? Estamos para ayudarte.',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>
    Estamos aquí para ayudarte. Escríbenos o utiliza el formulario y nuestro equipo se pondrá en contacto contigo lo antes posible.
</p>
HTML,
            ],
        ];

        foreach ($sections as $id => $data) {
            $data['id'] = $id;
            Sections::create($data);
        }
    }
}
