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
                'image' => 'sections/banner111.png',
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
                'image' => 'resources/recursos_clinicos_banner_helin.png',
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'layout_type' => self::LAYOUT_FEATURE_BOX,
                'icon_style' => self::ICON_NONE,
                'items' => json_encode([
                    'items' => [
                        ['icon' => '<i class="fas fa-file-medical"></i>', 'title' => 'Casos clínicos', 'description' => 'Protocolos, materiales utilizados y resultados.', 'order' => 1],
                        ['icon' => '<i class="fas fa-play-circle"></i>', 'title' => 'Videos', 'description' => 'Contenido audiovisual para soporte técnico.', 'order' => 2],
                        ['icon' => '<i class="fas fa-file-pdf"></i>', 'title' => 'Manuales', 'description' => 'Documentos técnicos y descargables.', 'order' => 3],
                        ['icon' => '<i class="fas fa-cloud-arrow-down"></i>', 'title' => 'Fichas técnicas', 'description' => 'Información clave de productos y soluciones.', 'order' => 4],
                    ]
                ]),
                'content' => null,
                'name_button' => null,
                'url_button' => null,
            ],
            Sections::CLINICAL_STATS => [
                'title' => 'Estadísticas de recursos clínicos',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'category_slug' => null,
                'status' => 1,
                'status_content' => 1,
                'layout_type' => 'stats_grid',
                'icon_style' => 'lucide',
                'items' => json_encode([
                    [
                        'icon' => '<i class="fas fa-laptop-medical"></i>',
                        'label' => 'Recursos disponibles',
                        'value_key' => 'total_resources',
                    ],
                    [
                        'icon' => '<i class="fas fa-star"></i>',
                        'label' => 'Especialidades clínicas',
                        'value_key' => 'total_specialties',
                    ],
                    [
                        'icon' => '<i class="fas fa-download"></i>',
                        'label' => 'Descargables técnicos',
                        'value_key' => 'total_pdfs',
                    ],
                    [
                        'icon' => '<i class="fas fa-book-open"></i>',
                        'label' => 'Casos clínicos',
                        'value_key' => 'total_cases',
                    ],
                ]),
            ],
            Sections::CLINICAL_LIBRARY => [
                'title' => 'Biblioteca clínica Helin',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'subtitle' => 'Busca, filtra y consulta recursos especializados.',
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
                'image' => 'sections/banner_rc_clinic.png',
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
                'category_slug' => 'implantologia',
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
                'category_slug' => 'regeneracion-guiada-bucal-gbr',
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
                'category_slug' => 'instrumentos',
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
                'image' => 'sections/banner_empresa.png',
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
                'image' => 'sections/imagen_empresa.png',
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
                'image' => 'sections/banner_empresa.png',
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
            // ==========================================
            // ---------- NUESTROS VALORES --------------
            // ==========================================
            Sections::COMPANY_VALUES => [
                'title' => 'Nuestros Valores',
                'subtitle' => 'Valores',
                'description' => 'Creemos en construir relaciones de confianza con los especialistas, ofreciendo respaldo, conocimiento y soluciones pensadas para su práctica profesional.',
                'image' => null,
                'layout_type' => self::LAYOUT_VALUE_GRID,
                'icon_style' => self::ICON_CUSTOM,
                'items' => json_encode([
                    'value_grid' => [
                        [
                            'number' => '01',
                            'icon' => 'far fa-handshake',
                            'title' => 'Cercanía Profesional',
                            'text' => 'Atendemos al especialista con criterio técnico, pero también con una relación humana, clara y accesible.',
                            'row' => 1,
                            'order' => 1,
                        ],
                        [
                            'number' => '02',
                            'icon' => 'fas fa-shield-halved',
                            'title' => 'Responsabilidad Clínica',
                            'text' => 'Sabemos que cada decisión impacta un procedimiento real, por eso actuamos con seriedad y precisión.',
                            'row' => 1,
                            'order' => 2,
                        ],
                        [
                            'number' => '03',
                            'icon' => 'fas fa-bolt',
                            'title' => 'Servicio Ágil',
                            'text' => 'Buscamos responder con rapidez, claridad y soluciones concretas a las necesidades del profesional.',
                            'row' => 2,
                            'order' => 3,
                        ],
                        [
                            'number' => '04',
                            'icon' => 'fas fa-lightbulb',
                            'title' => 'Formación Continua',
                            'text' => 'Creemos en compartir conocimiento, apoyar la educación y fortalecer la práctica de los especialistas.',
                            'row' => 2,
                            'order' => 4,
                        ],
                        [
                            'number' => '05',
                            'icon' => 'fas fa-people-group',
                            'title' => 'Respaldo Integral',
                            'text' => 'Ofrecemos más que productos: ofrecemos orientación, soporte y soluciones conectadas entre sí.',
                            'row' => 2,
                            'order' => 5,
                        ],
                    ]
                ]),
                'content' => null,
                'name_button' => null,
                'url_button' => '#nuestros-valores',
                'status' => 1,
                'status_content' => 1,
            ],
            Sections::TEAM => [
                'title' => 'Un equipo que te acompaña',
                'image' => 'sections/team_helin_test.png',
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
                'subtitle' => 'Nuestros aliados',
                'description' => 'Aliados estratégicos de reconocimiento mundial, que comparten los valores y los mismos de ética, y calidad clínica.',
                'image' => 'sections/banner_footer_empresa.png',
                'layout_type' => self::LAYOUT_BRAND_GRID,
                'icon_style' => self::ICON_NONE,
                'items' => json_encode([
                    'items' => [
                        ['image' => 'sections/gdt_logo.jpg', 'title' => 'GDT', 'order' => 1],
                        ['image' => 'sections/ab_logo.jpg', 'title' => 'AB', 'order' => 2],
                        ['image' => 'sections/bluem_logo.jpg', 'title' => 'Bluem', 'order' => 3],
                        ['image' => 'sections/logo_czmedietch.jpg', 'title' => 'CZ Medietch', 'order' => 4],
                        ['image' => 'sections/tealth_logo.jpg', 'title' => 'Tealth', 'order' => 5],
                        ['image' => 'sections/tissum_logo.jpg', 'title' => 'Tissum', 'order' => 6],
                    ]
                ]),
                'name_button' => null,
                'url_button' => '#nuestros-aliados',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>Aliados estratégicos de reconocimiento mundial, que comparten los valores y los mismos de ética, y calidad clínica.</p>
HTML,
            ],
            Sections::NEAR_YOU => [
                'title' => 'Estamos cerca de ti, donde construyes salud oral',
                'image' => null,
                'name_button' => null,
                'url_button' => '#',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<div class="location-icon">⌖</div>
<p>Caracas · Valencia · Barquisimeto · Maracaibo</p>
HTML,
            ],
            Sections::CTA_COMPANY => [
                'title' => '¿Listo para transformar tu práctica clínica?',
                'image' => 'sections/banner_footer_empresa.png',
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

            // GLOBAL SECTIONS
            Sections::BENEFITS => [
                'title' => 'Barra de beneficios',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => null,
                'items' => json_encode([
                    'items' => [
                        ['icon' => 'fas fa-truck', 'title' => 'Envíos rápidos y seguros', 'description' => 'A toda Venezuela', 'order' => 1],
                        ['icon' => 'fas fa-shield-alt', 'title' => 'Garantía Helin', 'description' => 'Calidad y respaldo en cada producto', 'order' => 2],
                        ['icon' => 'fas fa-headset', 'title' => 'Asesoría especializada', 'description' => 'Soporte técnico y comercial', 'order' => 3],
                        ['icon' => 'fas fa-box', 'title' => 'Stock disponible', 'description' => 'Productos de las mejores marcas', 'order' => 4],
                        ['icon' => 'fa-regular fa-square-check', 'title' => 'Productos certificados', 'description' => 'Cumplimos con los más altos estándares', 'order' => 5],
                    ]
                ]),
                'buttons' => json_encode([]),
            ],

            // ==========================================
            // ---------- SECCIONES DE CASO CLÍNICO ------
            // ==========================================
            Sections::CASE_SHARE => [
                'title' => 'Compartir este recurso',
                'image' => null,
                'name_button' => null,
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => null,
            ],
            Sections::CASE_ADVISOR => [
                'title' => '¿Necesitas asesoría personalizada?',
                'subtitle' => null,
                'description' => 'Un asesor Helin puede ayudarte a resolver dudas sobre este caso y los materiales utilizados.',
                'image' => null,
                'name_button' => 'Hablar por WhatsApp',
                'url_button' => null,
                'status' => 1,
                'status_content' => 1,
                'content' => null,
            ],
            Sections::CASE_BOTTOM_CTA => [
                'title' => '¿Tienes un caso similar o necesitas orientación?',
                'subtitle' => null,
                'description' => 'Nuestro equipo de especialistas está disponible para brindarte asesoría personalizada y acompañarte en la planificación de tus procedimientos.',
                'image' => null,
                'name_button' => 'Solicitar asesoría especializada',
                'url_button' => 'contactanos',
                'status' => 1,
                'status_content' => 1,
                'content' => null,
            ],

        ];

        foreach ($sections as $id => $data) {
            $data['id'] = $id;
            Sections::create($data);
        }
    }
}
