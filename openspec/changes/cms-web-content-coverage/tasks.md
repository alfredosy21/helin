## 1. Fase 1 — Bugs críticos (sin migraciones)

- [ ] 1.1 Eliminar el `dd()` en `SectionController::update()` y restaurar la lógica de guardado real
- [ ] 1.2 Añadir a `Sections::$fillable`: `subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`. Eliminar `status` duplicado del array.
- [ ] 1.3 Añadir a `Product::$fillable`: `material`, `is_biomaterial`, `seo_description`, `seo_keywords`, `system_product_id`, `product_platform_id`
- [ ] 1.4 Corregir `Testimonial::$fillable` a `['name', 'specialty', 'content', 'image', 'is_active', 'position']` (quitar `description`/`charge`/`order` inexistentes)
- [ ] 1.5 Añadir `image` a `ResourceSpecialty::$fillable` (columna ya existe por migración `2026_07_24_120002`)
- [ ] 1.6 Quitar `specialty` y `tags` de `Resource::$fillable` (no existen como columnas). Añadir `slug` (existe en BD y se usa en rutas/vistas)
- [ ] 1.7 Corregir `ResourceController` en todos los métodos que usan `specialty`/`tags` inexistentes: `filterSpecialty` (filtrar por `resource_specialty_id`), `render()` búsqueda (línea 82), `edit()` (líneas 118-120), `save()` (líneas 154-156), `$rules` (líneas 54-56), `resetForm()` (línea 217)
- [ ] 1.8 Añadir auto-generación de `slug` en `ResourceController::save()` con `Str::slug($this->title)` y hook `updatedTitle()`
- [ ] 1.9 Corregir `caso-clinico.blade.php`: `$resource->specialty->name` → `$resource->resourceSpecialty->name`, `$resource->type->name` → `$resource->resourceType->name`
- [ ] 1.10 Añadir verificación de permisos en `mount()` de `CommercialRequestsController`, `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController`
- [ ] 1.11 Cambiar `paginationTheme` de `'bootstrap'` a `'tailwind'` en `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController`
- [ ] 1.12 Quitar o implementar correctamente `ResourceTypeController::updatedName()` (genera `$this->slug` inexistente)
- [ ] 1.13 Eliminar el multiplicador ×4 de recursos en `WebController::recursosClinicos()` (líneas 358-359) y paginar recursos reales directamente
- [ ] 1.14 Reemplazar el pool de imágenes hardcoded `['im1.png'...'im6.png']` en `WebController::searchProducts()` por las imágenes reales del producto
- [ ] 1.15 Añadir `with(['attributeValues'])` al eager loading en `WebController::producto()`
- [ ] 1.16 Verificar Fase 1: editar una sección, un producto, un testimonio y un recurso desde el CMS y confirmar que se guardan. Verificar que la búsqueda de productos usa imágenes reales y que la página de recursos clínicos no muestra recursos duplicados

## 2. Fase 2A — Infraestructura existente sin usar (cambios simples, sin migraciones nuevas)

- [ ] 2.1 Conectar footer y header (`partials/footer.blade.php`, `partials/header.blade.php`) para consumir `Settings::facebook`, `instagram`, `linkedin`, `youtube`
- [ ] 2.2 Generar el mega menú del header dinámicamente desde `Menus`/`Category` en lugar de la estructura hardcodeada
- [ ] 2.3 Crear `WhatsAppNumbersController` (Livewire) para gestionar `whatsapp_numbers` (CRUD + activar/desactivar)
- [ ] 2.4 Crear vista CMS `cms/whatsapp-numbers/index.blade.php`
- [ ] 2.5 Registrar submódulo WhatsApp Numbers en `Submodule` y ruta en `routes/web.php` bajo M2
- [ ] 2.6 Reemplazar el array `$pickupInfo` hardcodeado en `WebController::solicitud()` por consulta a `WhatsAppNumber` + `State`
- [ ] 2.7 Crear `AttributesController` (Livewire) para gestionar `attributes` (CRUD + activar/desactivar)
- [ ] 2.8 Crear `AttributeValuesController` (Livewire) para gestionar `attribute_values` (CRUD + reordenar + activar/desactivar)
- [ ] 2.9 Crear vistas CMS `cms/attributes/index.blade.php` y `cms/attribute-values/index.blade.php`
- [ ] 2.10 Registrar submódulos Attributes y Attribute Values en `Submodule` y rutas en `routes/web.php` bajo M3
- [ ] 2.11 Añadir asociación de `attribute_values` al producto en el editor de productos existente
- [ ] 2.12 Reemplazar el pool estático `im1.png`–`im6.png` por `Product::mainImageUrl`/`images()` en `producto.blade.php`, `product-results.blade.php` y home
- [ ] 2.13 Reemplazar el gallery hardcodeado de `producto.blade.php` por `$product->images`
- [ ] 2.14 Reemplazar los productos relacionados hardcodeados en `producto.blade.php` por `$relatedProducts`
- [ ] 2.15 Reemplazar imágenes hardcodeadas de testimonios en `home.blade.php` por `$testimonial->image` con fallback. Eliminar la duplicación manual de un testimonio (líneas 381-389) para llenar el carrusel
- [ ] 2.16 Reemplazar el WhatsApp hardcodeado del sidebar de `caso-clinico.blade.php` por `WhatsAppNumber`/`Settings`
- [ ] 2.17 Reemplazar la imagen hero hardcodeada de `caso-clinico.blade.php` por `$resource->thumbnail`
- [ ] 2.18 Reemplazar el bloque "Categoría Destacada" hardcoded en `home.blade.php` (líneas 93-100, `categoria1.png`) por `Category::where('is_featured', true)->first()` o sección CMS
- [ ] 2.19 Reemplazar la sección "Destacados en Instrumentos y Equipos" en `home.blade.php` (líneas 277-330, 4 productos fake) por productos reales o eliminar si `$productSections` ya la cubre
- [ ] 2.20 Reemplazar `team_helin_test.png` hardcoded en `nuestra-empresa.blade.php` (línea 132) por `$teamSection->image` con fallback
- [ ] 2.21 Reemplazar WhatsApp `584244669150` hardcoded en `nuestra-empresa.blade.php` CTA (línea 160) por `WhatsAppNumber`/`Settings`
- [ ] 2.22 Verificar Fase 2A: gestionar WhatsApp y atributos desde el CMS; confirmar que la web consume imágenes reales de productos y no muestra contenido fake

## 3. Fase 2B — Editor repeater de items/buttons JSON (complejo, aislado)

- [ ] 3.1 Ampliar `SectionController` para gestionar campos estructurados (`subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`)
- [ ] 3.2 Implementar componente repeater en Livewire para editar `items` JSON (añadir/editar/reordenar/eliminar items con campos icon, title, description, order, url según layout_type)
- [ ] 3.3 Implementar componente repeater en Livewire para editar `buttons` JSON (añadir/editar/eliminar botones con texto y URL)
- [ ] 3.4 Crear/actualizar vista CMS `cms/sections/index.blade.php` con el editor repeater
- [ ] 3.5 Validar estructura JSON antes de guardar (esquema por `layout_type`)
- [ ] 3.6 Verificar Fase 2B: editar una sección con items y botones desde el CMS; confirmar que se guarda y la web lo muestra

## 4. Fase 3A — Migraciones de campos nuevos

- [ ] 4.1 Crear migración para `categories`: añadir `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.2 Crear migración para `brands`: añadir `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.3 Crear migración para `lines`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.4 Crear migración para `system_products`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.5 Crear migración para `product_platforms`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.6 Crear migración para `resource_types`: añadir `image`, `banner_title`, `banner_description`, `banner_image`
- [ ] 4.7 Crear migración para `resource_specialties`: añadir `banner_title`, `banner_description`, `banner_image` (`image` ya existe)
- [ ] 4.8 Crear migración para `resources`: añadir `content` (longText), `diagnosis` (text), `gallery` (JSON), `video_url` (string), `materials` (JSON), `results` (longText)
- [ ] 4.9 Crear migración para `settings`: añadir `opinion_url` (string nullable) y `offices` (JSON nullable). La migración incluye paso de migración de datos: leer `caracas_location`/`valencia_location`/`barquisimeto_location` existentes y consolidarlos en el JSON `offices` con `[{name, url, active}]`. El `down()` revierte el JSON a los campos individuales antes de eliminar la columna. Mantener las columnas individuales en esta migración (se eliminan en migración posterior tras verificar).
- [ ] 4.10 Crear migración para tabla nueva `contact_messages` (id, nombre, email, telefono, asunto, mensaje, is_read boolean, timestamps)
- [ ] 4.11 Crear migración para tabla nueva `page_seo` (id, page_slug unique, seo_title, seo_description, seo_keywords, og_image nullable, timestamps)
- [ ] 4.12 Ejecutar `php artisan migrate` y verificar que todas las migraciones se aplican sin error

## 5. Fase 3B — Actualizar modelos y controladores con nuevos campos

- [ ] 5.1 Actualizar `Category::$fillable` (añadir `seo_keywords`, `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`) y `CategoriesController` (gestionar imagen, banner, destacado)
- [ ] 5.2 Actualizar `Brand::$fillable` (añadir `seo_keywords`, `banner_*`) y `BrandsController`
- [ ] 5.3 Actualizar `Line::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `LineController`
- [ ] 5.4 Actualizar `SystemProduct::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `SystemProductsController`
- [ ] 5.5 Actualizar `ProductPlatform::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `ProductPlatformsController`
- [ ] 5.6 Actualizar `ResourceType::$fillable` (añadir `image`, `banner_*`) y `ResourceTypeController`
- [ ] 5.7 Actualizar `ResourceSpecialty::$fillable` (añadir `banner_*`) y `ResourceSpecialtyController`
- [ ] 5.8 Actualizar `Resource::$fillable` (añadir `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results`) y `ResourceController` (gestionar nuevos campos)
- [ ] 5.9 Actualizar `Settings::$fillable` (añadir `opinion_url`, `offices`) y `SettingsController` (gestionar offices como repeater JSON)
- [ ] 5.10 Crear modelo `ContactMessage` con `$fillable` y casts
- [ ] 5.11 Crear modelo `PageSeo` con `$fillable` y casts
- [ ] 5.12 Crear `PageSeoController` (Livewire) para gestionar SEO de páginas estáticas (CRUD por page_slug)
- [ ] 5.13 Crear vista CMS `cms/page-seo/index.blade.php`
- [ ] 5.14 Registrar submódulo Page SEO en `Submodule` y ruta en `routes/web.php` bajo M2
- [ ] 5.15 Actualizar vistas CMS de categories, brands, lines, system-products, product-platforms, resource-types, resource-specialties, resources, settings para gestionar los nuevos campos

## 6. Fase 3C — Reemplazo de contenido hardcodeado por BD en vistas

- [ ] 6.1 Reemplazar el array `$categoryBanners` hardcodeado en `catalogo.blade.php` por `$currentCategory->banner_*`
- [ ] 6.2 Reemplazar el grid de categorías destacadas hardcodeado en `home.blade.php` por `Category::where('is_featured', true)`
- [ ] 6.3 Reemplazar misión/visión hardcodeada en `nuestra-empresa.blade.php` por `Sections::MISSION_VISION` (incluyendo `items`)
- [ ] 6.4 Reemplazar About Us hardcodeado en `nuestra-empresa.blade.php` por `Sections::ABOUT_US` (incluyendo `items`)
- [ ] 6.5 Reemplazar CTAs hardcodeados en `home.blade.php` y `nuestra-empresa.blade.php` por `Sections::CTA_HOME` y `CTA_COMPANY` (incluyendo `buttons`)
- [ ] 6.6 Reemplazar políticas hardcodeadas en `politicas.blade.php` por `Sections::SHIPPING_POLICIES`, `TERMS_CONDITIONS`, `PRIVACY_POLICIES` (eliminar fallbacks hardcodeados)
- [ ] 6.7 Reemplazar beneficios hardcodeados en `partials/beneficios.blade.php` por `items` JSON de la sección correspondiente
- [ ] 6.8 Reemplazar pasos del flow hardcodeados en `home.blade.php` por `items` JSON de la sección `FLOW_HOW_TO`
- [ ] 6.9 Reemplazar quick cards hardcodeadas por `items` JSON de la sección correspondiente
- [ ] 6.10 Reemplazar aliados/logos hardcodeados en `nuestra-empresa.blade.php` por `items` JSON de la sección `ALLIES`
- [ ] 6.11 Reemplazar el enlace de opinión hardcodeado por `Settings::opinion_url` en `partials/opinion.blade.php`
- [ ] 6.12 Reemplazar "Estamos cerca de ti" hardcodeado en `partials/near.blade.php` por sección + `Settings`/`WhatsAppNumber`
- [ ] 6.13 Reemplazar materiales y resultados hardcodeados en `caso-clinico.blade.php` por `$resource->materials` y `$resource->results`. Reemplazar también el párrafo de descripción hardcoded (línea 77) por `$resource->content`
- [ ] 6.14 Eliminar el fallback `match($section->title)` con HTML hardcoded en `politicas.blade.php` (líneas 28-35) y consumir siempre `$section->content` directamente
- [ ] 6.15 Reemplazar el WhatsApp hardcodeado en `solicitud-enviada.blade.php` (`584244669150`) por `WhatsAppNumber`/`Settings`
- [ ] 6.16 Eliminar los productos de ejemplo hardcodeados y datos de cliente falsos en `solicitud-enviada.blade.php` (fallbacks); mostrar mensaje apropiado cuando no hay datos
- [ ] 6.17 Eliminar la tasa de cambio hardcodeada y totales falsos en `solicitud-enviada.blade.php`
- [ ] 6.18 Reemplazar el SEO hardcodeado (`@section('title')`, `meta-description`, `meta-keywords`) de las páginas estáticas (home, contacto, empresa, políticas, recursos) por `PageSeo::where('page_slug', Route::currentRouteName())->first()` con fallback a `Settings` en el layout `app.blade.php`. Para páginas dinámicas (producto, caso clínico) usar el SEO del propio modelo con fallback a `page_seo` por nombre de ruta. Considerar cargar `PageSeo` via `View::share()` o cache para evitar consulta por render.
- [ ] 6.19 Hacer dinámicas las sedes de `contactanos.blade.php` iterando `Settings::offices` (JSON) en lugar de los 3 bloques hardcodeados con nombres fijos. Incluir Maracay que ya tiene `maracay_location` en Settings pero no se muestra en la vista

## 7. Fase 3D — Selector de dimensiones dinámico

- [ ] 7.1 Reemplazar el selector de dimensiones JS hardcodeado en `producto.blade.php` (Ø3.3/Ø4.1/Ø4.8 mm) por los `attribute_values` del producto
- [ ] 7.2 Verificar que el selector muestra las dimensiones dinámicas y actualiza el precio si aplica

## 8. Fase 3E — Módulo de Mensajes de Contacto

- [ ] 8.1 Crear `ContactMessagesController` (Livewire) con listado, detalle, marcar como leído, eliminar
- [ ] 8.2 Crear vista CMS `cms/contact-messages/index.blade.php`
- [ ] 8.3 Modificar `ContactController::send` para guardar el mensaje en `contact_messages` además de enviar email
- [ ] 8.4 Registrar rutas CMS para contact-messages bajo M6, reutilizando submódulos 19/20/21 existentes
- [ ] 8.5 Verificar que un mensaje enviado desde la web se guarda en BD y aparece en el CMS

## 9. Fase 3F — Seeders

- [ ] 9.1 Actualizar `SectionSeeder` con los items/buttons/beneficios/pasos/aliados/quick cards actualmente hardcodeados en las vistas
- [ ] 9.2 Actualizar `CategorySeeder` con `image`, `is_featured`, `banner_*` y `seo_keywords` del contenido hardcodeado actual
- [ ] 9.3 Actualizar `BrandSeeder`, `LineSeeder`, `SystemProductSeeder`, `ProductPlatformSeeder` con `image`, `seo_keywords`, `banner_*`
- [ ] 9.4 Actualizar `ResourceTypeSeeder` y `ResourceSpecialtySeeder` con `image`, `banner_*`
- [ ] 9.5 Actualizar `ResourceSeeder` con `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results` de los casos clínicos hardcodeados
- [ ] 9.6 Actualizar `SettingsSeeder` con `opinion_url` y `offices` (migrar los datos de `caracas_location`/`valencia_location`/`barquisimeto_location` al JSON `offices`)
- [ ] 9.7 Actualizar `TestimonialSeeder` con `specialty` y `content` correctos
- [ ] 9.8 Crear `ContactMessageSeeder` (vacío)
- [ ] 9.9 Crear `PageSeoSeeder` con el SEO actualmente hardcodeado en las vistas (home, contacto, empresa, políticas, recursos)
- [ ] 9.10 Verificar y completar `WhatsAppNumberSeeder` y crear `AttributeSeeder`/`AttributeValueSeeder` si no existen
- [ ] 9.11 Ejecutar `php artisan db:seed` y verificar que la web pública no se ve vacía
- [ ] 9.12 Tras verificar que `offices` JSON funciona correctamente en la web, crear migración que elimine las columnas individuales `caracas_location`/`valencia_location`/`barquisimeto_location` de `settings` (ya obsoletas)

## 10. Verificación final

- [ ] 10.1 Ejecutar `openspec validate cms-web-content-coverage` y corregir cualquier error
- [ ] 10.2 Ejecutar `openspec status --change cms-web-content-coverage`
- [ ] 10.3 Recorrer toda la web pública y confirmar que no queda contenido hardcodeado sin gestionar (excepto `no-results.blade.php` que se deja hardcodeado por ser trivial)
- [ ] 10.4 Recorrer todo el CMS y confirmar que todos los módulos funcionan y guardan correctamente
- [ ] 10.5 Verificar permisos: usuario no admin no puede acceder a ningún submódulo CMS
- [ ] 10.6 Verificar que las migraciones tienen método `down()` funcional (rollback test)
