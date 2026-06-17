<?php
/**
 * Componente: Product Card
 * Reutilizable en catálogo, productos relacionados, destacados
 * 
 * Variables esperadas:
 * - $productImage (string): URL de la imagen
 * - $productName (string): Nombre del producto
 * - $productBrand (string): Marca del producto
 * - $productPrice (string/float): Precio
 * - $productOldPrice (string/float, opcional): Precio anterior para ofertas
 * - $productBadge (string, opcional): Etiqueta (Nuevo, Oferta, etc.)
 * - $productBadgeColor (string, opcional): Color de la badge (siempre turquesa)
 * - $productSku (string, opcional): SKU del producto
 * - $productLink (string): URL del producto
 * 
 * Ejemplo de uso:
 * $productImage = 'img/producto.jpg';
 * $productName = 'Implante Dental';
 * $productBrand = 'Straumann';
 * $productPrice = 299.00;
 * $productBadge = 'Nuevo';
 * $productBadgeColor = 'turquesa'; // Siempre turquesa
 * include 'components/product-card.php';
 */

// Valores por defecto
$productBadge = isset($productBadge) ? $productBadge : '';
$productBadgeColor = isset($productBadgeColor) ? $productBadgeColor : 'turquesa';
$productOldPrice = isset($productOldPrice) ? $productOldPrice : '';
$productSku = isset($productSku) ? $productSku : '';
$productLink = isset($productLink) ? $productLink : 'producto.php';

// Formatear precio
$formattedPrice = is_numeric($productPrice) ? '$' . number_format($productPrice, 2) : $productPrice;
$formattedOldPrice = is_numeric($productOldPrice) ? '$' . number_format($productOldPrice, 2) : $productOldPrice;

// Todas las badges usan color turquesa uniformemente
$badgeClass = 'bg-turquesa';
?>

<div class="bg-white rounded-xl p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-transform flex flex-col h-full cursor-pointer">
    <!-- Imagen -->
    <div class="relative flex-1 flex items-center justify-center min-h-[200px] mb-4">
        <?php if ($productBadge): ?>
            <span class="absolute top-2.5 left-2.5 <?php echo $badgeClass; ?> text-white text-xs font-semibold uppercase px-3 py-1 rounded-full z-10">
                <?php echo htmlspecialchars($productBadge); ?>
            </span>
        <?php endif; ?>
        <img src="<?php echo htmlspecialchars($productImage); ?>" 
             alt="<?php echo htmlspecialchars($productName); ?>" 
             class="max-h-[180px] object-contain">
    </div>
    
    <!-- Contenido -->
    <h3 class="font-bold text-[#1a202c] text-base mb-1 text-center">
        <?php echo htmlspecialchars($productName); ?>
    </h3>
    
    <p class="text-[#718096] text-sm font-normal mb-3 text-center"><?php echo htmlspecialchars($productBrand); ?></p>
    
    <!-- Precio -->
    <div class="flex items-center justify-center gap-2 mb-4 mt-auto">
        <?php if ($productOldPrice): ?>
            <span class="text-[#a0aec0] text-sm line-through"><?php echo $formattedOldPrice; ?></span>
        <?php endif; ?>
        <span class="text-[#15aabf] font-bold text-lg"><?php echo $formattedPrice; ?></span>
    </div>
    
    <!-- Botón -->
    <button class="w-full bg-[#15aabf] hover:bg-[#0e8c9c] text-white font-semibold uppercase py-3 rounded-[30px] transition-colors">
        Añadir al carrito +
    </button>
</div>
