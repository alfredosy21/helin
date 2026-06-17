<?php
/**
 * Componente: Testimonial Card
 * Reutilizable en secciones de testimonios
 * 
 * Variables esperadas:
 * - $testimonialText (string): Texto del testimonio
 * - $testimonialAuthor (string): Nombre del autor
 * - $testimonialTitle (string): Título/cargo del autor
 * - $testimonialRating (string, opcional): Rating en estrellas (por defecto: ★★★★★)
 * 
 * Ejemplo de uso:
 * $testimonialText = 'Excelente atención y muy buen acompañamiento comercial.';
 * $testimonialAuthor = 'Dra. María Fernanda López';
 * $testimonialTitle = 'Odontóloga implantóloga';
 * include 'components/testimonial-card.php';
 */

// Valores por defecto
$testimonialText = isset($testimonialText) ? $testimonialText : '';
$testimonialAuthor = isset($testimonialAuthor) ? $testimonialAuthor : '';
$testimonialTitle = isset($testimonialTitle) ? $testimonialTitle : '';
$testimonialRating = isset($testimonialRating) ? $testimonialRating : '★★★★★';
?>

<article class="testimonial">
    <div class="stars"><?php echo htmlspecialchars($testimonialRating); ?></div>
    <p><?php echo htmlspecialchars($testimonialText); ?></p>
    <div class="person">
        <div class="avatar"></div>
        <div>
            <strong><?php echo htmlspecialchars($testimonialAuthor); ?></strong>
            <span><?php echo htmlspecialchars($testimonialTitle); ?></span>
        </div>
    </div>
    <div class="quote">"</div>
</article>
