@extends('web.layouts.app')

@section('title', 'Políticas - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/politicas.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav>
        <a href="{{ route('web.home') }}">Inicio</a>
        <span>></span>
        <span>Políticas</span>
    </nav>

    <h1 class="page-title">Políticas</h1>

    <section class="policies">
        <article class="policy-card" id="envio-garantias">
            <div class="policy-icon-wrap">
                <div class="policy-icon">🚚</div>
            </div>
            <div class="policy-number">1.</div>
            <div class="policy-content">
                <h2>Políticas de envío y garantías</h2>
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
            </div>
            <div class="policy-action">
                <button class="more-btn">Ver más ›</button>
            </div>
        </article>

        <article class="policy-card" id="terminos-condiciones">
            <div class="policy-icon-wrap">
                <div class="policy-icon">▤</div>
            </div>
            <div class="policy-number">2.</div>
            <div class="policy-content">
                <h2>Términos y condiciones</h2>
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
            </div>
            <div class="policy-action">
                <button class="more-btn">Ver más ›</button>
            </div>
        </article>

        <article class="policy-card" id="privacidad">
            <div class="policy-icon-wrap">
                <div class="policy-icon">♙</div>
            </div>
            <div class="policy-number">3.</div>
            <div class="policy-content">
                <h2>Políticas de privacidad</h2>
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
            </div>
            <div class="policy-action">
                <button class="more-btn">Ver más ›</button>
            </div>
        </article>
    </section>
</main>
@endsection
