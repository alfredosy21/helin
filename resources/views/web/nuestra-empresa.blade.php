@extends('web.layouts.app')

@section('title', 'Nuestra Empresa - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/nuestra-empresa.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav>
        <a href="{{ route('web.home') }}">Inicio</a>
        <span>></span>
        <span>Nuestra Empresa</span>
    </nav>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-copy">
            <span class="hero-badge">Nuestra empresa</span>
            <h1>Comprometidos con la excelencia en cada solución</h1>
            <p>
                En Helin, nos apasiona hacer excelencia, integridad y experiencia para acompañar a profesionales y laboratorios en cada tratamiento y cada sonrisa.
            </p>
            <div class="hero-actions">
                <a href="{{ route('web.catalogo') }}" class="btn-primary">Conoce nuestro portafolio →</a>
                <a href="{{ route('web.contactanos') }}" class="btn-outline">☏ Háblale con un asesor</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-card about" id="quienes-somos">
        <div>
            <span class="section-label">Quiénes somos</span>
            <h2>Soluciones que impulsan mejores resultados clínicos</h2>
            <p>
                Somos más que una casa comercial: un aliado con visión quirúrgica, clínica y digital, trabajando junto a especialistas, con educación sin fronteras, ética, foco en respaldo y calidad real.
            </p>
            <p>
                Seleccionamos e importamos lo mejor en odontología y trabajamos codo a codo con ustedes para que cada procedimiento sea un reflejo de la diferencia real: la sonrisa clínica.
            </p>

            <div class="value-grid">
                <div class="value-item">
                    <div class="value-icon">♡</div>
                    Calidad<br>comprobada
                </div>
                <div class="value-item">
                    <div class="value-icon">☊</div>
                    Asesoría<br>especializada
                </div>
                <div class="value-item">
                    <div class="value-icon">▱</div>
                    Portafolio<br>completo
                </div>
                <div class="value-item">
                    <div class="value-icon">✓</div>
                    Respaldo y<br>confianza
                </div>
            </div>
        </div>

        <div class="about-visual">
            <div class="implants-row">
                <div class="implant"></div>
                <div class="implant"></div>
                <div class="implant"></div>
                <div class="implant"></div>
            </div>
            <div class="kit-base"></div>
        </div>
    </section>

    <!-- Mission and Vision -->
    <section id="mision-vision">
        <span class="section-label">Misión y visión</span>
        <div class="mission-vision">
            <article class="mv-card">
                <div class="mv-icon">◎</div>
                <div>
                    <h3>Misión</h3>
                    <p>
                        Brindar soluciones odontológicas especializadas con excelencia, calidad e innovación, impulsando la transformación y progreso real a futuro.
                    </p>
                </div>
            </article>

            <article class="mv-card">
                <div class="mv-icon">⚑</div>
                <div>
                    <h3>Visión</h3>
                    <p>
                        Ser un referente en el sector odontológico especializado, reconocido por nuestro espíritu innovador, calidad y compromiso con el crecimiento de los profesionales de la salud bucal.
                    </p>
                </div>
            </article>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-card team" id="nuestro-team">
        <div>
            <span class="section-label">Nuestro team</span>
            <h2>Un equipo que te acompaña</h2>
            <p>
                Contamos con un equipo comprometido en ofrecer asesoría experta, alineada a calidad y novedad constante, porque en cada paso el camino prevalece.
            </p>
            <a href="{{ route('web.contactanos') }}" class="btn-outline">☏ Conoce al equipo</a>
        </div>

        <div class="team-photo">
            <div class="people">
                @for($i = 0; $i < 8; $i++)
                <div class="person"></div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Allies Section -->
    <section class="section-card allies" id="nuestros-aliados">
        <div>
            <span class="section-label">Nuestros aliados</span>
            <h2>Trabajamos junto a marcas líderes</h2>
            <p>
                Aliados estratégicos de reconocimiento mundial, que comparten los valores y los mismos de ética, y calidad clínica.
            </p>
        </div>

        <div>
            <div class="logos-grid">
                @foreach(['GDT', 'AB', 'b:u', 'NSK', 'WOODPECKER', 'Dentistry<br>Sironа', 'Bio-Oss®', 'Geistlich'] as $brand)
                <div class="brand-card">{!! $brand !!}</div>
                @endforeach
            </div>
            <div class="slider-dot"></div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div>
            <h2>¿Listo para transformar tu práctica clínica?</h2>
            <p>Somos tu aliado en cada paso hacia la excelencia de la salud bucal.</p>
        </div>
        <div class="cta-actions">
            <a href="https://wa.me/584241232025" target="_blank" class="btn-primary">☏ Háblale con WhatsApp</a>
            <a href="{{ route('web.contactanos') }}" class="btn-outline">✉ Permítenos por correo</a>
        </div>
    </section>
</main>
@endsection
