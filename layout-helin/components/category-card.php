<?php
/**
 * Componente: Category Card
 * Reutilizable en secciones de categorías
 * 
 * Variables esperadas:
 * - $categorySubtitle (string): Subtítulo de la categoría
 * - $categoryTitle (string): Título de la categoría
 * - $categoryLink (string, opcional): URL de la categoría (por defecto: catalogo.php)
 * - $categoryIcon (string, opcional): Icono o clase adicional (por defecto: vacío)
 * 
 * Ejemplo de uso:
 * $categorySubtitle = 'Recuperación y soporte';
 * $categoryTitle = 'Regeneración ósea guiada';
 * $categoryLink = 'catalogo.php';
 * include 'components/category-card.php';
 */

// Valores por defecto
$categorySubtitle = isset($categorySubtitle) ? $categorySubtitle : '';
$categoryTitle = isset($categoryTitle) ? $categoryTitle : '';
$categoryLink = isset($categoryLink) ? $categoryLink : 'catalogo.php';
$categoryIcon = isset($categoryIcon) ? $categoryIcon : '';
?>

<article class="category-card min-h-[140px] border border-gray-200 rounded-2xl p-5 bg-gradient-to-br from-white to-turquesa/10 shadow-sm hover:shadow-md transition relative overflow-hidden <?php echo htmlspecialchars($categoryIcon); ?>">
   <small class="block text-turquesa text-xs font-black mb-2"><?php echo htmlspecialchars($categorySubtitle); ?></small>
   <h3 class="text-xl font-bold leading-none mb-4"><?php echo htmlspecialchars($categoryTitle); ?></h3>
   <a href="<?php echo htmlspecialchars($categoryLink); ?>" class="text-link text-turquesa font-black text-sm">Ver categoría →</a>
</article>
