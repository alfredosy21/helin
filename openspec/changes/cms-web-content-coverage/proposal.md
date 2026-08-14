## Why

La web pública de Helin contiene una gran cantidad de contenido **hardcodeado en las vistas Blade** que no puede ser actualizado desde el CMS: barra de beneficios, categorías destacadas del home, pasos del flow "Cómo solicitar productos", mega menú de navegación, redes sociales del footer, misión/visión, aliados/logos, CTAs, políticas, sección de opinión, tabs y materiales de casos clínicos, selector de dimensiones de producto, banners de categorías, y un pool estático de imágenes de productos. Esto obliga a un desarrollador a editar código cada vez que se quiere cambiar un texto, un logo o un enlace, anulando el propósito del CMS. Adicionalmente, hay **bugs críticos** (`dd()` en `SectionController`, `$fillable` desactualizados en varios modelos, relaciones incorrectas en vistas) que rompen incluso el contenido que sí debería ser editable. Este cambio cierra la brecha entre lo que el CMS gestiona y lo que la web muestra, **ampliando los módulos existentes** para que todo el contenido público sea gestionable sin tocar código.

## What Changes

> El cambio se organiza en fases dentro del mismo change, aplicables de forma incremental. Cada fase deja el sistema funcional. Fase 0 = auditoría integral de todos los módulos del CMS (detectar brechas y bugs no cubiertos por el resto del plan). Fase 1 = bugs críticos (urgente, bajo riesgo, sin migraciones). Fase 2 = infraestructura ya existente sin usar (mediano riesgo, sin migraciones nuevas). Fase 3 = features nuevas y migraciones (mayor esfuerzo). Los "opcionales" previos (banners de marcas/tipos/especialidades) se confirman como incluidos.

---

### FASE 0 — Auditoría integral de módulos CMS

El plan original cubre los módulos relacionados con el contenido web público (secciones, recursos, testimonios, productos, taxonomías, settings, whatsapp, atributos), pero **no audita la totalidad del CMS**. Hay submódulos y controladores que el plan no toca y que pueden tener los mismos problemas que la Fase 1 corrigió (permisos faltantes, `$fillable` desactualizados, uso de columnas inexistentes, CRUD incompleto, paginación inconsistente) o contener contenido hardcodeado no gestionable desde el CMS.

- Auditar sistemáticamente cada módulo del CMS (M1–M6: 26 submódulos + menú, dashboard, perfil y auth) contra la checklist estándar usada en la Fase 1: verificación de permisos en `mount()`, `$fillable` alineado con las columnas reales de BD, CRUD completo (index/create/edit/update/delete), validación coherente con las columnas, `paginationTheme` uniforme (`tailwind`), vistas CMS que renderizan sin error, rutas registradas en `routes/web.php`, submódulos registrados en `Submodule`, relaciones correctas.
- Módulos **no cubiertos** por el resto del plan y que la auditoría debe revisar en detalle:
  - **Blog (M4: categorías S14 y artículos S15)** — incluyendo si existen vistas públicas de blog y si muestran contenido hardcodeado.
  - **Configuración → Métodos de Pago (S5), Tipos de Cliente (S6), Métodos de Entrega (S7)**.
  - **Menú del Sitio** — `MenuController` existe con vista pero **sin submódulo registrado en BD** (gap de registro y rutas).
  - **Administradores (M1: Usuarios S1, Roles S2)** y **Dashboard, Perfil y Auth**.
  - **Solicitudes Comerciales (M6: S20)** — CRUD completo y detalle.
- Verificar el registro real de submódulos en BD contra lo asumido en este documento (p.ej. los submódulos CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG 19/20/21 y el orden real de IDs tras reseeding).
- Consolidar los hallazgos como tareas nuevas en las fases correspondientes del plan (correcciones de bugs, campos gestionables, contenido hardcodeado detectado en módulos no cubiertos).

> **Resultado de la auditoría** (ejecutada): la corrección de los hallazgos se consolida en la **Fase 3G**:
> - **Crítico**: `ResourceController::save()` crashea al crear/editar un recurso (`Unknown column 'views'` — columna inexistente).
> - **RBAC roto**: `PermissionMiddleware` resuelve permisos por nombre pero las rutas pasan IDs numéricos → usuarios con rol (level≠1) reciben 403 en casi todo el CMS; solo el super admin (level 1) funciona.
> - **IDs desalineados BD↔constantes**: la BD numera los submódulos por orden de menú (S7=Métodos de Entrega, S16=Testimonios…) mientras las constantes del modelo usan otro esquema (PRODUCTS=7, TESTIMONIALS=15…). Faltan en BD: submódulo "Menú del Sitio" (`MenuController` existe sin registro) y el módulo "Contacto" (los submódulos CONTACT_* 19/20/21 que asumía la Fase 3D no existen).
> - **~15 breadcrumbs `<x-cms-breadcrumb>` y ~12 rutas** con IDs hardcodeados obsoletos.
> - **Menores**: `PaymentMethodController::$paginationTheme='bootstrap'`; fillable muertos en `PaymentMethod`, `BlogCategory` y `Resource`.
> - **Blog (M4)**: CMS completo pero sin páginas públicas — documentado como fuera de alcance.

---

### FASE 1 — Bugs críticos (bloqueante, bajo riesgo, sin migraciones)

- **BREAKING (para el bug)**: Eliminar las sentencias `dd()` en `SectionController::update()` que impiden guardar secciones. Restaurar la lógica de guardado real.
- **`Sections::$fillable` incompleto y con duplicado**: Añadir `subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style` — columnas **ya existentes en BD** (migración `2026_06_27_120000`) pero no expuestas. Adicionalmente, el array tiene `status` duplicado (líneas 58 y 62) — eliminar el duplicado.
- **`Product::$fillable` incompleto**: Añadir `material`, `is_biomaterial`, `seo_description`, `seo_keywords`, `system_product_id`, `product_platform_id` — columnas **ya existentes en BD**.
- **`Testimonial::$fillable` incorrecto**: El fillable tiene `description`/`charge`/`order` que **no existen en la BD** (causarían error si se usan), y NO tiene `specialty`/`content` que el controlador guarda y **se pierden silenciosamente**. La BD tiene `name`, `specialty`, `content`, `is_active`, `position`, `image`. El fillable correcto es `['name', 'specialty', 'content', 'image', 'is_active', 'position']`.
- **`ResourceSpecialty::$fillable` incompleto**: La migración `2026_07_24_120002` añadió `image` a la BD pero el fillable no la incluye. Añadir `image`.
- **`Resource::$fillable` incorrecto**: Incluye `specialty`/`tags` (no existen como columnas). `slug` **sí está** en el fillable (añadido por migración `2026_07_26_224817` y usado en rutas/vistas) — solo hay que verificar que se conserve al quitar los campos inválidos. `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results` no existen en BD (requieren migración — fase 3).
- **`ResourceController` usa columnas inexistentes en múltiples métodos**: `filterSpecialty` filtra por `specialty` (string) en lugar de `resource_specialty_id` (FK). La búsqueda (`render()`) filtra por `specialty` (línea 82). El `edit()` carga `specialty`/`tags` (líneas 118-120). El `save()` guarda `specialty`/`tags` en `$data` (líneas 154-156). Las `$rules` validan `specialty`/`tags` (líneas 54-56). El `resetForm()` resetea `specialty`/`tags` (línea 217). Corregir todos estos métodos para usar `resource_specialty_id` y eliminar referencias a `specialty`/`tags`.
- **`ResourceController` no genera `slug` automáticamente**: El controlador requiere que el usuario ingrese el slug manualmente, pero el patrón del resto del CMS es generar slug desde el título. Añadir auto-generación con `Str::slug()`.
- **Vistas con relaciones incorrectas**: `caso-clinico.blade.php` usa `$resource->specialty->name` y `$resource->type->name` pero el modelo define `resourceSpecialty()` y `resourceType()`. Corregir.
- **Permisos faltantes en `mount()`**: `CommercialRequestsController`, `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController` no verifican permisos. Añadir verificación consistente.
- **Inconsistencia de paginación**: `ResourceController`, `ResourceSpecialtyController`, `ResourceTypeController` usan `paginationTheme = 'bootstrap'` — unificar a `'tailwind'`.
- **`ResourceTypeController::updatedName()`**: Genera `$this->slug` pero el controlador no tiene propiedad `slug`. Quitar o implementar correctamente.
- **`WebController::recursosClinicos()` y `ResourceFilterController::filter()` duplican recursos artificialmente**: `WebController.php` líneas 358-359 y `ResourceFilterController.php` líneas 64-65 multiplican los recursos ×4 (`array_fill(0, $multiplier, $r)`) para simular más contenido. Esto es un bug de diseño que infla la paginación y muestra recursos repetidos. Eliminar el multiplicador en **ambos** y paginar los recursos reales.
- **`WebController::searchProducts()` y `ProductAutocompleteController::search()` usan imágenes hardcoded**: `WebController.php` líneas 403-405 y `ProductAutocompleteController.php` líneas 47-49 usan un pool estático `['im1.png'...'im6.png']` para los resultados de búsqueda AJAX en lugar de las imágenes reales del producto. Reemplazar por `Product::mainImageUrl` o equivalente.
- **`WebController::producto()` no carga `attributeValues`**: El método no hace `with(['attributeValues'])`, por lo que la vista `producto.blade.php` no tiene acceso a las variantes/dimensiones del producto. Añadir eager loading.

---

### FASE 2 — Infraestructura existente sin usar (mediano riesgo, sin migraciones nuevas)

#### 2A. Cambios simples (bajo riesgo)
- **Configuración → Settings**: Consumir en footer/header los campos `facebook`, `instagram`, `linkedin`, `youtube` que ya existen en `Settings` pero la web no usa.
- **Configuración → Menú del Sitio**: Generar el mega menú del header dinámicamente desde `Menus`/`Category` en lugar de la estructura hardcodeada.
- **Nuevo submódulo Configuración → WhatsApp Numbers (M2)**: La tabla `whatsapp_numbers` ya existe (con `executive_name`). Crear `WhatsAppNumbersController` (Livewire) + registrar submódulo + reemplazar el array `$pickupInfo` hardcodeado en `WebController::solicitud()` por consulta a `WhatsAppNumber` + `State`.
- **Nuevo submódulo Catálogo → Atributos (M3)**: Las tablas `attributes`/`attribute_values`/`attribute_value_product` ya existen con modelos. Crear `AttributesController` + `AttributeValuesController` (Livewire) + registrar submódulos.
- **Catálogo → Productos**: Usar `Product::mainImageUrl`/`images()` en las vistas en lugar del pool estático `im1.png`–`im6.png`. Reemplazar el gallery hardcodeado por `$product->images`. Reemplazar los productos relacionados hardcodeados por `$relatedProducts` que el controlador ya pasa. Asociar `attribute_values` al producto desde el editor.
- **Contenido → Testimonios**: `home.blade.php` asigna imágenes hardcodeadas (`dra_test.png`, etc.) según el nombre del autor — usar `$testimonial->image` con fallback.
- **Contenido → Recursos Clínicos**: Reemplazar el WhatsApp hardcodeado del sidebar de `caso-clinico.blade.php` (`584244669150`) por `WhatsAppNumber`/`Settings`. Reemplazar la imagen hero hardcodeada por `$resource->thumbnail`.
- **Home → Categoría destacada**: `home.blade.php` líneas 93-100 tienen un bloque "Categoría Destacada" con imagen `categoria1.png`, título "Implantología" y link hardcoded. Reemplazar por `Category::where('is_featured', true)->first()` o una sección CMS.
- **Home → Sección Instrumentos y Equipos**: `home.blade.php` líneas 277-330 tienen una sección completa "Destacados en Instrumentos y Equipos" con 4 productos fake (nombres, imágenes y precios inventados). Reemplazar por productos reales de la categoría correspondiente o eliminar si la lógica de `$productSections` ya la cubre.
- **Home → Duplicación manual de testimonio**: `home.blade.php` líneas 381-389 duplican manualmente un testimonio para llenar el carrusel. Si hay pocos testimonios, el carrusel debe manejarse con CSS/JS sin duplicar datos.
- **Nuestra Empresa → Team photo**: `nuestra-empresa.blade.php` línea 132 usa `team_helin_test.png` hardcoded. Usar `$teamSection->image` con fallback.
- **Nuestra Empresa → CTA WhatsApp**: `nuestra-empresa.blade.php` línea 160 tiene WhatsApp `584244669150` hardcoded. Reemplazar por `WhatsAppNumber`/`Settings`.

#### 2B. Editor repeater de items/buttons JSON (mayor complejidad técnica, aislado)
- **Configuración → Secciones**: Ampliar `SectionController` para gestionar los campos estructurados ya en BD (`subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`) con editor repeater para `items`/`buttons` JSON. Este es el componente más complejo del change y se aísla para gestionar su riesgo de forma independiente.

---

### FASE 3 — Features nuevas y migraciones (mayor esfuerzo)

#### 3A. Migraciones de campos nuevos (todas las entidades taxonómicas)

Añadir a todas las entidades taxonómicas del catálogo y contenido los campos de imagen, SEO keywords y banner para que TODO su contenido sea gestionable:

- **`categories`**: `image`, `is_featured`, `seo_keywords` (col existe vía migración `2026_06_30` pero no en fillable), `banner_title`, `banner_description`, `banner_image`.
- **`brands`**: `seo_keywords`, `banner_title`, `banner_description`, `banner_image`.
- **`lines`**: `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`.
- **`system_products`**: `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`.
- **`product_platforms`**: `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`.
- **`resource_types`**: `image`, `banner_title`, `banner_description`, `banner_image`.
- **`resource_specialties`**: `banner_title`, `banner_description`, `banner_image` (`image` ya existe).
- **`resources`**: `content` (longText), `diagnosis` (text), `gallery` (JSON), `video_url` (string), `materials` (JSON), `results` (longText).
- **`settings`**: `opinion_url` (string nullable), `offices` (JSON nullable — reemplaza los campos individuales `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp` con estructura `[{name, url, whatsapp, active}]`).
- **`contact_messages`**: tabla nueva (nombre, email, teléfono, asunto, mensaje, is_read, timestamps).
- **`page_seo`**: tabla nueva (`page_slug` unique, `seo_title`, `seo_description`, `seo_keywords`, `og_image` nullable) para gestionar SEO de cada página estática.

Actualizar `$fillable` y controladores CMS de todas las entidades anteriores para gestionar los nuevos campos (imagen, banner, SEO keywords).

#### 3B. Reemplazo de contenido hardcodeado por BD

- **Banner del catálogo**: Reemplazar el array `$categoryBanners` hardcodeado en `catalogo.blade.php` (~15 entradas) por `$currentCategory->banner_*`. Aplicar mismo patrón para marcas/líneas/sistemas/plataformas/tipos/especialidades.
- **Categorías destacadas del home**: Consumir `Category::where('is_featured', true)` en lugar del grid hardcodeado (6 categorías con `categoria1.png`).
- **Mega menú del header**: Generar dinámicamente desde `Category` (activas, ordenadas) en lugar de la estructura hardcodeada.
- **Misión y Visión**: Consumir `Sections::MISSION_VISION` (incluyendo `items` con misión/visión) en lugar del texto estático.
- **About Us**: Consumir `Sections::ABOUT_US` (incluyendo `description` y features en `items`) en lugar del texto hardcodeado.
- **CTAs (home y empresa)**: Consumir `Sections::CTA_HOME` y `Sections::CTA_COMPANY` (texto + `buttons`) en lugar del contenido estático.
- **Políticas**: Eliminar el fallback hardcodeado y consumir siempre `Sections::SHIPPING_POLICIES`, `TERMS_CONDITIONS`, `PRIVACY_POLICIES`.
- **Sección "Estamos cerca de ti"**: Consumir texto de una sección + datos de sedes de `Settings`/`WhatsAppNumber`.
- **Sección de opinión**: Consumir `Settings::opinion_url` en lugar del enlace Typeform hardcodeado.
- **Bloques en `items` JSON de secciones** (sin tablas nuevas):
  - **Beneficios** (`partials/beneficios.blade.php`): 5 items con `icon`, `title`, `description`, `order`.
  - **Pasos del flow** "¿Cómo solicitar productos Helin?": 3 items con `icon`, `title`, `description`, `order`.
  - **Quick cards de recursos clínicos**: 4 items con `icon`, `title`, `url`.
  - **Features de About Us**: 4 items con `icon`, `title`, `description`.
  - **Aliados/logos**: items con `name`, `logo`, `url`, `order` (reemplaza los 6 logos hardcodeados).
- **Materiales y resultados de caso clínico**: Reemplazar los 5 materiales estáticos por `$resource->materials` y el texto de resultados por `$resource->results`. Reemplazar también el párrafo de descripción hardcoded (línea 77) por `$resource->content`.
- **Políticas — fallback `match()` en Blade**: `politicas.blade.php` líneas 28-35 tienen un `match($section->title)` con HTML hardcoded para políticas de envío y términos. Eliminar este fallback y consumir siempre `$section->content`.
- **Página de solicitud enviada**: Reemplazar el WhatsApp hardcodeado (`584244669150`) por `WhatsAppNumber`/`Settings`. Eliminar los productos de ejemplo hardcodeados (Implante Dental Cónico $195, Pilar Protésico $85, Kit Quirúrgico $1250) y los datos de cliente falsos (Gabriel Montes, SY Evolution, etc.) que aparecen como fallback — mostrar un mensaje apropiado en su lugar. Eliminar la tasa de cambio hardcodeada (567.68) y los totales falsos.
- **SEO por página estática**: Las vistas de home, contacto, nuestra-empresa, políticas y recursos-clinicos tienen `@section('title')`, `@section('meta-description')` y `@section('meta-keywords')` hardcodeadas en Blade. **Decisión**: crear tabla nueva `page_seo` (`page_slug` unique, `seo_title`, `seo_description`, `seo_keywords`, `og_image` nullable) con un submódulo CMS en Configuración para gestionar el SEO de cada página estática. El layout `app.blade.php` consulta `PageSeo::where('page_slug', $pageSlug)` y usa sus valores con fallback a `Settings`.
- **Sedes en contacto y "Estamos cerca de ti"**: Los nombres y números de WhatsApp de las sedes (Caracas, Valencia, Barquisimeto, Maracaibo — y Maracay que existe en Settings pero no se muestra) están hardcodeados en `contactanos.blade.php` y `partials/near.blade.php`. **Decisión**: reemplazar los campos individuales `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp` por un campo JSON `offices` en `Settings` con estructura `[{name, url, whatsapp, active}]`, gestionable desde el CMS. Las vistas iteran el JSON en lugar de bloques hardcodeados.

#### 3C. Selector de dimensiones dinámico

- Reemplazar el selector de dimensiones JS hardcodeado en `producto.blade.php` (Ø3.3/Ø4.1/Ø4.8 mm con precios en JS) por los `attribute_values` del producto (gestionados en fase 2).

#### 3D. Módulo de Mensajes de Contacto (M7)

> La auditoría (Fase 0) verificó que los submódulos CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG **no existen en la BD** (la propuesta original asumía IDs 19/20/21 que en realidad corresponden a Especialidades y Solicitudes Comerciales). El módulo "Contacto" se crea en la Fase 3G (M7, submódulos 21/22/23).

- Crear modelo `ContactMessage` y `ContactMessagesController` (Livewire) para gestionar mensajes (listar, ver, marcar como leído, eliminar).
- Modificar `ContactController::send` para **guardar el mensaje en BD** además de enviar el email.
- Registrar rutas CMS para contact-messages bajo el módulo Contacto (M7, creado en 3G).

#### 3E. Seeders

- Actualizar/crear seeders para sembrar los nuevos campos (`banner_*`, `image`, `seo_keywords`, `items`, `buttons`, `opinion_url`, `materials`, `results`, etc.) con el contenido hardcodeado actual de las vistas, para que la web no se vea vacía tras el cambio.
- Sembrar `contact_messages` vacío (tabla nueva).
- Verificar y completar seeders de `whatsapp_numbers` y `attributes`/`attribute_values` si no tienen datos.

#### 3F. Correcciones de la auditoría (RBAC, IDs, bugs)

Derivadas de la Fase 0 (auditoría integral del CMS):

- **Crash al guardar recursos**: eliminar la columna inexistente `views` de `Resource::$fillable`, `edit()` y `save()` (crear/editar un recurso crashea con `Unknown column 'views'`).
- **Alinear IDs de submódulos BD↔constantes**: reescribir las constantes de `Submodule` para que coincidan con los IDs reales de la BD (PRODUCTS=8, PRODUCT_FAMILIES=9, PRODUCT_BRANDS=10, PRODUCT_LINES=11, SYSTEM_PRODUCTS=12, PRODUCT_PLATFORMS=13, BLOG_CATEGORIES=14, BLOG_ARTICLES=15, TESTIMONIALS=16, CLINICAL_RESOURCES=17, RESOURCE_TYPES=18, RESOURCE_SPECIALTIES=19, CUSTOMER_TYPES=6, DELIVERY_METHODS=7) y añadir las faltantes (COMMERCIAL_REQUESTS=20, WEBSITE_MENU=27, CONTACT_MESSAGES=21, CONTACT_MANAGEMENT=22, CONTACT_FORM_CONFIG=23).
- **Crear en BD lo ausente**: submódulo "Menú del Sitio" (WEBSITE_MENU, bajo M2 Configuración — `MenuController` existe sin registro) y módulo "Contacto" (M7) con CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG. Actualizar `ModuleSeeder` para forzar IDs por constantes en todos los submódulos y re-seed coherente con permisos.
- **Fix RBAC**: `PermissionMiddleware` debe resolver por ID cuando el argumento es numérico (y por nombre si no lo es); corregir las rutas hardcodeadas (`permission:5,1`, `2,3`, `2,4`, `4,1`, `4,2`, `6,1`, `2,1`, `2,2`, dashboard `1`) para usar constantes. Sembrar permisos de submódulo para el rol Editor (rol 2) y verificar RBAC real (editor accede solo a sus módulos, 403 en el resto).
- **Breadcrumbs**: corregir `<x-cms-breadcrumb>` con IDs obsoletos en ~15 vistas CMS usando constantes (payment-methods apunta hoy al módulo 7 inexistente).
- **Menores**: `PaymentMethodController::$paginationTheme` → `'tailwind'`; limpiar fillable muertos (`PaymentMethod`, `BlogCategory`).
- **Blog (M4)**: el CMS del blog funciona pero no hay páginas públicas — documentado como fuera de alcance de este change (decisión de negocio pendiente).

## Capabilities

### New Capabilities
- `cms/sections-editor`: Editor de secciones funcional (arreglo del bug `dd()`) que expone los campos estructurados **ya existentes en BD** (`subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style`) en el modelo y controlador, con editor repeater para JSON, dentro del módulo existente Configuración → Secciones.
- `cms/whatsapp-numbers`: Nuevo submódulo en Configuración para gestionar la tabla `whatsapp_numbers` **ya existente** (número por estado, ejecutivo, estado activo), reemplazando el array `$pickupInfo` hardcodeado en `WebController`.
- `cms/product-attributes`: Nuevo submódulo en Catálogo para gestionar las tablas `attributes`/`attribute_values` **ya existentes** (variantes/dimensiones de producto), reemplazando el selector de dimensiones JS hardcodeado en `producto.blade.php`.
- `cms/contact-messages`: Nuevo submódulo en Contacto para gestionar mensajes del formulario de contacto (tabla nueva `contact_messages`), persistiendo los mensajes en BD además de enviar email — los submódulos 19/20/21 ya existen en `Submodule` pero no tienen controlador.
- `cms/web-content-binding`: Vinculación de todo el contenido hardcodeado de las vistas web públicas con datos gestionados por el CMS, ampliando los módulos existentes (Settings, Sections, Menus, Products, Resources, Categories, Brands, Lines, SystemProducts, ProductPlatforms, ResourceTypes, ResourceSpecialties, Testimonials) — mega menú, redes sociales, misión/visión, about us, CTAs, políticas, opinión, beneficios, pasos del flow, quick cards, aliados, tabs/materiales de caso clínico, selector de dimensiones, imágenes de productos, banners de categorías/marcas/líneas/etc., categorías destacadas, imágenes de testimonios.

### Modified Capabilities
<!-- No existen specs previos (proyecto brownfield adoptando OpenSpec). Todas las capacidades son nuevas. -->

## Impact

### Código afectado
- **Auditoría Fase 0**: revisión de todos los controladores CMS existentes contra la checklist estándar, incluidos los no cubiertos por el resto del plan: `UserController`, `RolController`, `BlogCategoriesController`, `BlogArticlesController`, `PaymentMethodController`, `CustomerTypesController`, `DeliveryMethodsController`, `MenuController`, `DashboardController`, `ProfileController` y auth. Correcciones según hallazgos (bugs, `$fillable`, permisos, registros de submódulos/rutas).
- **Controladores CMS**: `SectionController` (arreglo bug + exponer campos estructurados ya en BD + editor repeater de `items`/`buttons`). **Nuevos**: `WhatsAppNumbersController` (tabla existente), `AttributesController` + `AttributeValuesController` (tablas existentes), `ContactMessagesController` (tabla nueva). Ampliación de `CategoriesController`, `BrandsController`, `LineController`, `SystemProductsController`, `ProductPlatformsController`, `ResourceTypeController`, `ResourceSpecialtyController`, `ResourceController`, `TestimonialsController`, `SettingsController` para gestionar nuevos campos (imagen, banner, SEO keywords, etc.).
- **Modelos**: `Sections` (añadir a `$fillable`: `subtitle`, `description`, `items`, `buttons`, `layout_type`, `icon_style` — columnas ya existentes), `Product` (añadir a `$fillable`: `material`, `is_biomaterial`, `seo_description`, `seo_keywords`, `system_product_id`, `product_platform_id` — columnas ya existentes), `Resource` (quitar `specialty`/`tags` inexistentes; añadir `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results` — requieren migración), `Testimonial` (fillable correcto: `['name', 'specialty', 'content', 'image', 'is_active', 'position']` — quitar `description`/`charge`/`order` inexistentes), `Category` (añadir `seo_keywords`, `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`), `Brand` (añadir `seo_keywords`, `banner_title`, `banner_description`, `banner_image`), `Line` (añadir `image`, `seo_keywords`, `banner_*`), `SystemProduct` (añadir `image`, `seo_keywords`, `banner_*`), `ProductPlatform` (añadir `image`, `seo_keywords`, `banner_*`), `ResourceType` (añadir `image`, `banner_*`), `ResourceSpecialty` (añadir `image` — columna ya existe; `banner_*`), `Settings` (añadir `opinion_url`, `offices` JSON). **Nuevos modelos**: `PageSeo`, `ContactMessage`. Los modelos `Attribute`, `AttributeValue`, `WhatsAppNumber` ya existen.
- **Vistas web**: `home.blade.php`, `nuestra-empresa.blade.php`, `politicas.blade.php`, `recursos-clinicos.blade.php`, `caso-clinico.blade.php`, `producto.blade.php`, `solicitud.blade.php`, `solicitud-enviada.blade.php`, `contactanos.blade.php`, `catalogo.blade.php`, y partials (`header`, `footer`, `beneficios`, `near`, `opinion`, `mobile-nav`, `product-results`, `resource-results`).
- **WebController**: `recursosClinicos()` (eliminar multiplicador ×4 de recursos), `searchProducts()` (usar imágenes reales), `producto()` (añadir eager loading de `attributeValues`), `solicitud()` (reemplazar `$pickupInfo` hardcodeado).
- **Vistas CMS**: `cms/sections/index.blade.php` (editor ampliado con campos estructurados y repeater de items/buttons), nuevas vistas para whatsapp-numbers, attributes, attribute-values, contact-messages. Ampliación de vistas de categories, brands, lines, system-products, product-platforms, resource-types, resource-specialties, resources, testimonials, settings para gestionar nuevos campos.
- **Rutas**: `routes/web.php` — nuevas rutas CMS para whatsapp-numbers (M2), attributes y attribute-values (M3), contact-messages (M7). Corrección de rutas con IDs hardcodeados a constantes (Fase 3G).
- **Migraciones**: **Sin migración para `sections` ni `products`** (las columnas ya existen, solo falta actualizar `$fillable`). Migraciones nuevas para:
  - `settings.opinion_url`
  - `resources`: añadir `content`, `diagnosis`, `gallery`, `video_url`, `materials`, `results`
  - `categories`: añadir `image`, `is_featured`, `banner_title`, `banner_description`, `banner_image`
  - `brands`: añadir `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
  - `lines`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
  - `system_products`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
  - `product_platforms`: añadir `image`, `seo_keywords`, `banner_title`, `banner_description`, `banner_image`
  - `resource_types`: añadir `image`, `banner_title`, `banner_description`, `banner_image`
  - `resource_specialties`: añadir `banner_title`, `banner_description`, `banner_image` (`image` ya existe)
  - `contact_messages`: crear tabla nueva
  - `page_seo`: crear tabla nueva (`page_slug` unique, `seo_title`, `seo_description`, `seo_keywords`, `og_image` nullable)
  - `settings.offices`: añadir columna JSON `offices` (reemplaza `caracas_location`/`valencia_location`/`barquisimeto_location`/`maracay_location`/`maracaibo_location` y sus `*_whatsapp`)
  - Registro de los nuevos submódulos en `submodules` (whatsapp-numbers, attributes, attribute-values, page-seo) si no se insertan por seeder.
  - **Tablas nuevas**: `contact_messages` y `page_seo` (whatsapp_numbers, attributes, attribute_values ya existen).
- **Seeders**: Actualizar `SectionSeeder`, `CategorySeeder`, `BrandSeeder`, `LineSeeder`, `SystemProductSeeder`, `ProductPlatformSeeder`, `ResourceTypeSeeder`, `ResourceSpecialtySeeder`, `ResourceSeeder`, `SettingsSeeder`, `TestimonialSeeder` con los nuevos campos y el contenido hardcodeado actual. Crear `ContactMessageSeeder` (vacío). Verificar `WhatsAppNumberSeeder` y crear `AttributeSeeder`/`AttributeValueSeeder` si no existen.
- **Permisos**: submódulos nuevos en `Submodule` (WhatsApp Numbers y Page SEO en M2, Attributes y Attribute Values en M3, Menú del Sitio en M2, Contacto M7 con CONTACT_MESSAGES/CONTACT_MANAGEMENT/CONTACT_FORM_CONFIG) + asignación al rol administrador por defecto y al rol Editor. Corrección de `PermissionMiddleware` (resolver por ID) y de las rutas con IDs hardcodeados (Fase 3G).

### Dependencias
- No se introducen nuevas dependencias externas; se reutilizan Livewire, Tailwind y las convenciones existentes.

### Riesgos
- **Migración de datos**: Sembrar las secciones existentes y los nuevos campos (`items`, `buttons`, `banner_*`, `materials`, `results`) con el contenido hardcodeado actual para que la web no se vea vacía tras el cambio.
- **Compatibilidad de vistas**: Las vistas deben tener fallbacks graceful mientras se migran los datos.
- **Editor de items JSON**: Requiere un componente repeater en el CMS (Livewire) para editar arrays de items de forma amigable sin que el usuario manipule JSON crudo.
- **Alcance amplio**: El cambio toca prácticamente todos los módulos del CMS y casi todas las vistas web. Por eso se divide en 3 fases aplicables de forma incremental.
