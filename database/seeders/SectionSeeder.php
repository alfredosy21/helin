<?php

namespace Database\Seeders;

use App\Models\Sections;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
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
            [
                'title' => 'Comprometidos con la excelencia en cada solución',
                'image' => 'hero-home.jpg',
                'name_button' => 'Conoce nuestro portafolio →',
                'url_button' => 'web.catalogo',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.</p>
<div class="hero-badges">
    <div class="badge">
        <div class="mini-icon">✚</div>
        <span>Soluciones<br>quirúrgicas</span>
    </div>
    <div class="badge">
        <div class="mini-icon">⌖</div>
        <span>Asesoría<br>especializada</span>
    </div>
</div>
HTML,
            ],

            // ==========================================
            // ---------- SECCIONES DE POLÍTICAS --------
            // ==========================================
            [
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
            [
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
            [
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
            [
                'title' => 'Soluciones que impulsan mejores resultados clínicos',
                'image' => 'Quiénes somos',
                'name_button' => null,
                'url_button' => '#quienes-somos',
                'status' => 1,
                'status_content' => 0,
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
            [
                'title' => 'Misión y Visión',
                'image' => 'Misión y visión',
                'name_button' => null,
                'url_button' => '#mision-vision',
                'status' => 1,
                'status_content' => 0,
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
            [
                'title' => 'Un equipo que te acompaña',
                'image' => 'Nuestro team',
                'name_button' => '☏ Conoce al equipo',
                'url_button' => 'web.contactanos',
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
            [
                'title' => 'Trabajamos junto a marcas líderes',
                'image' => 'Nuestros aliados',
                'name_button' => null,
                'url_button' => '#nuestros-aliados',
                'status' => 1,
                'status_content' => 0,
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
            [
                'title' => 'Estamos cerca de ti',
                'image' => null,
                'name_button' => null,
                'url_button' => '#',
                'status' => 1,
                'status_content' => 1,
                'content' => <<<HTML
<p>donde construyes salud oral</p>
<div class="cities-list">
    <div class="city-item">Caracas</div>
    <div class="city-item">Valencia</div>
    <div class="city-item">Barquisimeto</div>
    <div class="city-item">Maracaibo</div>
</div>
HTML,
            ],
            [
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
        ];

        foreach ($sections as $data) {
            Sections::create($data);
        }
    }
}
