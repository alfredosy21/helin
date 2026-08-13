## Purpose

Vincula todo el contenido hardcodeado de las vistas web públicas con datos gestionados por el CMS, ampliando los módulos existentes (Settings, Sections, Menus, Products, Resources, Categories, Brands, Lines, SystemProducts, ProductPlatforms, ResourceTypes, ResourceSpecialties, Testimonials) para que todo el contenido público sea editable sin tocar código.

## ADDED Requirements

### Requirement: Redes sociales desde Settings
El footer y header de la web pública SHALL mostrar los enlaces a redes sociales (Facebook, Instagram, LinkedIn, YouTube) desde `Settings`, no desde literales en Blade.

#### Scenario: Cambiar URL de Facebook
- **WHEN** el administrador cambia la URL de Facebook en Settings
- **THEN** el footer y header de la web muestran el nuevo enlace

### Requirement: Mega menú dinámico
El header de la web pública SHALL generar el mega menú de navegación dinámicamente desde las categorías activas y ordenadas, no desde una estructura hardcodeada.

#### Scenario: Añadir categoría al menú
- **WHEN** el administrador crea una nueva categoría activa
- **THEN** la categoría aparece en el mega menú de la web pública

### Requirement: Categorías destacadas del home
El home SHALL mostrar las categorías destacadas desde `Category::where('is_featured', true)` ordenadas, no desde un grid hardcodeado.

#### Scenario: Marcar categoría como destacada
- **WHEN** el administrador marca una categoría como destacada y le asigna una imagen
- **THEN** la categoría aparece en el grid de categorías destacadas del home

### Requirement: Banner de categoría dinámico
El catálogo SHALL mostrar el banner de cada categoría desde los campos `banner_title`, `banner_description` y `banner_image` de la categoría, no desde un array PHP hardcodeado.

#### Scenario: Editar banner de categoría
- **WHEN** el administrador edita el banner de una categoría desde el CMS
- **THEN** el catálogo muestra el nuevo banner al filtrar por esa categoría

#### Scenario: Categoría sin banner
- **WHEN** una categoría no tiene `banner_image`
- **THEN** el catálogo muestra un gradiente por defecto

### Requirement: Banners de marcas, líneas, sistemas, plataformas, tipos y especialidades
Las entidades taxonómicas (marcas, líneas, sistemas, plataformas, tipos de recursos, especialidades) SHALL tener campos `banner_title`, `banner_description` y `banner_image` gestionables desde el CMS.

#### Scenario: Editar banner de marca
- **WHEN** el administrador edita el banner de una marca
- **THEN** el cambio se persiste y está disponible para las vistas

### Requirement: Imagen y SEO keywords en entidades taxonómicas
Todas las entidades taxonómicas (categorías, marcas, líneas, sistemas, plataformas, tipos, especialidades) SHALL tener campos `image` y `seo_keywords` gestionables desde el CMS.

#### Scenario: Subir imagen de categoría
- **WHEN** el administrador sube una imagen para una categoría
- **THEN** la imagen se persiste y puede usarse en el home o catálogo

### Requirement: Contenido corporativo desde secciones
Las vistas de Nuestra Empresa y Políticas SHALL consumir el contenido de las secciones predefinidas (`MISSION_VISION`, `ABOUT_US`, `CTA_HOME`, `CTA_COMPANY`, `SHIPPING_POLICIES`, `TERMS_CONDITIONS`, `PRIVACY_POLICIES`) en lugar de texto hardcodeado.

#### Scenario: Editar misión
- **WHEN** el administrador edita el contenido de la sección `MISSION_VISION`
- **THEN** la vista de Nuestra Empresa muestra el nuevo texto

#### Scenario: Editar políticas de envío
- **WHEN** el administrador edita la sección `SHIPPING_POLICIES`
- **THEN** la vista de Políticas muestra el nuevo contenido sin fallback hardcodeado

### Requirement: Bloques de items JSON consumidos por las vistas
Las vistas públicas SHALL consumir los bloques (beneficios, pasos del flow, quick cards, features de About Us, aliados/logos) desde el campo `items` JSON de las secciones correspondientes.

#### Scenario: Editar beneficios
- **WHEN** el administrador edita los items de la sección de beneficios
- **THEN** el partial `beneficios.blade.php` muestra los nuevos items

#### Scenario: Editar aliados
- **WHEN** el administrador edita los items de la sección de aliados
- **THEN** la vista de Nuestra Empresa muestra los nuevos logos

### Requirement: Sección de opinión desde Settings
El enlace del banner de opinión SHALL provenir de `Settings::opinion_url`, no de un literal hardcodeado.

#### Scenario: Cambiar URL de opinión
- **WHEN** el administrador cambia `opinion_url` en Settings
- **THEN** el banner de opinión de la web usa el nuevo enlace

### Requirement: Imágenes reales de productos
Las vistas de producto y catálogo SHALL mostrar las imágenes reales de los productos (`Product::mainImageUrl`, `images()`) en lugar del pool estático `im1.png`–`im6.png`.

#### Scenario: Producto con imagen principal
- **WHEN** un usuario visita la ficha de un producto con imagen principal
- **THEN** la vista muestra la imagen almacenada, no `im1.png`

#### Scenario: Producto con galería
- **WHEN** un usuario visita la ficha de un producto con múltiples imágenes
- **THEN** la galería muestra las imágenes asociadas al producto

### Requirement: Productos relacionados dinámicos
La ficha de producto SHALL mostrar productos relacionados desde `$relatedProducts` que el controlador pasa, no desde tarjetas hardcodeadas.

#### Scenario: Ver productos relacionados
- **WHEN** un usuario visita la ficha de un producto con relacionados
- **THEN** la vista muestra los productos relacionados dinámicos

### Requirement: Imagen de testimonio real
El carrusel de testimonios del home SHALL mostrar la imagen almacenada en `$testimonial->image`, no imágenes hardcodeadas basadas en el nombre del autor. El carrusel SHALL manejar graceful los casos con pocos testimonios sin duplicar datos manualmente en la vista.

#### Scenario: Testimonio con imagen
- **WHEN** un testimonio tiene imagen subida desde el CMS
- **THEN** el home muestra esa imagen

#### Scenario: Testimonio sin imagen
- **WHEN** un testimonio no tiene imagen
- **THEN** el home muestra una imagen placeholder por defecto

#### Scenario: Pocos testimonios en el carrusel
- **WHEN** hay menos de 4 testimonios activos
- **THEN** el carrusel no duplica manualmente ningún testimonio para llenar slides

### Requirement: Contenido enriquecido de casos clínicos
La vista de caso clínico SHALL mostrar el contenido detallado, diagnóstico, galería, video, materiales, resultados y descripción desde los campos de `Resource`, no desde literales hardcodeados.

#### Scenario: Editar materiales de un caso
- **WHEN** el administrador edita los materiales de un recurso tipo caso clínico
- **THEN** la vista de caso clínico muestra los nuevos materiales

#### Scenario: Editar descripción de un caso
- **WHEN** el administrador edita el campo `content` de un recurso tipo caso clínico
- **THEN** la vista de caso clínico muestra el nuevo contenido en el párrafo de descripción

#### Scenario: Relaciones correctas en caso clínico
- **WHEN** la vista de caso clínico muestra la especialidad y el tipo
- **THEN** usa `$resource->resourceSpecialty->name` y `$resource->resourceType->name`

### Requirement: Sección "Estamos cerca de ti" dinámica
El partial "Estamos cerca de ti" SHALL consumir el texto desde una sección y los datos de sedes desde `Settings`/`WhatsAppNumber`, no desde literales hardcodeados.

#### Scenario: Cambiar texto de la sección
- **WHEN** el administrador edita la sección correspondiente
- **THEN** el partial muestra el nuevo texto

### Requirement: Página de solicitud enviada sin datos falsos
La vista de solicitud enviada SHALL mostrar los datos reales de la solicitud y no usar datos de ejemplo hardcodeados como fallback.

#### Scenario: Solicitud con datos reales
- **WHEN** un usuario llega a la página de solicitud enviada con un UUID válido
- **THEN** la vista muestra los productos reales del carrito, los datos reales del cliente y el WhatsApp real desde `WhatsAppNumber`/`Settings`

#### Scenario: Solicitud sin items en el carrito
- **WHEN** no hay items en el carrito
- **THEN** la vista muestra un mensaje indicando que no hay productos, no productos falsos hardcodeados

### Requirement: Recursos clínicos sin duplicación artificial
La página de recursos clínicos SHALL mostrar los recursos reales paginados sin multiplicar artificialmente el número de resultados.

#### Scenario: Lista de recursos clínicos
- **WHEN** un usuario visita la página de recursos clínicos
- **THEN** cada recurso aparece una sola vez en la paginación, sin duplicación por multiplicador

### Requirement: Búsqueda de productos con imágenes reales
La búsqueda AJAX de productos SHALL mostrar las imágenes reales de cada producto, no un pool estático de imágenes.

#### Scenario: Buscar un producto
- **WHEN** un usuario busca un producto desde el header o el catálogo
- **THEN** los resultados muestran la imagen real del producto, no `im1.png`–`im6.png`

### Requirement: Productos fake del home eliminados
El home SHALL mostrar productos reales del catálogo en todas las secciones de productos destacados, no productos con nombres, imágenes y precios inventados.

#### Scenario: Sección de Instrumentos y Equipos
- **WHEN** un usuario visita el home y ve la sección de productos destacados
- **THEN** los productos mostrados son reales del catálogo, no placeholders con nombres fake

### Requirement: Imagen del team desde CMS
La vista de Nuestra Empresa SHALL mostrar la imagen del team desde `$teamSection->image`, no desde un literal hardcodeado.

#### Scenario: Cambiar foto del team
- **WHEN** el administrador sube una nueva imagen para la sección del team
- **THEN** la vista de Nuestra Empresa muestra la nueva imagen

### Requirement: WhatsApp de CTAs desde BD
Los CTAs de Nuestra Empresa y otras vistas que incluyen enlaces de WhatsApp SHALL obtener el número desde `WhatsAppNumber`/`Settings`, no desde literales hardcodeados.

#### Scenario: Cambiar número de WhatsApp del CTA
- **WHEN** el administrador cambia el número de WhatsApp en Settings o WhatsAppNumber
- **THEN** los CTAs de Nuestra Empresa y otras vistas usan el nuevo número

### Requirement: SEO por página estática gestionable
El título, meta description y meta keywords de cada página estática (home, contacto, nuestra-empresa, políticas, recursos-clinicos) SHALL provenir de la tabla `page_seo`, consultada por `page_slug` usando `Route::currentRouteName()` como slug, con fallback a `Settings` globales. No se usarán literales hardcodeados en Blade.

#### Scenario: Editar SEO de la página de contacto
- **WHEN** el administrador edita el SEO de la página con slug `contactanos` (nombre de la ruta) desde el submódulo Page SEO
- **THEN** la meta description y keywords de la página `/contactanos` se actualizan

#### Scenario: Página sin SEO específico
- **WHEN** una página no tiene registro en `page_seo` para su nombre de ruta
- **THEN** el layout usa los valores globales de `Settings` como fallback

#### Scenario: Página dinámica (producto, caso clínico)
- **WHEN** la página es dinámica (ej. `/producto/{slug}`)
- **THEN** el SEO proviene del propio modelo (Product::seo_description, Resource::seo si aplica) con fallback a `page_seo` por nombre de ruta y luego a `Settings`

### Requirement: Sedes dinámicas en contacto
La vista de contacto SHALL mostrar las sedes dinámicamente iterando el campo JSON `offices` de `Settings` (estructura `[{name, url, active}]`), no con nombres hardcodeados en Blade. Esto incluye todas las sedes que tengan `active: true`, sin limitarse a un subconjunto fijo.

#### Scenario: Añadir una sede nueva
- **WHEN** el administrador añade una entrada `{name: "Maracay", url: "...", active: true}` al JSON `offices` en Settings
- **THEN** la vista de contacto muestra "Maracay" como sede sin editar código

#### Scenario: Quitar una sede
- **WHEN** el administrador marca una sede como `active: false` o la elimina del JSON
- **THEN** la vista de contacto deja de mostrar esa sede

#### Scenario: Sede sin URL
- **WHEN** una sede tiene `active: true` pero `url` vacío
- **THEN** la vista muestra el nombre de la sede sin enlace

### Requirement: Políticas sin fallback hardcodeado en Blade
La vista de políticas SHALL consumir siempre el contenido de `$section->content` desde la BD, sin un `match()` con HTML hardcodeado como fallback.

#### Scenario: Editar contenido de políticas
- **WHEN** el administrador edita el contenido HTML de una sección de políticas
- **THEN** la vista de políticas muestra el contenido exacto de la BD, sin fallback hardcodeado
