@extends('web.layouts.app')

@section('title', 'Contáctanos - Helin')

@section('styles')
<link rel="stylesheet" href="{{ asset('helin/css/contactanos.css') }}">
@endsection

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav>
        <a href="{{ route('web.home') }}">Inicio</a>
        <span>></span>
        <span>Contacto</span>
    </nav>

    <h1 class="page-title">Contáctanos</h1>

    <section class="contact-layout">
        <aside class="info-block">
            <h2>¿Tienes preguntas?<br>Hablemos.</h2>
            <p>
                Estamos aquí para ayudarte. Escríbenos o utiliza el formulario y nuestro equipo se pondrá en contacto contigo lo antes posible.
            </p>

            <div class="info-list">
                <article class="info-item">
                    <div class="info-icon">⌖</div>
                    <div>
                        <h3>Dirección</h3>
                        <p>Centro Comercial El Recreo,<br>Torre Norte, Piso 5, Oficina 5B,<br>Sabana Grande, Caracas 1050, Venezuela.</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon">☏</div>
                    <div>
                        <h3>Teléfono central</h3>
                        <p>+58 4242789481</p>
                    </div>
                </article>

                <article class="info-item">
                    <div class="info-icon">✉</div>
                    <div>
                        <h3>Correo electrónico</h3>
                        <p>info@helinlatam.com</p>
                    </div>
                </article>
            </div>

            <div class="sedes">
                <h3>Nuestras sedes</h3>
                <div class="sede-pills">
                    @foreach(['Caracas', 'Valencia', 'Barquisimeto', 'Maracaibo'] as $sede)
                    <span class="sede-pill"><span>⌖</span>{{ $sede }}</span>
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="form-card">
            <form class="form-grid">
                <div>
                    <label>Nombre <span>*</span></label>
                    <input type="text" name="nombre" placeholder="Ingresa tu nombre" required>
                </div>

                <div>
                    <label>Correo electrónico <span>*</span></label>
                    <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="Ingresa tu número de teléfono">
                </div>

                <div>
                    <label>Asunto <span>*</span></label>
                    <select name="asunto" required>
                        <option value="">Selecciona un asunto</option>
                        <option value="informacion-comercial">Información comercial</option>
                        <option value="asesoria-productos">Asesoría de productos</option>
                        <option value="cotizacion">Cotización</option>
                        <option value="disponibilidad">Disponibilidad de productos</option>
                        <option value="soporte-orden">Soporte de orden</option>
                        <option value="recursos-clinicos">Recursos clínicos</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="field-full">
                    <label>¿Cómo podemos ayudarte? <span>*</span></label>
                    <textarea name="mensaje" placeholder="Cuéntanos más sobre tu consulta..." required></textarea>
                </div>

                <div class="field-full captcha">
                    <div class="captcha-left">
                        <input type="checkbox" id="robot-check" required>
                        <label for="robot-check">No soy un robot</label>
                    </div>
                    <div class="captcha-right">
                        <div class="captcha-logo"></div>
                        <div>reCAPTCHA<br>Privacidad · Términos</div>
                    </div>
                </div>

                <button class="submit" type="submit">➤ Contactar a Helin</button>

                <div class="secure">▵ Tu información está segura con nosotros.</div>
            </form>
        </section>
    </section>
</main>
@endsection
