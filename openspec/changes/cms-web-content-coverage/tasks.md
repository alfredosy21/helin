## 0. Fase 0 — Auditoría integral de módulos CMS

- [x] 0.1 Auditar M1 Administradores: `UserController` (S1 Usuarios) y `RolController` (S2 Roles) — permisos en `mount()`, `$fillable` vs columnas reales de BD, CRUD completo, validación, vistas sin errores
- [x] 0.2 Auditar M2 Configuración: `SettingsController` (S3), `MenuController` (controlador y vista existen **sin submódulo registrado** — verificar registro/rutas), `PaymentMethodController` (S5), `CustomerTypesController` (S6), `DeliveryMethodsController` (S7), `WhatsAppNumbersController` (S24)
- [x] 0.3 Auditar M3 Catálogo: `ProductsController` (S8), `CategoriesController` (S9), `BrandsController` (S10), `LineController` (S11), `SystemProductsController` (S12), `ProductPlatformsController` (S13), `AttributesController` (S25), `AttributeValuesController` (S26)
- [x] 0.4 Auditar M4 Blog (NO cubierto por el plan actual): `BlogCategoriesController` (S14) y `BlogArticlesController` (S15) — CRUD, fillable, permisos, vistas, y si hay vistas públicas de blog con contenido hardcodeado
- [x] 0.5 Auditar M5 Contenido: `TestimonialsController` (S16), `ResourceController` (S17), `ResourceTypeController` (S18), `ResourceSpecialtyController` (S19)
- [x] 0.6 Auditar M6 Solicitudes: `CommercialRequestsController` (S20) — CRUD completo, detalle, estados, permisos
- [x] 0.7 Auditar periferia: `DashboardController`, `ProfileController`, auth (login/logout/reset) y verificar el registro real de submódulos en BD vs lo asumido en proposal.md (incluidos los CONTACT_* 19/20/21)
- [x] 0.8 Consolidar hallazgos: crear tareas nuevas en las fases correspondientes del plan para corregir los problemas y brechas detectadas

> **Hallazgos consolidados (0.8)** → corregidos en la **Fase 3G**:
> - CRÍTICO: `ResourceController::save()` crashea al crear/editar un recurso (`Unknown column 'views'` — columna inexistente en `resources`, probado en vivo).
> - RBAC roto: `PermissionMiddleware` resuelve permisos **por nombre** pero casi todas las rutas pasan **IDs numéricos** (`permission:2,1`, `5,1`…) → cualquier usuario con rol (level≠1) recibe 403; solo funciona el super admin (level 1). El editor (rol 2) no tiene permisos sembrados.
> - IDs desalineados BD↔constantes: la BD numera submódulos por orden de menú (S7=Métodos de Entrega, S16=Testimonios…) mientras `Submodule::PRODUCTS=7`, `TESTIMONIALS=15`… Solo coinciden 1-5 y 24-26. Faltan en BD: submódulo "Menú del Sitio" (`MenuController` existe sin registro) y módulo "Contacto" (M6=Contacto asumido en la Fase 3E no existe; M6=Solicitudes) + IDs 21-23.
> - ~15 breadcrumbs `<x-cms-breadcrumb>` y ~12 rutas con IDs hardcodeados obsoletos (payment-methods breadcrumb apunta al módulo 7 inexistente).
> - Menores: `PaymentMethodController::$paginationTheme='bootstrap'`; fillable muertos en `PaymentMethod` (10 cols), `BlogCategory` (color/icon/image), `Resource` (views).
> - Blog (M4): CMS completo pero **sin páginas públicas** — **documentado como fuera de alcance** (decisión del usuario).

## 1. Fase 1 — Bugs críticos (sin migraciones)

- [x] 1.1 Eliminar el `dd()` en `SectionController::update()` y restaurar la lógica de guardado real
- [x] 1.2 Añadir a `Sections::$fillable`: `subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`. Eliminar `status` duplicado del array.
- [x] 1.3 Añadir a `Product::$fillable`: `material`, `is_biomaterial`, `seo_description`, `seo_keywords`, `system_product_id`, `product_platform_id`
- [x] 1.4 Corregir `Testimonial::$fillable` a `['name', 'specialty', 'content', 'image', 'is_active', 'position']` (quitar `description`/`charge`/`order` inexistentes)
- [x] 1.5 Añadir `image` a `ResourceSpecialty::$fillable` (columna ya existe por migración `2026_07_24_120002`)
- [x] 1.6 Quitar `specialty` y `tags` de `Resource::$fillable` (no existen como columnas). `slug` **ya está** en el fillable (migración `2026_07_26_224817`) — solo verificar que se conserve
- [x] 1.7 Corregir `ResourceController` en todos los métodos que usan `specialty`/`tags` inexistentes: `filterSpecialty` (filtrar por `resource_specialty_id`), `render()` búsqueda (línea 82), `edit()` (líneas 118-120), `save()` (líneas 154-156), `$rules` (líneas 54-56), `resetForm()` (línea 217)
- [x] 1.8 Añadir auto-generación de `slug` en `ResourceController::save()` con `Str::slug($this->title)` y hook `updatedTitle()`
- [x] 1.9 Corregir `caso-clinico.blade.php`: `$resource->specialty->name` → `$resource->resourceSpecialty->name`, `$resource->type->name` → `$resource->resourceType->name`
- [x] 1.10 Añadir verificación de permisos en `mount()` de `CommercialRequestsController`, `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController`
- [x] 1.11 Cambiar `paginationTheme` de `'bootstrap'` a `'tailwind'` en `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController`
- [x] 1.12 Quitar o implementar correctamente `ResourceTypeController::updatedName()` (genera `$this->slug` inexistente)
- [x] 1.13 Eliminar el multiplicador ×4 de recursos en `WebController::recursosClinicos()` (líneas 358-359) **y en `ResourceFilterController::filter()` (líneas 64-65)**; paginar los recursos reales directamente en ambos
- [x] 1.14 Reemplazar el pool de imágenes hardcoded `['im1.png'...'im6.png']` en `WebController::searchProducts()` (líneas 403-405) **y `ProductAutocompleteController::search()` (líneas 47-49)** por `Product::mainImageUrl`
- [x] 1.15 Añadir `with(['attributeValues'])` al eager loading en `WebController::producto()`
- [x] 1.16 Verificar Fase 1: editar una sección, un producto, un testimonio y un recurso desde el CMS y confirmar que se guardan. Verificar que la búsqueda de productos usa imágenes reales y que la página de recursos clínicos no muestra recursos duplicados

## 2. Fase 2A — Infraestructura existente sin usar (cambios simples, sin migraciones nuevas)

- [x] 2.1 Conectar footer y header (`partials/footer.blade.php`, `partials/header.blade.php`) para consumir `Settings::facebook`, `instagram`, `linkedin`, `youtube`
- [x] 2.2 Generar el mega menú del header dinámicamente desde `Menus`/`Category` en lugar de la estructura hardcodeada
- [x] 2.3 Crear `WhatsAppNumbersController` (Livewire) para gestionar `whatsapp_numbers` (CRUD + activar/desactivar)
- [x] 2.4 Crear vista CMS `cms/whatsapp-numbers/index.blade.php`
- [x] 2.5 Registrar submódulo WhatsApp Numbers en `Submodule` y ruta en `routes/web.php` bajo M2
- [x] 2.6 Reemplazar el array `$pickupInfo` hardcodeado en `WebController::solicitud()` por consulta a `WhatsAppNumber` + `State`
- [x] 2.7 Crear `AttributesController` (Livewire) para gestionar `attributes` (CRUD + activar/desactivar)
- [x] 2.8 Crear `AttributeValuesController` (Livewire) para gestionar `attribute_values` (CRUD + reordenar + activar/desactivar)
- [x] 2.9 Crear vistas CMS `cms/attributes/index.blade.php` y `cms/attribute-values/index.blade.php`
- [x] 2.10 Registrar submódulos Attributes y Attribute Values en `Submodule` y rutas en `routes/web.php` bajo M3
- [x] 2.11 Añadir asociación de `attribute_values` al producto en el editor de productos existente
- [x] 2.12 Reemplazar el pool estático `im1.png`–`im6.png` por `Product::mainImageUrl`/`images()` en `producto.blade.php`, `product-results.blade.php` y home
- [x] 2.13 Reemplazar el gallery hardcodeado de `producto.blade.php` por `$product->images`
- [x] 2.14 Reemplazar los productos relacionados hardcodeados en `producto.blade.php` por `$relatedProducts`
- [x] 2.15 Reemplazar imágenes hardcodeadas de testimonios en `home.blade.php` por `$testimonial->image` con fallback. Eliminar la duplicación manual de un testimonio (líneas 381-389) para llenar el carrusel
- [x] 2.16 Reemplazar el WhatsApp hardcodeado del sidebar de `caso-clinico.blade.php` por `WhatsAppNumber`/`Settings`
- [x] 2.17 Reemplazar la imagen hero hardcodeada de `caso-clinico.blade.php` por `$resource->thumbnail`
- [x] 2.18 Reemplazar el bloque "Categoría Destacada" hardcoded en `home.blade.php` (líneas 93-100, `categoria1.png`) por `Category::where('is_featured', true)->first()` o sección CMS
- [x] 2.19 Reemplazar la sección "Destacados en Instrumentos y Equipos" en `home.blade.php` (líneas 277-330, 4 productos fake) por productos reales o eliminar si `$productSections` ya la cubre
- [x] 2.20 Reemplazar `team_helin_test.png` hardcoded en `nuestra-empresa.blade.php` (línea 132) por `$teamSection->image` con fallback
- [x] 2.21 Reemplazar WhatsApp `584244669150` hardcoded en `nuestra-empresa.blade.php` CTA (línea 160) por `WhatsAppNumber`/`Settings`
- [x] 2.22 Verificar Fase 2A: gestionar WhatsApp y atributos desde el CMS; confirmar que la web consume imágenes reales de productos y no muestra contenido fake

## 3. Fase 2B — Editor repeater de items/buttons JSON (complejo, aislado)

- [x] 3.1 Ampliar `SectionController` para gestionar campos estructurados (`subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`)
- [x] 3.2 Implementar componente repeater en Livewire para editar `items` JSON (añadir/editar/reordenar/eliminar items con campos icon, title, description, order, url según layout_type)
- [x] 3.3 Implementar componente repeater en Livewire para editar `buttons` JSON (añadir/editar/eliminar botones con texto y URL)
- [x] 3.4 Crear/actualizar vista CMS `cms/sections/index.blade.php` con el editor repeater
- [x] 3.5 Validar estructura JSON antes de guardar (esquema por `layout_type`)
- [x] 3.6 Verificar Fase 2B: editar una sección con items y botones desde el CMS; confirmar que se guarda y la web lo muestra

## 4. Fase 3A — Migraciones de campos nuevos

- [x] 4.1 Crear migración para `categories`: añadir `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.2 Crear migración para `brands`: añadir `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.3 Crear migración para `lines`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.4 Crear migración para `system_products`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.5 Crear migración para `product_platforms`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.6 Crear migración para `resource_types`: añadir `image`, `banner_title`, `banner_description`, `banner_image`
- [x] 4.7 Crear migración para `resource_specialties`: añadir `banner_title`, `banner_description`, `banner_image` (`image` ya existe)
- [x] 4.8 Crear migración para `resources`: añadir `content` (longText), `diagnosis` (text), `gallery` (JSON), `video_url` (string), `materials` (JSON), `results` (longText)
- [x] 4.9 Crear migración para `settings`: añadir `opinion_url` (string nullable) y `offices` (JSON nullable). La migración incluye paso de migración de datos: leer `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp` existentes y consolidarlos en el JSON `offices` con `[{name, url, whatsapp, active}]`. El `down()` revierte el JSON a los campos individuales antes de eliminar la columna. Mantener las columnas individuales en esta migración (se eliminan en migración posterior tras verificar).
- [x] 4.10 Crear migración para tabla nueva `contact_messages` (id, nombre, email, telefono, asunto, mensaje, is_read boolean, timestamps)
- [x] 4.11 Crear migración para tabla nueva `page_seo` (id, page_slug unique, seo_title, seo_description, seo_keywords, og_image nullable, timestamps)
- [x] 4.12 Ejecutar `php artisan migrate` y verificar que todas las migraciones se aplican sin error

## 5. Fase 3B — Actualizar modelos y controladores con nuevos campos

- [x] 5.1 Actualizar `Category::$fillable` (añadir `seo_keywords`, `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`) y `CategoriesController` (gestionar imagen, banner, destacado)
- [x] 5.2 Actualizar `Brand::$fillable` (añadir `seo_keywords`, `banner_*`) y `BrandsController`
- [x] 5.3 Actualizar `Line::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `LineController`
- [x] 5.4 Actualizar `SystemProduct::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `SystemProductsController`
- [x] 5.5 Actualizar `ProductPlatform::$fillable` (añadir `image`, `seo_keywords`, `banner_*`) y `ProductPlatformsController`
- [x] 5.6 Actualizar `ResourceType::$fillable` (añadir `image`, `banner_*`) y `ResourceTypeController`
- [x] 5.7 Actualizar `ResourceSpecialty::$fillable` (añadir `banner_*`) y `ResourceSpecialtyController`
- [x] 5.8 Actualizar `Resource::$fillable` (añadir `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results`) y `ResourceController` (gestionar nuevos campos)
- [x] 5.9 Actualizar `Settings::$fillable` (añadir `opinion_url`, `offices`) y `SettingsController` (gestionar offices como repeater JSON)
- [x] 5.10 Crear modelo `ContactMessage` con `$fillable` y casts
- [x] 5.11 Crear modelo `PageSeo` con `$fillable` y casts
- [x] 5.12 Crear `PageSeoController` (Livewire) para gestionar SEO de páginas estáticas (CRUD por page_slug)
- [x] 5.13 Crear vista CMS `cms/page-seo/index.blade.php`
- [x] 5.14 Registrar submódulo Page SEO en `Submodule` y ruta en `routes/web.php` bajo M2
- [x] 5.15 Actualizar vistas CMS de categories, brands, lines, system-products, product-platforms, resource-types, resource-specialties, resources, settings para gestionar los nuevos campos

## 6. Fase 3C — Reemplazo de contenido hardcodeado por BD en vistas

- [x] 6.1 Reemplazar el array `$categoryBanners` hardcodeado en `catalogo.blade.php` por `$currentCategory->banner_*`
- [x] 6.2 Reemplazar el grid de categorías destacadas hardcodeado en `home.blade.php` por `Category::where('is_featured', true)`
- [x] 6.3 Reemplazar misión/visión hardcodeada en `nuestra-empresa.blade.php` por `Sections::MISSION_VISION` (incluyendo `items`)
- [x] 6.4 Reemplazar About Us hardcodeado en `nuestra-empresa.blade.php` por `Sections::ABOUT_US` (incluyendo `items`)
- [x] 6.5 Reemplazar CTAs hardcodeados en `home.blade.php` y `nuestra-empresa.blade.php` por `Sections::CTA_HOME` y `CTA_COMPANY` (incluyendo `buttons`)
- [x] 6.6 Reemplazar políticas hardcodeadas en `politicas.blade.php` por `Sections::SHIPPING_POLICIES`, `TERMS_CONDITIONS`, `PRIVACY_POLICIES` (eliminar fallbacks hardcodeados)
- [x] 6.7 Reemplazar beneficios hardcodeados en `partials/beneficios.blade.php` por `items` JSON de la sección correspondiente
- [x] 6.8 Reemplazar pasos del flow hardcodeados en `home.blade.php` por `items` JSON de la sección `FLOW_HOW_TO`
- [x] 6.9 Reemplazar quick cards hardcodeadas por `items` JSON de la sección correspondiente
- [x] 6.10 Reemplazar aliados/logos hardcodeados en `nuestra-empresa.blade.php` por `items` JSON de la sección `ALLIES`
- [x] 6.11 Reemplazar el enlace de opinión hardcodeado por `Settings::opinion_url` en `partials/opinion.blade.php`
- [x] 6.12 Reemplazar "Estamos cerca de ti" hardcodeado en `partials/near.blade.php` (4 sedes con números de WhatsApp distintos: `584242789481`, `584244669150`, `584143805640`, `584242550811`) por `Settings::offices` (JSON con `name`/`url`/`whatsapp`/`active`)
- [x] 6.13 Reemplazar materiales y resultados hardcodeados en `caso-clinico.blade.php` por `$resource->materials` y `$resource->results`. Reemplazar también el párrafo de descripción hardcoded (línea 77) por `$resource->content`
- [x] 6.14 Eliminar el fallback `match($section->title)` con HTML hardcoded en `politicas.blade.php` (líneas 28-35) y consumir siempre `$section->content` directamente
- [x] 6.15 Reemplazar el WhatsApp hardcodeado en `solicitud-enviada.blade.php` (`584244669150`) por `WhatsAppNumber`/`Settings`
- [x] 6.16 Eliminar los productos de ejemplo hardcodeados y datos de cliente falsos en `solicitud-enviada.blade.php` (fallbacks); mostrar mensaje apropiado cuando no hay datos
- [x] 6.17 Eliminar la tasa de cambio hardcodeada y totales falsos en `solicitud-enviada.blade.php`
- [x] 6.18 Reemplazar el SEO hardcodeado (`@section('title')`, `meta-description`, `meta-keywords`) de las páginas estáticas (home, contacto, empresa, políticas, recursos) por `PageSeo::where('page_slug', Route::currentRouteName())->first()` con fallback a `Settings` en el layout `app.blade.php`. Para páginas dinámicas (producto, caso clínico) usar el SEO del propio modelo con fallback a `page_seo` por nombre de ruta. Considerar cargar `PageSeo` via `View::share()` o cache para evitar consulta por render.
- [x] 6.19 Hacer dinámicas las sedes de `contactanos.blade.php` y `partials/near.blade.php` iterando `Settings::offices` (JSON) en lugar de los bloques hardcodeados con nombres fijos. Incluir Maracay y Maracaibo (que existen en Settings pero no se muestran en la vista de contacto)

## 7. Fase 3D — Selector de dimensiones dinámico

- [x] 7.1 Reemplazar el selector de dimensiones JS hardcodeado en `producto.blade.php` (Ø3.3/Ø4.1/Ø4.8 mm) por los `attribute_values` del producto
- [x] 7.2 Verificar que el selector muestra las dimensiones dinámicas y actualiza el precio si aplica

## 8. Fase 3E — Módulo de Mensajes de Contacto

- [ ] 8.1 Crear `ContactMessagesController` (Livewire) con listado, detalle, marcar como leído, eliminar
- [ ] 8.2 Crear vista CMS `cms/contact-messages/index.blade.php`
- [ ] 8.3 Modificar `ContactController::send` para guardar el mensaje en `contact_messages` además de enviar email
- [ ] 8.4 Registrar rutas CMS para contact-messages bajo el módulo **Contacto (M7, creado en la Fase 3G)** con los submódulos CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG (21/22/23) y sembrar permisos en el seeder
- [ ] 8.5 Verificar que un mensaje enviado desde la web se guarda en BD y aparece en el CMS

## 9. Fase 3F — Seeders

- [x] 9.1 Actualizar `SectionSeeder` con los items/buttons/beneficios/pasos/aliados/quick cards actualmente hardcodeados en las vistas
- [x] 9.2 Actualizar `CategorySeeder` con `image`, `is_featured`, `banner_*` y `seo_keywords` del contenido hardcodeado actual
- [x] 9.3 Actualizar `BrandSeeder`, `LineSeeder`, `SystemProductSeeder`, `ProductPlatformSeeder` con `image`, `seo_keywords`, `banner_*`
- [x] 9.4 Actualizar `ResourceTypeSeeder` y `ResourceSpecialtySeeder` con `image`, `banner_*`
- [x] 9.5 Actualizar `ResourceSeeder` con `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results` de los casos clínicos hardcodeados
- [x] 9.6 Actualizar `SettingsSeeder` con `opinion_url` y `offices` (migrar los datos de `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp` al JSON `offices`)
- [x] 9.7 Actualizar `TestimonialSeeder` con `specialty` y `content` correctos
- [x] 9.8 Crear `ContactMessageSeeder` (vacío)
- [x] 9.9 Crear `PageSeoSeeder` con el SEO actualmente hardcodeado en las vistas (home, contacto, empresa, políticas, recursos)
- [ ] 9.10 Verificar y completar `WhatsAppNumberSeeder` y crear `AttributeSeeder`/`AttributeValueSeeder` si no existen
- [ ] 9.11 Ejecutar `php artisan db:seed` y verificar que la web pública no se ve vacía
- [ ] 9.12 Tras verificar que `offices` JSON funciona correctamente en la web, crear migración que elimine las columnas individuales `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp` de `settings` (ya obsoletas)

## 10. Fase 3G — Correcciones de la auditoría (RBAC, IDs, bugs)

- [x] 10.1 Fix crash al guardar recursos: eliminar `views` de `Resource::$fillable`, de `edit()` y de `save()` (columna inexistente — probado: crear/editar crashea con `Unknown column 'views'`)
- [x] 10.2 Alinear `Submodule` constantes con los IDs reales de BD: PRODUCTS=8, PRODUCT_FAMILIES=9, PRODUCT_BRANDS=10, PRODUCT_LINES=11, SYSTEM_PRODUCTS=12, PRODUCT_PLATFORMS=13, BLOG_CATEGORIES=14, BLOG_ARTICLES=15, TESTIMONIALS=16, CLINICAL_RESOURCES=17, RESOURCE_TYPES=18, RESOURCE_SPECIALTIES=19, CUSTOMER_TYPES=6, DELIVERY_METHODS=7; añadir faltantes: COMMERCIAL_REQUESTS=20, WEBSITE_MENU=27, CONTACT_MESSAGES=21, CONTACT_MANAGEMENT=22, CONTACT_FORM_CONFIG=23
- [x] 10.3 Actualizar `ModuleSeeder` para forzar IDs por constantes en todos los submódulos y crear los ausentes en BD: "Menú del Sitio" (WEBSITE_MENU, bajo M2) y módulo "Contacto" (M7) con CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG; re-seed y verificar coherencia BD↔constantes↔permisos
- [x] 10.4 Fix `PermissionMiddleware`: resolver por ID cuando el argumento es numérico (por nombre si no lo es). Corregir las rutas hardcodeadas para usar constantes: testimonials `5,1`→(CONTENT,TESTIMONIALS), resources `5,2`→(CONTENT,CLINICAL_RESOURCES), resource-types `5,3`→(CONTENT,RESOURCE_TYPES), resource-specialties `5,4`→(CONTENT,RESOURCE_SPECIALTIES), payment-methods `2,3`→(SETTINGS,PAYMENT_METHODS), menu `2,4`→(SETTINGS,WEBSITE_MENU), blog `4,1`/`4,2`→(BLOG,BLOG_CATEGORIES/BLOG_ARTICLES), settings `2,1`→(SETTINGS,GENERAL_SETTINGS), sections `2,2`→(SETTINGS,SECTIONS), commercial-requests `6,1`→(SOLICITUDES,COMMERCIAL_REQUESTS), dashboard `1`→(ADMINISTRATORS)
- [x] 10.5 Corregir `<x-cms-breadcrumb>` con IDs obsoletos en las vistas CMS usando constantes: products `(3,5)`→(3,8), categories `(3,6)`→(3,9), brands `(3,7)`→(3,10), lines `(3,8)`→(3,11), system-products `(3,9)`→(3,12), product-platforms `(3,10)`→(3,13), blog_categories `(4,11)`→(4,14), blog_articles `(4,12)`→(4,15), testimonials `(5,13)`→(5,16), resources `(6,14)`→(5,17), resource-types `(6,16)`→(5,18), resource-specialties `(6,17)`→(5,19), commercial_requests `(6,14)`→(6,20), payment-methods `(7,15)`→(2,5), delivery-methods `(2,5)`→(2,7), customer-types `(2,4)`→(2,6), menu `(1,1)`→(2,27)
- [x] 10.6 Sembrar permisos de submódulo para el rol Editor (rol 2, level 2) y verificar RBAC real: el editor accede a sus módulos y recibe 403 en los que no tiene; super admin sigue con acceso total
- [x] 10.7 Menores: `PaymentMethodController::$paginationTheme` → `'tailwind'`; limpiar fillable muertos (`PaymentMethod`: slug/icon/image/config/is_default/provider/provider_config/fee_*; `BlogCategory`: color/icon/image)
- [x] 10.8 Verificar Fase 3G: crear y editar un recurso desde el CMS sin error; breadcrumbs correctos en todos los módulos; rutas con permisos funcionando para admin y editor

## 11. Verificación final

- [ ] 11.1 Ejecutar `openspec validate cms-web-content-coverage` y corregir cualquier error
- [ ] 11.2 Ejecutar `openspec status --change cms-web-content-coverage`
- [ ] 11.3 Recorrer toda la web pública y confirmar que no queda contenido hardcodeado sin gestionar (excepto `no-results.blade.php` que se deja hardcodeado por ser trivial)
- [ ] 11.4 Recorrer todo el CMS y confirmar que todos los módulos funcionan y guardan correctamente
- [ ] 11.5 Verificar permisos: usuario con rol solo accede a sus módulos (403 en el resto); super admin acceso total
- [ ] 11.6 Verificar que las migraciones tienen método `down()` funcional (rollback test)
