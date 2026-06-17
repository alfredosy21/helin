<?php
/**
 * Página de Solicitud Comercial - Helin (Checkout/Pedido)
 */
$pageTitle = 'Detalle de la Solicitud - Helin';
include 'includes/head.php';
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/mobile-nav.php'; ?>

<main class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="index.php" class="text-gray-500 hover:text-turquesa">Inicio</a>
        <span class="text-gray-400 mx-2">></span>
        <a href="carrito.php" class="text-gray-500 hover:text-turquesa">Carrito</a>
        <span class="text-gray-400 mx-2">></span>
        <span class="text-turquesa font-medium">Detalle de la Solicitud</span>
    </nav>

    <form action="#" method="POST" class="flex flex-col lg:flex-row gap-8">
        <!-- Columna Izquierda (70%) - Datos del Cliente -->
        <div class="lg:w-[70%] space-y-8">
            
            <!-- Bloque: Detalle de la solicitud -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Detalle de la solicitud</h2>
                
                <!-- Tipo de cliente -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de cliente *</label>
                    <select required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                        <option value="">Selecciona una opción</option>
                        <option value="doctor">Doctor</option>
                        <option value="paciente">Paciente</option>
                        <option value="empresa">Empresa</option>
                    </select>
                </div>

                <!-- Nombre y Apellido -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Tu nombre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                        <input type="text" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Tu apellido">
                    </div>
                </div>

                <!-- Empresa (opcional) -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Empresa (opcional)</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="Nombre de la empresa">
                </div>

                <!-- Estado y Ciudad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                        <select required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                            <option value="">Selecciona estado</option>
                            <option value="distrito-capital">Distrito Capital</option>
                            <option value="miranda">Miranda</option>
                            <option value="carabobo">Carabobo</option>
                            <option value="aragua">Aragua</option>
                            <option value="lara">Lara</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                        <select required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors bg-white">
                            <option value="">Selecciona ciudad</option>
                            <option value="caracas">Caracas</option>
                            <option value="valencia">Valencia</option>
                            <option value="barquisimeto">Barquisimeto</option>
                            <option value="maracay">Maracay</option>
                        </select>
                    </div>
                </div>

                <!-- Dirección completa -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección completa *</label>
                    <textarea required rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors resize-none" placeholder="Calle, número, urbanización, edificio, piso, apartamento..."></textarea>
                </div>

                <!-- Teléfono y Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="tel" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="+58 412 123 4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                        <input type="email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors" placeholder="correo@ejemplo.com">
                    </div>
                </div>
            </div>

            <!-- Bloque: Información adicional -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Información adicional</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors resize-none" placeholder="Indica aquí cualquier detalle adicional sobre tu pedido, horario de entrega preferido, instrucciones especiales..."></textarea>
                </div>
            </div>
        </div>

        <!-- Columna Derecha (30%) - Widget de Orden -->
        <div class="lg:w-[30%]">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Orden</h2>

                <!-- Resumen de productos -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Implante Straumann BLX x 1</span>
                        <span class="font-medium">$299.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biomaterial Bio-Oss x 2</span>
                        <span class="font-medium">$298.00</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Subtotal</span>
                            <span>$597.00</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Descuento</span>
                            <span class="text-green-500">-$45.00</span>
                        </div>
                        <div class="flex justify-between text-base font-bold">
                            <span class="text-gray-800">Total</span>
                            <span class="text-turquesa">$552.00</span>
                        </div>
                    </div>
                </div>

                <!-- reCAPTCHA -->
                <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="recaptcha" class="w-5 h-5 text-turquesa rounded">
                        <label for="recaptcha" class="text-sm text-gray-700">No soy un robot</label>
                    </div>
                </div>

                <!-- Métodos de Entrega -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 text-sm">Métodos de Entrega</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="mrw" class="w-4 h-4 text-turquesa" checked>
                            <span class="text-sm text-gray-600">MRW (Cobro destino)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="liberty" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Liberty (Cobro destino)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="tealca" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Tealca</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="zoom" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Zoom</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="envio" value="pickup" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Pick Up (Recoger en tienda)</span>
                        </label>
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 text-sm">Métodos de Pago</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" value="acordar" class="w-4 h-4 text-turquesa" checked>
                            <span class="text-sm text-gray-600">Acordar con Helin</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" value="pago-movil" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Pago Móvil</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" value="zelle" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Zelle</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" value="binance" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Binance Pay</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pago" value="multiple" class="w-4 h-4 text-turquesa">
                            <span class="text-sm text-gray-600">Pagos Múltiples</span>
                        </label>
                    </div>
                </div>

                <!-- Nro de comprobante -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nro. de comprobante de pago</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-turquesa focus:ring-1 focus:ring-turquesa outline-none transition-colors text-sm" placeholder="Referencia de transferencia">
                </div>

                <!-- Botón Enviar -->
                <button type="submit" class="w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold py-4 rounded-full uppercase transition-colors">
                    Enviar Solicitud Comercial
                </button>
            </div>
        </div>
    </form>
</main>

<?php include 'includes/beneficios.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
