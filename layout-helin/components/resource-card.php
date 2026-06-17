<?php
/**
 * Componente: Resource Card
 * Reutilizable en secciones de recursos clínicos
 * 
 * Variables esperadas:
 * - $resourceType (string): Tipo de recurso (Caso clínico, Video, Manual, Ficha técnica, Guía)
 * - $resourcePlay (string): Icono de play/descarga (→, ↓, ▶, etc.)
 * - $resourceTags (array): Array de etiquetas
 * - $resourceTitle (string): Título del recurso
 * - $resourceDescription (string): Descripción del recurso
 * - $resourceFormat (string): Formato (▣ Artículo, ▤ PDF, ▶ Video, etc.)
 * - $resourceLink (string): Texto del enlace (Ver caso, Descargar, Leer guía, etc.)
 * - $resourceUrl (string, opcional): URL del enlace (por defecto: #)
 * 
 * Ejemplo de uso:
 * $resourceType = 'Caso clínico';
 * $resourcePlay = '→';
 * $resourceTags = ['Cirugía Bucal', 'GBR'];
 * $resourceTitle = 'Regeneración ósea guiada';
 * $resourceDescription = 'Protocolo clínico con biomateriales.';
 * $resourceFormat = '▣ Artículo';
 * $resourceLink = 'Ver caso';
 * include 'components/resource-card.php';
 */

// Valores por defecto
$resourceType = isset($resourceType) ? $resourceType : '';
$resourcePlay = isset($resourcePlay) ? $resourcePlay : '→';
$resourceTags = isset($resourceTags) ? $resourceTags : [];
$resourceTitle = isset($resourceTitle) ? $resourceTitle : '';
$resourceDescription = isset($resourceDescription) ? $resourceDescription : '';
$resourceFormat = isset($resourceFormat) ? $resourceFormat : '▣ Artículo';
$resourceLink = isset($resourceLink) ? $resourceLink : 'Ver más';
$resourceUrl = isset($resourceUrl) ? $resourceUrl : '#';
?>

<article class="resource-card">
    <div class="resource-thumb">
        <span class="resource-type"><?php echo htmlspecialchars($resourceType); ?></span>
        <span class="resource-play"><?php echo htmlspecialchars($resourcePlay); ?></span>
    </div>
    <div class="resource-body">
        <div class="resource-tags">
            <?php foreach ($resourceTags as $tag): ?>
                <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
            <?php endforeach; ?>
        </div>
        <h3><?php echo htmlspecialchars($resourceTitle); ?></h3>
        <p><?php echo htmlspecialchars($resourceDescription); ?></p>
        <div class="resource-footer">
            <span class="resource-format"><?php echo htmlspecialchars($resourceFormat); ?></span>
            <a href="<?php echo htmlspecialchars($resourceUrl); ?>" class="resource-link"><?php echo htmlspecialchars($resourceLink); ?></a>
        </div>
    </div>
</article>
