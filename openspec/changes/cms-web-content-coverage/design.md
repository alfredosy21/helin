## Context

Proyecto Laravel + Livewire + Blade brownfield. El CMS vive bajo `/cms` con controladores Livewire y vistas en `cms.*`. La web pública usa `WebController` y vistas en `web.*`. Hay 6 módulos con submódulos y permisos por rol (`rol_id === 1 || level === 1`). La base de datos ya tiene infraestructura que el CMS no expone (migración `2026_06_27_120000` en `sections`, tablas `attributes`/`attribute_values`/`attribute_value_product`, tabla `whatsapp_numbers` con `executive_name`). Varios `$fillable` están desactualizados respecto a las migraciones. Hay un `dd()` en `SectionController::update()` que rompe la edición. La propuesta divide el trabajo en 3 fases; este documento explica las decisiones técnicas de implementación.

## Goals / Non-Goals

**Goals:**
- Que todo el contenido visible en la web pública sea editable desde el CMS sin tocar código.
- Reutilizar la infraestructura existente (tablas, modelos, migraciones) en lugar de crear entidades nuevas.
- Arreglar los bugs críticos (fillable, dd(), relaciones, permisos) con el menor riesgo posible.
- Mantener el sistema funcional tras cada fase.

**Non-Goals:**
- No rediseñar la arquitectura del CMS ni cambiar el sistema de permisos.
- No migrar a otro framework ni introducir nuevas dependencias externas.
- No crear un sistema de bloques/CMS visual nuevo; se reutilizan las secciones existentes.
- No refactorizar el código que no esté relacionado con contenido gestionable.
- No implementar multi-idioma ni versionado de contenido.

## Decisions

### D1: Reutilizar `sections.items` JSON para bloques repetibles (no crear tablas de bloques)
**Decisión**: Modelar beneficios, pasos, aliados, quick cards y features como arrays JSON dentro del campo `items` de las secciones existentes, en lugar de crear una tabla `section_blocks` o `block_items`.

**Rationale**: La migración `2026_06_27_120000` ya añadió `items` JSON y el `SectionSeeder` ya lo puebla con datos reales (hero_badges, etc.). Crear una tabla nueva duplicaría infraestructura y requeriría relaciones adicionales. El JSON es suficiente porque estos bloques son de lectura (la web los muestra, no consulta por ellos).

**Alternativa considerada**: Tabla `section_items` con FK a `sections`. Se descarta por overhead innecesario y porque la estructura ya existe en JSON.

### D2: Editor repeater de items/buttons en Livewire nativo
**Decisión**: Implementar el editor de items y buttons JSON como un componente Livewire con arrays de propiedades públicas, sin depender de paquetes externos tipo Filament Forms.

**Rationale**: El CMS ya usa Livewire nativo en todos los controladores. Añadir Filament Forms introduciría una dependencia nueva y un estilo distinto. El patrón de array repeater en Livewire es simple: `$items = [['icon' => '', 'title' => '', ...]]` con métodos `addItem`, `removeItem`, `reorderItem`.

**Alternativa considerada**: Paquete `filament/forms` o `livewire/json-editor`. Se descartan por dependencia externa y complejidad.

### D3: Banners como columnas en las tablas taxonómicas (no tabla separada)
**Decisión**: Añadir `banner_title`, `banner_description`, `banner_image` directamente a cada tabla taxonómica (`categories`, `brands`, `lines`, `system_products`, `product_platforms`, `resource_types`, `resource_specialties`).

**Rationale**: Cada entidad tiene a lo sumo un banner. Una tabla polimórfica `banners` añadiría complejidad innecesaria. Las columnas son simples de consultar, sembrar y gestionar.

**Alternativa considerada**: Tabla polimórfica `banners` con `bannerable_type`/`bannerable_id`. Se descarta por over-engineering para 1:1.

### D4: `contact_messages` como tabla nueva (única tabla nueva del change)
**Decisión**: Crear la tabla `contact_messages` para persistir los mensajes del formulario de contacto.

**Rationale**: Actualmente `ContactController::send` solo envía email, lo que significa que si el SMTP falla o el email se pierde, no hay registro. Los submódulos 19/20/21 ya existen en `Submodule` pero sin controlador. Es la única tabla nueva del change porque no hay infraestructura existente para mensajes.

**Alternativa considerada**: Reutilizar `commercial_requests` con un flag `type`. Se descarta porque el esquema de solicitudes comerciales es distinto (campos de cliente, método de pago, etc.) y mezclarlos complicaría las consultas.

### D5: Fase 1 sin migraciones para minimizar riesgo
**Decisión**: La Fase 1 (bugs críticos) no crea migraciones. Solo actualiza `$fillable`, elimina `dd()`, corrige relaciones en vistas, añade verificaciones de permisos, unifica paginación, y corrige bugs del `WebController` (multiplicador de recursos, imágenes hardcoded en búsqueda, eager loading faltante).

**Rationale**: Esto permite aplicar Fase 1 rápidamente con riesgo mínimo y sin downtime. Los campos ya existen en BD; el problema es que los modelos no los exponen. Los bugs del `WebController` son correcciones de lógica que no requieren cambios de schema.

### D6: Seeders con el contenido hardcodeado actual
**Decisión**: Tras las migraciones de Fase 3, actualizar los seeders para sembrar los nuevos campos (`banner_*`, `image`, `seo_keywords`, `items`, `buttons`, `materials`, `results`, `opinion_url`) con el contenido que actualmente está hardcodeado en las vistas.

**Rationale**: Si no se siembra, la web se vería vacía tras el cambio (las vistas consumirían campos vacíos en BD). Sembrar con el contenido actual garantiza continuidad visual.

**Alternativa considerada**: Migración con `UPDATE` directo. Se descarta porque los seeders son el mecanismo idiomático de Laravel para datos iniciales y son re-ejecutables.

### D7: Fallbacks graceful en vistas durante la transición
**Decisión**: Las vistas usarán `?? 'fallback'` para los nuevos campos, de modo que si un registro no tiene `banner_title` o `image`, la web no se rompa.

**Rationale**: Durante la transición (entre aplicar migraciones y ejecutar seeders), algunos registros pueden tener campos vacíos. Los fallbacks evitan errores visibles.

### D8: Permisos consistentes con el patrón existente
**Decisión**: Todos los `mount()` de controladores CMS verificarán `if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) abort(403, ...)`.

**Rationale**: Es el patrón que ya usan `CategoriesController`, `BrandsController`, `LineController`, etc. Los controladores de `Resource`, `ResourceSpecialty`, `ResourceType` y `CommercialRequests` no lo tienen y deben alinearse.

### D9: SEO por página estática en tabla `page_seo` (no en Settings ni Sections)
**Decisión**: Crear tabla nueva `page_seo` (`page_slug` unique, `seo_title`, `seo_description`, `seo_keywords`, `og_image`) con un submódulo CMS en Configuración.

**Rationale**: Settings es para valores globales (un solo SEO global), no para SEO por página. Sections es para contenido de secciones, no para metadatos SEO. Una tabla dedicada `page_seo` es simple, consultable por slug, y permite gestionar el SEO de cada página estática de forma independiente. El layout `app.blade.php` hace `PageSeo::where('page_slug', $pageSlug)->first()` con fallback a Settings.

**Alternativa considerada 1**: Campos `home_seo_title`, `home_seo_description`, etc. en Settings. Se descarta porque no escala — cada página nueva requeriría 3 columnas nuevas.
**Alternativa considerada 2**: Reutilizar Sections con campos SEO. Se descarta porque mezcla responsabilidades (contenido de secciones vs metadatos de página) y no todas las páginas tienen una sección 1:1.

### D10: Sedes como JSON `offices` en Settings (no tabla separada)
**Decisión**: Reemplazar los campos individuales `caracas_location`/`valencia_location`/`barquisimeto_location` por un campo JSON `offices` en `Settings` con estructura `[{name, url, active}]`.

**Rationale**: Las sedes son pocos registros (3-5) que solo se muestran en la vista de contacto. Una tabla `offices` sería over-engineering. El JSON es flexible (añadir/quitar sedes sin migración) y se gestiona desde el SettingsController existente. La vista itera el JSON en lugar de tener 3 bloques Blade hardcodeados.

**Alternativa considerada**: Tabla `offices` con CRUD. Se descarta por ser pocos datos y ya existir el patrón de Settings con campos individuales — el JSON es el paso natural.

### D11: Resolución de `page_slug` en el layout mediante `Route::currentRouteName()`
**Decisión**: El layout `app.blade.php` obtiene el slug de la página actual con `Route::currentRouteName()` (ej. `home`, `contactanos`, `nuestra-empresa`, `politicas`, `recursos-clinicos`) y consulta `PageSeo::where('page_slug', $routeName)->first()`. Si no existe registro, usa los valores globales de `Settings` como fallback.

**Rationale**: Laravel expone `Route::currentRouteName()` en cualquier vista sin necesidad de pasar variables desde el controlador. Los nombres de ruta ya existen y son estables (`home`, `catalogo`, `producto`, `contactanos`, `nuestra-empresa`, `politicas`, `recursos-clinicos`, `caso-clinico`, `solicitud`, `solicitud-enviada`). No requiere modificar ningún controlador. La consulta se cachea con `Cache::remember()` o se carga en `View::share()` desde un middleware para evitar una consulta por render.

**Alternativa considerada 1**: Pasar `$pageSlug` desde cada controlador. Se descarta porque requiere modificar todos los controladores web y es propenso a olvidos.
**Alternativa considerada 2**: Usar `request()->path()` en lugar del nombre de ruta. Se descarta porque las rutas pueden cambiar de path sin cambiar de nombre, y los paths tienen prefijos/parámetros (`producto/{slug}`) que no mapean a un slug estable.

### D13: Eliminar multiplicador de recursos en `WebController::recursosClinicos()`
**Decisión**: Eliminar el código que duplica recursos ×4 (`array_fill(0, $multiplier, $r)`) y paginar los recursos reales directamente.

**Rationale**: El multiplicador era un parche temporal para simular más contenido del que existe. Mostrar recursos repetidos 4 veces degrada la experiencia de usuario y infla la paginación artificialmente. Con los seeders actualizados (Fase 3F) habrá suficientes recursos reales. Si hay pocos, la paginación simplemente mostrará menos páginas.

**Alternativa considerada**: Mantener el multiplicador hasta tener más recursos. Se descarta porque es un bug que muestra datos duplicados al usuario final.

### D14: Auto-generación de slug en `ResourceController`
**Decisión**: Añadir auto-generación de slug desde el título en `ResourceController::save()` usando `Str::slug($this->title)`, con un hook `updatedTitle()` que genere el slug en tiempo real como ya hacen otros controladores del CMS.

**Rationale**: El patrón consistente en el resto del CMS es que el slug se genere automáticamente. Requerir que el usuario lo ingrese manualmente es propenso a errores y inconsistencias.

### D15: Eliminar fallback `match()` de políticas en la vista Blade
**Decisión**: Eliminar el `match($section->title)` con HTML hardcoded en `politicas.blade.php` (líneas 28-35) y consumir siempre `$section->content` directamente.

**Rationale**: El `match()` duplica contenido que debería vivir en la BD. Si el contenido de las políticas necesita formato HTML estructurado, debe guardarse como HTML en el campo `content` de la sección, no como un fallback en la vista. El seeder debe encargarse de poblar el contenido correctamente.

### D16: No duplicar testimonios manualmente en el carrusel
**Decisión**: Eliminar la duplicación manual de un testimonio en `home.blade.php` (líneas 381-389) para llenar el carrusel. El carrusel JS debe manejar graceful los casos con pocos elementos (1-3) sin necesidad de duplicar datos.

**Rationale**: Duplicar un testimonio manualmente en la vista es un hack que confunde al usuario (ve el mismo testimonio repetido) y mezcla lógica de presentación con datos. El JS del carrusel ya tiene lógica de clonado para infinito — si necesita más slides, puede clonar visualmente sin que el HTML los duplique.

### D12: Migración de datos de sedes individuales a JSON `offices` dentro de la migración (no solo seeder)
**Decisión**: La migración que añade `offices` a `settings` incluye un paso de migración de datos que lee los valores existentes de `caracas_location`, `valencia_location`, `barquisimeto_location` y los consolida en el JSON `offices` con un `UPDATE`. El método `down()` revierte el JSON a los campos individuales antes de eliminar la columna.

**Rationale**: Si hay datos en producción, el seeder no es suficiente porque los seeders se usan para datos iniciales, no para migrar datos existentes. La migración debe ser idempotente: si los campos individuales ya tienen valores, los migra al JSON; si están vacíos, deja `offices` como null. Esto garantiza que la transición no pierda datos en ningún entorno.

**Estructura del `up()`**:
1. Añadir columna `offices` JSON nullable.
2. Leer el registro único de `settings`.
3. Si `caracas_location` o `valencia_location` o `barquisimeto_location` tienen valor, construir el JSON `[{name: "Caracas", url: "...", active: true}, ...]` y hacer `UPDATE`.
4. (Opcional) Mantener las columnas individuales durante esta migración para rollback seguro; se eliminan en una migración posterior una vez verificado que `offices` funciona.

**Estructura del `down()`**:
1. Leer `offices` JSON.
2. Para cada entrada, restaurar el valor en el campo individual correspondiente (`caracas_location`, etc.).
3. Eliminar la columna `offices`.

## Risks / Trade-offs

- **[Riesgo] Alcance amplio** → Mitigación: División en 3 fases aplicables incrementalmente. Cada fase deja el sistema funcional.
- **[Riesgo] Editor repeater de JSON complejo en Livewire** → Mitigación: Empezar con un esquema simple (array de objetos con campos fijos por `layout_type`). Validar la estructura antes de guardar.
- **[Riesgo] Datos vacíos tras migración** → Mitigación: D6 (seeders con contenido actual) + D7 (fallbacks en vistas).
- **[Riesgo] Performance de consultas con muchos campos** → Mitigación: Las columnas nuevas son strings/text nullable, no afectan índices existentes. Las consultas ya cargan el modelo completo.
- **[Riesgo] Rollback de migraciones** → Mitigación: Todas las migraciones nuevas tendrán método `down()` que elimina las columnas/tablas añadidas.
- **[Trade-off] JSON en `items` no es consultable por SQL** → Aceptable porque los items son de lectura/visualización, no de filtrado. Si en el futuro se necesita filtrar por items, se migraría a tabla separada.

## Migration Plan

1. **Fase 1**: Aplicar cambios de código (fillable, dd(), relaciones, permisos, paginación). Sin migraciones. Deploy y verificar que el CMS existente funciona mejor.
2. **Fase 2**: Aplicar cambios de código (nuevos controladores, consumo de infraestructura existente). Sin migraciones. Deploy y verificar nuevos submódulos.
3. **Fase 3A**: Aplicar migraciones (campos nuevos + tabla `contact_messages`). Ejecutar seeders. Deploy.
4. **Fase 3B-3E**: Aplicar cambios de vistas (reemplazo de hardcodeado por BD), selector dinámico, módulo de mensajes, seeders. Deploy y verificar web pública.

**Rollback**: Cada fase es independiente. Si Fase 3 falla, se hace rollback de las migraciones (`php artisan migrate:rollback`) y se restauran las vistas a la versión anterior. Fase 1 y 2 no tienen migraciones, por lo que su rollback es solo revertir código.

## Open Questions

Resueltas con el usuario:
- **Banners de marcas/líneas/sistemas/plataformas**: son para uso futuro. Los campos se añaden en Fase 3A pero NO se modifican vistas en 3C para consumirlos (solo categorías tienen banner consumido por el catálogo hoy).
- **Formulario de contacto**: ambos — guardar en BD Y enviar email.
