# HELIN LATAM - B2B MEDICAL ECOMMERCE CMS

## SYSTEM ARCHITECTURE & DEVELOPMENT STANDARDS (2026)

Este documento define la verdad absoluta para el desarrollo del panel administrativo de Helin Latam. Cualquier código generado debe adherirse estrictamente a estas reglas.

---
## 🛒 ¿Qué estamos construyendo?
Estamos desarrollando un **Ecommerce especializado** para la industria médica y dental. A diferencia de un ecommerce tradicional de consumo masivo, Helin Latam funciona como un **Catálogo Digital de Alta Gama** centrado en la generación de **Cotizaciones Estratégicas**.

## 🏗️ Fase Actual: Panel Administrativo (CMS)
En este momento, nos enfocamos exclusivamente en la construcción del **Panel Administrativo (Backend/CMS)**. Este panel es el cerebro que permitirá gestionar:
- El inventario técnico de productos.
- Las jerarquías de categorías clínicas.
- El procesamiento de las solicitudes de cotización.
- El contenido corporativo (testimonios, blog médico, red de distribuidores).

---

### 🏗️ 2. ESTRUCTURA DE DIRECTORIOS Y DESACOPLAMIENTO
Se prohíbe la lógica dispersa. El proyecto sigue un patrón de servicios y utilidades:

- **Utils (`app/Utils/`)**: 
    - `Messages.php`: Constantes de texto. Prohibido hardcodear strings.
    - `Helpers.php`: Funciones globales estáticas.
- **Services (`app/Services/`)**:
    - `FileUpload.php`: Único punto de entrada para archivos.
    - `CustomMail.php`: Gestión de envíos.
- **Arquitectura Laravel 11**:
    - **Models**: Fat Models (Casts, Scopes, Accessors).
    - **Controllers**: Thin Controllers (Solo coordinan el flujo).
    - **Livewire**: Form Objects para validación y estado.

#### **Estándares de Seguridad y Validación (Middleware & Requests):**

- **Form Requests (`App\Http\Requests`):** - Se deben utilizar para validaciones complejas. 
    - Estructura: `authorize()` retorna `true` por defecto; `rules()` gestiona la lógica de validación (Ej: `exists:App\Models\Makes,id`).
  
- **Middlewares de Acceso (`App\Http\Middleware`):**
    - **AdminMiddleware**: Verifica si el usuario es `guest` y redirecciona a `cms` si no está autenticado bajo el guard `web`.
    - **RoleMiddleware**: 
        - Gestiona permisos por sub-módulos. 
        - Si el usuario tiene `level == '2'`, consulta la tabla `Permission` filtrando por `rol_id` y `submodule_id`. 
        - Si no tiene permisos, redirecciona a `dashboard`.
    - **Uso en Rutas:** `->middleware('role:ID_SUBMODULO')`.


# 🧠 HELIN LATAM: BACKEND LOGIC, SECURITY & ROUTING STANDARDS

Este documento establece las reglas de oro para la construcción de controladores, la seguridad de rutas y la arquitectura de validación en Laravel 11.

---

### 🎮 1. ARQUITECTURA DE CONTROLADORES
- **Estándar:** Utilizar Resource Controllers.
- **Manejo de Errores:** Todos los métodos deben estar envueltos en bloques `try-catch`.
    - Usar `report($ex)` para el log de errores.
    - Retornar `false` o redireccionar con mensaje de error en caso de fallo.
- **Auditoría:** Uso obligatorio de `Activities::saveActivity()` para registrar acciones relevantes (Ej: `'Actualización de cotización ID #5'`).
- **Notificaciones:** Usar `Session()->flash()`:
    - `'notice'`: Para éxitos.
    - `'warning'`: Para cambios importantes o errores.
- **Acceso:** Definir el control de acceso en el `__construct` mediante middlewares.

---

### 🛡️ 2. VALIDACIÓN: FORM REQUESTS
- **Ubicación:** Todo archivo de validación DEBE residir en `app/Http/Requests/`.
- **Inyección:** Inyectar la clase Request específica en los métodos del controlador (Ej: `store(ProductRequest $request)`).
- **Reglas Dinámicas:** Manejar la exclusión de ID en reglas de unicidad durante actualizaciones:
  ```php
  'email' => 'sometimes|required|email|unique:users,email,' . $this->id


---

### ⚡ 3. SPA & REACTIVIDAD (STRICT RULES)
El CMS debe sentirse como una aplicación nativa (Estilo Linear/Stripe).

- **Navegación:** Uso obligatorio de `wire:navigate` en todos los links internos.
- **Formularios:** Uso obligatorio de `wire:submit.prevent`.
- **Interacciones:** Alpine.js para micro-interacciones rápidas (dropdowns, toggles, modales).
- **Feedback:** 
    - `wire:loading` obligatorio en cada acción asíncrona (botones, filtros, búsquedas).
    - Skeleton loaders para estados de carga inicial en cada módulo.
    - **Cero Parpadeo:** Prohibido el refresco total de pantalla o parpadeos blancos (flickering).

#### **Listados y Tablas SPA:**
Todos los listados de los módulos (**Productos, Cotizaciones, Testimonios, Categorías y Recursos**) deben implementar:
- **Paginación Asíncrona:** Uso de `WithPagination` de Livewire sin recarga de página.
- **Búsqueda Instantánea:** `wire:model.live.debounce.500ms` para filtrar datos en tiempo real.
- **Persistencia de Filtros:** Los filtros y la página actual deben persistir en la URL mediante `$queryString` para permitir el uso de los botones "atrás" y "adelante" del navegador sin perder el estado de la búsqueda.
- **Scroll Management:** Mantener la posición del scroll tras las actualizaciones de Livewire.


---

### 🎨 4. DISEÑO ORGÁNICO Y UI/UX
- **Estética:** Minimalismo premium, mucho whitespace.
- **Bordes:** `rounded-3xl` para contenedores, `rounded-2xl` para elementos pequeños.
- **Sombras:** `shadow-lg shadow-black/5` (Sombras suaves y orgánicas).
- **Componentes Blade:** Uso de `<x-button>`, `<x-input>`, `<x-card>`, etc.
- **Estados de Botón:** Hover (`scale-[1.02]`), Active (`scale-[0.98]`), Loading (Spinner) y Disabled.
- **Anti-Robótico:** Sin bordes agresivos, sin anillos de enfoque por defecto. Usar anillos suaves (`focus:ring-accent/10`).
- **Tematización:** Usar variables CSS mapeadas en `tailwind.config.js` para facilitar el intercambio de colores.


---

### 📂 5. GESTIÓN DE MEDIA (DROPZONE STYLE)
Los uploads deben ser modernos:
- **UX:** Drag & Drop con preview instantáneo y barra de progreso.
- **Validación:** MIME (jpg, png, webp, pdf), tamaño y dimensiones.
- **Backend:** Siempre invocar a `FileUpload::save()`.

---

### 🗑️ 6. ESTÁNDAR DE ELIMINACIÓN
Queda terminantemente prohibido el uso de `confirm()` nativo.
- **Modal:** SweetAlert2.
- **Título:** `¡Cuidado!`.
- **Texto:** `¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.`
- **Feedback:** Toastify al completar la acción: `Registro eliminado correctamente`.

---

### 🔔 7. SISTEMA DE NOTIFICACIONES (TOASTIFY)
Todas las operaciones CRUD deben notificar al usuario mediante un Toast (Toastify) en la esquina superior derecha (`top-right`).

#### **Reglas de Feedback:**
1. **Persistencia:** Los toasts deben desaparecer automáticamente tras 3 segundos.
2. **Contexto:** Se debe usar el color correspondiente al tipo de acción (Success para éxito, Danger para errores, Warning para alertas).
3. **Origen:** Los mensajes deben provenir exclusivamente de la clase `app/Utils/Messages.php`.

#### **Mensajes Estándar por Acción:**
- **Creación:** `Registro guardado correctamente`.
- **Actualización:** `Cambios actualizados con éxito`.
- **Eliminación:** `Registro eliminado correctamente`.
- **Error:** `Ocurrió un error al procesar la solicitud`.
- **Validación:** `Por favor, revisa los errores en el formulario`.

#### **Implementación Técnica:**
En el componente Livewire, después de la lógica de negocio, se debe disparar el evento:
```php
$this->dispatch('toast', 
    message: Messages::LABEL_SUCCESS_UPDATE, 
    type: 'success'
);

### 🏗️ ESTRUCTURA DEL LAYOUT MAESTRO (APP TEMPLATE)
1. **Sidebar Dinámico (Izquierda):**
    - El menú debe renderizarse dinámicamente consultando el modelo `Module`.
    - **Lógica de Renderizado:**
      ```php
      @php $modules = \App\Models\Module::getModules(); @endphp
      @foreach ($modules as $module)
          <li class="dropdown" x-data="{ open: false }">
              <a @click="open = !open" class="nav-link menu-title" href="#">
                  <i data-feather="{{ $module['class'] }}"></i>
                  <span>{{ $module['name'] }}</span>
              </a>
              <ul class="nav-submenu menu-content" x-show="open" x-transition>
                  @foreach ($module['submodules'] as $submodule)
                      <li>
                          <a href="{{ url($submodule['url']) }}" wire:navigate>
                              {{ $submodule['name'] }}
                          </a>
                      </li>
                  @endforeach
              </ul>
          </li>
      @endforeach
      ```
    - Usa **Lucide Icons** (o Feather según la clase del módulo) y asegura que los enlaces usen `wire:navigate`.

2. **Header Minimalista (Superior):**
    - **Izquierda:** Logo de Helin.
    - **Derecha:** Nombre del usuario y avatar circular con un **Dropdown de Alpine.js**.
    - **Opciones del Dropdown:** 
        - "Actualizar Perfil" (enlace con `wire:navigate`).
        - "Cerrar Sesión": Un enlace directo **GET** (`<a>`) que apunte a la ruta de logout.
    - **Estilo:** `sticky top-0`, `z-50`, fondo con leve transparencia (glassmorphism) y borde inferior sutil.

3. **Contenido Central e Inyectado:**
    - Zona principal usando `@yield('content')` o el slot de Livewire.
    - **Skeleton Loadings:** Implementar obligatoriamente filas fantasma con efecto pulso que se activen durante búsquedas, filtrados o cambios de página en los listados.

4. **Footer:**
    - Template estático al final con créditos "Helin Specialist Access" y año actual.

### 🛡️ REGLAS DE ORO TRANSVERSALES (OBLIGATORIAS)
1. **Prevención de Duplicados:** No permitir registros duplicados. Implementar validaciones `unique` en base de datos y formularios (Slugs, SKUs, Emails y Nombres). Verificar existencia antes de procesar.
2. **Estados de Carga en Botones:** Al hacer clic en "Guardar", "Ingresar" o "Actualizar":
    - Deshabilitar el botón automáticamente (`wire:loading.attr="disabled"`).
    - Mostrar un spinner o texto de carga dentro del botón (`wire:loading`).
3. **Skeletons y Estados de Carga en Listados:** 
    - Mientras se realiza una búsqueda o se cambia de página en el paginador, el listado debe mostrar un **Skeleton Loading** (filas fantasma con efecto de pulso) para evitar saltos visuales.
    - El paginador también debe mostrar un estado de carga o deshabilitarse mientras se procesa la nueva página.
4. **Navegación SPA:** Uso estricto de `wire:navigate` para una navegación instantánea.
5. **Validación Real-time:** Errores en español al instante debajo de cada input usando `wire:model.live`.

---
### 📦 DETALLE DE MÓDULOS

### 1. MÓDULO: AUTENTICACIÓN Y RECUPERACIÓN
- **Login:** Card flotante, `rounded-3xl`, logo de Helin. Error: "Credenciales incorrectas". Botón con estado de carga. , el login debe tener un enlace para recuperar contraseña
- **Recuperación:** Formulario de envío de link con validación de existencia de correo. el formulario debe tener un enlace para volver al login
- Mantener las buenas prácticas con el gestion de contraseñas
- Agregar toast indicando el error que tiene el usuario
- Se debe hacer la logica de recuperación de contraseña

### 2. MÓDULO: PERFIL (ACCOUNT MANAGEMENT)
- Actualización de Nombre, Email y Contraseña (opcional). 
- Validación de fuerza de clave y formato de email. Toast: "Datos actualizados con éxito".
- Mantener las buenas prácticas con el gestion de contraseñas

### 3. MÓDULO: DASHBOARD (HOME)
- Bienvendido [Nombre de usuario].
- **Grid de Estadísticas (x4):** Cotizaciones pendientes, Total productos, Recursos clínicos y Crecimiento de clientes.
- Lista de actividad reciente (últimas 5 cotizaciones).

#### 4. PRODUCTOS Y CATEGORÍAS (THE CORE)

##### **📦 Categorías de Productos (categories)**
**Migración:** `2026_05_07_001240_create_categories_table.php`
```sql
-- Campos principales
id, name, slug, description, parent_id, is_active, order
-- Campos visuales
image, icon, color
-- Índices
fullText([name, description]), index(['is_active', 'is_featured'])
```
**Características:**
- Sistema jerárquico con `parent_id` auto-referencia
- Posicionamiento manual con campo `order`
- Personalización visual: imagen, icono, color
- Estados: activo/inactivo
- Full-text search optimizado

##### **🛍️ Productos (products)**
**Migración:** `2026_05_07_001259_create_products_table.php`
```sql
-- Campos básicos
id, name, slug, sku, description, clinical_specs
-- Relaciones
category_id (FK), brand_id (FK)
-- Comerciales
price, currency, stock, unit
-- Promociones
sale_price, sale_start_date, sale_end_date, is_on_sale
-- Estados
is_active, is_featured, is_new
-- SEO
meta_title, meta_description, meta_keywords
-- Analytics
view_count, search_count, rating, review_count
-- Publicación
published_at
```
**Características:**
- SKU único para identificación
- Relación con categorías y marcas
- Sistema de precios y promociones
- Contadores de engagement
- SEO completo con meta tags
- Full-text search en nombre, SKU, descripción y especificaciones
- Índices compuestos para filtros optimizados

##### **🏢 Marcas (brands)**
**Migración:** `2026_05_07_001266_create_brands_table.php`
```sql
-- Campos básicos
id, name, slug, description
-- SEO
meta_title, meta_description, meta_keywords
-- Control
is_active, is_featured, position, product_count
-- Índices
fullText([name, description]), index(['is_active', 'is_featured'])
```
**Características:**
- Nombre y slug únicos
- SEO optimizado
- Posicionamiento manual
- Contador cache de productos
- Estados de activación y destacado

##### **🖼️ Media de Productos (product_media)**
**Migración:** `2026_05_08_001129_create_product_media_table.php`
```sql
-- Relación
product_id (FK → products.id)
-- Archivo
file_path, file_name, mime_type, file_size
-- Tipo y metadatos
type (image/document/video), alt_text, title, label
-- Control
is_main, is_featured, position, is_active
-- Optimización
thumbnail, description
```
**Características:**
- Sistema de gestión de imágenes y documentos
- Tipos: image, document, video
- SEO con alt text y títulos
- Imagen principal y destacadas
- Thumbnails para optimización
- Posicionamiento manual

##### **🏷️ Atributos Dinámicos (attributes)**
**Migración:** `2026_05_08_001103_create_attributes_table.php`
```sql
-- Campos básicos
id, name, slug, description, type, unit
-- Configuración
options (JSON), is_required, is_filterable
-- Control
position, is_active
-- Índices
fullText([name, description]), index(['is_active', 'position'])
```
**Características:**
- Tipos: text, number, select, boolean
- Unidades y opciones personalizadas
- Validación y filtros automáticos
- Posicionamiento manual

##### **🎯 Valores de Atributos (attribute_values)**
**Migración:** `2026_05_08_001109_create_attribute_values_table.php`
```sql
-- Relación
attribute_id (FK → attributes.id)
-- Valores
value, label, description, color
-- Control
position, is_active
-- Índices
fullText([value, label, description]), index(['attribute_id', 'position'])
```
**Características:**
- Valores específicos para cada atributo
- Etiquetas y descripciones amigables
- Personalización visual con colores
- Posicionamiento manual

##### **🔗 Relación Productos-Atributos (attribute_value_product)**
**Migración:** `2026_05_08_001118_create_attribute_value_product_table.php`
```sql
-- Relaciones
product_id (FK → products.id)
attribute_value_id (FK → attribute_values.id)
-- Datos adicionales
notes, numeric_value, text_value
-- Índices
unique([product_id, attribute_value_id])
```
**Características:**
- Relación many-to-many productos ↔ valores de atributos
- Datos adicionales por producto
- Constraint único para evitar duplicados

##### **🔗 Relaciones Completas:**
- **Products → Categories** (Muchos a Uno)
- **Products → Brands** (Muchos a Uno)  
- **Products → ProductMedia** (Uno a Muchos)
- **Products → AttributeValues** (Muchos a Muchos)
- **Categories → Categories** (Auto-referencia jerárquica)
- **Brands → Products** (Uno a Muchos)
- **Attributes → AttributeValues** (Uno a Muchos)
- **AttributeValues → Products** (Muchos a Muchos)

##### **✅ Validaciones y Índices:**
- SKU único, slug único
- Categorías y marcas existentes
- Full-text search optimizado
- Índices compuestos para filtros de rendimiento
- Foreign keys con cascade y set null

#### 5. BLOG Y CONTENIDO MÉDICO

##### **📝 Categorías de Blog (blog_categories)**
**Migración:** `2026_05_07_001265_create_blog_categories_table.php`
```sql
-- Campos básicos
id, name, slug, description
-- Visuales
color, icon, image
-- Control
is_active, order
-- Índices
index(['is_active', 'order'])
```
**Características:**
- Nombre y slug únicos
- Personalización visual con colores e iconos
- Posicionamiento manual
- Estados de activación

##### **📰 Blogs (blogs)**
**Migración:** `2026_05_07_001264_create_blogs_table.php`
```sql
-- Campos básicos
id, title, slug, author, content
-- Media
featured_image
-- SEO
meta_title, meta_description, meta_keywords
-- Relación
blog_category_id (FK → blog_categories.id)
-- Engagement
view_count, like_count, comment_count, share_count
-- Estados
is_active, is_featured, is_pinned
-- Publicación
read_time, published_at
-- Índices
fullText(['title', 'content']), fullText(['title', 'excerpt', 'content'])
index(['is_active', 'is_featured']), index(['is_active', 'published_at'])
```
**Características:**
- Título y slug únicos
- SEO completo con meta tags
- Contadores de engagement
- Estados de publicación y destacado
- Full-text search optimizado
- Fecha de publicación programada

##### **🖼️ Galerías de Blog (blog_galleries)**
**Migración:** `2026_05_07_001267_create_blog_galleries_table.php`
```sql
-- Relación
blog_id (FK → blogs.id)
-- Archivo
file_path, file_name, mime_type, file_size
-- Metadatos
title, alt_text, description, thumbnail
-- Control
is_featured, position, is_active
-- Índices
index(['blog_id', 'position']), index(['blog_id', 'is_featured'])
```
**Características:**
- Sistema de gestión de imágenes separado
- SEO con alt text y títulos
- Imágenes destacadas y posicionamiento
- Thumbnails para optimización

##### **🔗 Relaciones Completas:**
- **Blogs → BlogCategories** (Muchos a Uno)
- **Blogs → BlogGalleries** (Uno a Muchos)
- **BlogCategories → Blogs** (Uno a Muchos)

##### **✅ Validaciones y Índices:**
- Título y slug únicos
- Categorías existentes
- Full-text search en título y contenido
- Índices compuestos para filtros de estado y categoría

#### 6. COTIZACIONES (SALES ENGINE)

##### **📋 Cotizaciones (quotes)**
**Migración:** `2026_05_07_002923_create_quotes_table.php`
```sql
-- Identificación
reference_number (único)
-- Cliente
customer_name, customer_email, customer_phone
-- Especialidad
specialty
-- Control
notes, status (pending/sent/cancelled)
-- Timestamps
created_at, updated_at
```
**Características:**
- Reference number único para seguimiento
- Información completa del cliente
- Estados de cotización
- Notas adicionales

##### **📦 Items de Cotización (quote_items)**
**Migración:** `2026_05_07_002951_create_quote_items_table.php`
```sql
-- Relaciones
quote_id (FK → quotes.id), product_id (FK → products.id)
-- Comerciales
quantity, unit_price (snapshot)
-- Timestamps
created_at, updated_at
```
**Características:**
- Relación con productos y cotizaciones
- Snapshot de precios al momento de cotización
- Cantidades flexibles

##### **🔗 Relaciones Completas:**
- **Quotes → QuoteItems** (Uno a Muchos)
- **Products → QuoteItems** (Muchos a Muchos)

##### **✅ Funcionalidades Adicionales:**
- Exportación a Excel para análisis comercial
- Integración WhatsApp con productos formateados
- Seguimiento de estado de cotizaciones

#### 7. GESTIÓN DE CONTENIDO AUTÓNOMO
- **Banners:** El equipo de Helin podrá actualizar banners y catálogo sin depender de la agencia.
- **Catálogo:** Gestión completa de productos, categorías y recursos clínicos desde el panel.
- **Contenido Corporativo:** Actualización de testimonios, blogs y distribuidores de forma autónoma.

#### 8. CARGA MASIVA DE DATOS (IMPORTACIÓN CSV)
- **Importación de Productos:**
  - Upload de archivo CSV con formato predefinido
  - Validación de columnas obligatorias: name, sku, category_id, brand_id, description
  - Mapeo de columnas personalizables
  - Preview de datos antes de importar
  - Detección de duplicados por SKU
  - Importación de atributos y media asociados
  - Reporte de importación con éxitos y errores
  - Soporte para actualización de productos existentes
- **Importación de Categorías:**
  - Upload de archivo CSV con estructura jerárquica
  - Validación de nombres únicos
  - Creación automática de parent_id según jerarquía
  - Soporte para múltiples niveles de anidamiento
  - Importación de imágenes, iconos y colores
- **Importación de Marcas:**
  - Upload de archivo CSV con información de marcas
  - Validación de nombres únicos
  - Importación de logos y datos de contacto
- **Proceso de Importación:**
  - Validación de estructura CSV
  - Sanitización de datos
  - Transacciones database para rollback en errores
  - Notificaciones de progreso en tiempo real
  - Log detallado de operaciones realizadas

#### 9. TESTIMONIOS

##### **🎭 Testimonios (testimonies)**
**Migración:** `2026_05_07_001319_create_testimonies_table.php`
```sql
-- Profesional
name, specialty, city
-- Contenido
content
-- Media
photo
-- Control
is_active
-- Timestamps
created_at, updated_at
```
**Características:**
- Información completa del profesional
- Testimonio/opinión del paciente
- Foto profesional
- Estados de activación

#### 10. RECURSOS CLÍNICOS (CRUD COMPLETO)

##### **📚 Recursos Clínicos (resources)**
**Migración:** `2026_05_07_001315_create_resources_table.php`
```sql
-- Contenido
title, description
-- Tipo
type (case_study/video/manual/digital_planning)
-- Media
file_path, thumbnail
-- Relación polimórfica
resourceable_type, resourceable_id
-- Control
is_active
-- Timestamps
created_at, updated_at
```
**Características:**
- Múltiples tipos de recursos clínicos
- Sistema polimórfico para asociar a productos o categorías
- Soporte para archivos y videos
- Estados de activación

##### **🔗 Relaciones Polimórficas:**
- **Resources → Products** (Uno a Muchos)
- **Resources → Categories** (Uno a Muchos)

#### 11. GESTIÓN DE USUARIOS (ADMIN)

##### **👥 Usuarios (users)**
**Migración:** `2023_08_10_151033_create_users_table.php`
```sql
-- Autenticación
name, email, password
-- Roles y permisos
rol_id, level
-- Perfil
image, last_login_at
-- Control
is_active
-- Timestamps
created_at, updated_at
```
**Características:**
- Autenticación estándar Laravel
- Sistema de roles multinivel
- Perfil con avatar
- Registro de última actividad

##### **🔐 Roles (roles)**
**Migración:** `2023_08_10_151028_create_roles_table.php`
```sql
-- Configuración
name, display_name, description
-- Control
is_active
-- Timestamps
created_at, updated_at
```
**Características:**
- Definición de roles del sistema
- Nombres descriptivos para UI
- Estados de activación

##### **🔑 Permisos (permissions)**
**Migración:** `2023_08_10_151030_create_permissions_table.php`
```sql
-- Relaciones
rol_id, submodule_id
-- Control
status
-- Timestamps
created_at, updated_at
```
**Características:**
- Permisos granulares por rol y módulo
- Estados de activación
- Sistema de control de acceso

##### **🔗 Relaciones de Usuarios:**
- **Users → Roles** (Muchos a Uno)
- **Users → Permissions** (Muchos a Muchos vía Roles)

#### 12. CONFIGURACIÓN DEL SISTEMA

##### **⚙️ Configuración (settings)**
**Migración:** `2023_08_10_151032_create_settings_table.php`
```sql
-- Corporativa
company_name, logo, address, phone, email
-- Contacto
website
-- Social Media
social_media (JSON)
-- SEO
google_analytics_head, google_analytics_body
-- Ubicación
google_maps_url, business_hours
-- Timestamps
created_at, updated_at
```
**Características:**
- Información corporativa completa
- Integración con redes sociales
- SEO y analytics
- Información de contacto y ubicación

#### 13. NAVEGACIÓN DEL CMS

##### **🧭 Menús (menus)**
**Migración:** `2023_08_10_151026_create_menus_table.php`
```sql
-- Contenido
title, url, icon, description
-- Control
is_active, position, target_blank
-- Jerarquía
parent_id (FK → menus.id)
-- Timestamps
created_at, updated_at
```
**Características:**
- Sistema de menús jerárquico
- Iconos Lucide integrados
- Posicionamiento manual
- Estados de activación
- Apertura en nuevas pestañas

##### **🔗 Relaciones de Menús:**
- **Menus → Menus** (Auto-referencia jerárquica)

#### 14. ACTIVIDADES Y AUDITORÍA

##### **📊 Actividades (activities)**
**Migración:** `2023_08_10_151035_create_activities_table.php`
```sql
-- Registro
user_id, action, module
-- Contexto
subject, subject_id
-- Control
ip_address, user_agent
-- Timestamps
created_at, updated_at
```
**Características:**
- Registro completo de actividades
- Seguimiento de acciones por módulo
- Información de contexto y ubicación
- Auditoría de cambios

##### **🔗 Relaciones de Actividades:**
- **Users → Activities** (Uno a Muchos)

#### 15. MARCAS (GESTIÓN COMERCIAL)

##### **🏢 Marcas (brands)**
**Migración:** `2026_05_07_001266_create_brands_table.php`
```sql
-- Identidad
id, name, slug, description
-- SEO
meta_title, meta_description, meta_keywords
-- Control
is_active, is_featured, position, product_count
-- Índices
fullText([name, description]), index(['is_active', 'is_featured'])
```
**Características:**
- Nombre y slug únicos
- SEO optimizado
- Posicionamiento manual
- Contador cache de productos
- Estados de activación y destacado

##### **🔗 Relaciones de Marcas:**
- **Brands → Products** (Uno a Muchos)

##### **✅ Validaciones y Características:**
- Nombre único, slug único
- Full-text search optimizado
- Índices compuestos para filtros de rendimiento
- Foreign keys con cascade y set null

---
**REQUERIMIENTO DE EDICIÓN:** Al editar cualquier módulo, se deben precargar todos los datos. Las imágenes y archivos existentes deben ser visibles y permitir su eliminación individual sin afectar al resto de la carga.

### 🛡️ 9. SEGURIDAD Y CALIDAD DE CÓDIGO
- **Idiomas:** Código en **Inglés** (Variables, métodos, comentarios JSDoc/PHPDoc). UI en **Español**.
- **Tipado:** Strict Types en PHP 8.2+.
- **Validación de Datos:** - En controladores o componentes Livewire, usar siempre la estructura de los Requests definidos para asegurar consistencia en tipos de datos y existencia en base de datos.
- **Manejo de Sesiones:** El guard predeterminado para el acceso administrativo es `web`.

#### **GESTIÓN DE SESIÓN Y AUTENTICACIÓN**
- **Redirección por Sesión:** 
    - Si el usuario no tiene sesión activa → redirigir a `login`.
    - Si el usuario ya está autenticado e intenta acceder a `login` → redirigir a `dashboard`.
- **Middleware de Sesión:** Verificar `Auth::guard('web')->guest()` para detectar usuarios sin sesión.
- **Persistencia:** Mantener sesión activa durante toda la navegación del CMS.

#### **MIDDLEWARE DE ROLES (RoleMiddleware)**
```php
<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $submodule_id
     * @return mixed
     */
    public function handle($request, Closure $next, $submodule_id) {
        if (Auth::guard('web')->guest()) {
            return redirect('login');
        } else {
            if (Auth::guard('web')->User()->level == '2') {
                $permission = Permission::
                        where('rol_id', Auth::guard('web')->User()->rol_id)
                        ->where('submodule_id', $submodule_id)
                        ->where('status', Permission::ACTIVE_STATUS)
                        ->first();

                return isset($permission) == 0 ?
                        redirect('dashboard') :
                        $next($request);
            }
        }

        return $next($request);
    }
}
```

#### **LÓGICA DE PERMISOS**
- **Nivel 1 (SuperAdmin):** Acceso completo a todos los módulos sin verificación de permisos.
- **Nivel 2+:** Verificación obligatoria en tabla `Permission`:
    - `rol_id`: ID del rol del usuario.
    - `submodule_id`: ID del submódulo solicitado.
    - `status`: `Permission::ACTIVE_STATUS` (1).
- **Uso en Rutas:** `->middleware('role:ID_SUBMODULO')` para proteger rutas específicas.

---

### 🚀 10. PERFORMANCE & ESCALABILIDAD
- Evitar consultas N+1 (Uso de Eager Loading).
- Componentes Livewire pequeños y reutilizables.
- Lazy Loading en componentes pesados.
- Persistencia de estado en la Query String.


### 📂 11. ESTRUCTURA DE DIRECTORIOS (BACKEND)
- **Controllers:** `app/Http/Controllers/`
- **Models:** `app/Models/` (Mantener controladores delgados moviendo la lógica de creación/obtención al Modelo).
- **Requests:** `app/Http/Requests/`
- **Resources:** `app/Http/Resources/` (Para transformación de datos JSON si es necesario).

### 🔐 12. SEGURIDAD: MIDDLEWARE & CONTROL DE ACCESO
Registro: Middlewares registrados en bootstrap/app.php (Estilo Laravel 11).

Middlewares Requeridos:
AdminAuth (admin): Verifica autenticación y que la cuenta esté activa.

Fallo: Redirige a login con flash warning: "Inicie sesión para continuar".

RoleControl (role:level): Verifica si el role_id del usuario cumple con el nivel requerido (Ej: role:10 para SuperAdmin).

Fallo: Loguea el intento fallido en Activities y redirige al Dashboard con flash notice: "No tiene permisos para acceder a este módulo".

### 📍 13. ESTRATEGIA DE RUTEO (HIERARCHICAL STYLE)
Sintaxis: Siempre usar el formato de string 'App\Http\Controllers\ExampleController@method'.

SPA: Todos los enlaces de navegación deben incluir wire:navigate.

Estructura de Grupos:
A. Rutas de Autenticación

PHP
Route::group(['middleware' => 'cors', 'prefix' => '/auth'], function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
});
B. Capa Administrativa Protegida (/admin)

PHP
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => '/admin'], function () {
    
    // DASHBOARD & CUENTA
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index');
    Route::get('/account', 'App\Http\Controllers\AccountController@edit');
    Route::put('/account', 'App\Http\Controllers\AccountController@update');

    // CATÁLOGO (Productos y Categorías)
    Route::prefix('/catalog')->group(function () {
        // Categorías
        Route::prefix('/categories')->group(function () {
            Route::get('/', 'App\Http\Controllers\CategoriesController@index');
            Route::post('/', 'App\Http\Controllers\CategoriesController@store');
            Route::get('/{id}/edit', 'App\Http\Controllers\CategoriesController@edit');
            Route::put('/{id}', 'App\Http\Controllers\CategoriesController@update');
            Route::delete('/{id}', 'App\Http\Controllers\CategoriesController@destroy');
        });
        
        // Productos
        Route::prefix('/products')->group(function () {
            Route::get('/', 'App\Http\Controllers\ProductsController@index');
            Route::post('/', 'App\Http\Controllers\ProductsController@store');
            Route::get('/{id}/edit', 'App\Http\Controllers\ProductsController@edit');
            Route::put('/{id}', 'App\Http\Controllers\ProductsController@update');
            Route::delete('/{id}', 'App\Http\Controllers\ProductsController@destroy');
        });
        
        // Importación Masiva
        Route::prefix('/import')->group(function () {
            Route::get('/', 'App\Http\Controllers\ImportController@index');
            Route::post('/products', 'App\Http\Controllers\ImportController@importProducts');
            Route::post('/categories', 'App\Http\Controllers\ImportController@importCategories');
            Route::get('/template/{type}', 'App\Http\Controllers\ImportController@downloadTemplate');
            Route::get('/preview', 'App\Http\Controllers\ImportController@preview');
        });
    });

    // BLOG Y CONTENIDO
    Route::prefix('/blog')->group(function () {
        // Categorías de Blog
        Route::prefix('/categories')->group(function () {
            Route::get('/', 'App\Http\Controllers\BlogCategoriesController@index');
            Route::post('/', 'App\Http\Controllers\BlogCategoriesController@store');
            Route::get('/{id}/edit', 'App\Http\Controllers\BlogCategoriesController@edit');
            Route::put('/{id}', 'App\Http\Controllers\BlogCategoriesController@update');
            Route::delete('/{id}', 'App\Http\Controllers\BlogCategoriesController@destroy');
        });
        
        // Blogs
        Route::prefix('/posts')->group(function () {
            Route::get('/', 'App\Http\Controllers\BlogsController@index');
            Route::post('/', 'App\Http\Controllers\BlogsController@store');
            Route::get('/{id}/edit', 'App\Http\Controllers\BlogsController@edit');
            Route::put('/{id}', 'App\Http\Controllers\BlogsController@update');
            Route::delete('/{id}', 'App\Http\Controllers\BlogsController@destroy');
        });
    });

    // VENTAS (Cotizaciones)
    Route::prefix('/quotes')->group(function () {
        Route::get('/', 'App\Http\Controllers\QuotesController@index');
        Route::get('/{id}', 'App\Http\Controllers\QuotesController@show');
        Route::get('/export/excel', 'App\Http\Controllers\QuotesController@exportExcel');
        Route::get('/export/pdf', 'App\Http\Controllers\QuotesController@exportPdf');
    });

    // TESTIMONIOS
    Route::prefix('/testimonials')->group(function () {
        Route::get('/', 'App\Http\Controllers\TestimonialsController@index');
        Route::post('/', 'App\Http\Controllers\TestimonialsController@store');
        Route::get('/{id}/edit', 'App\Http\Controllers\TestimonialsController@edit');
        Route::put('/{id}', 'App\Http\Controllers\TestimonialsController@update');
        Route::delete('/{id}', 'App\Http\Controllers\TestimonialsController@destroy');
        Route::post('/move', 'App\Http\Controllers\TestimonialsController@move_testimony');
    });

    // RECURSOS CLÍNICOS
    Route::prefix('/resources')->group(function () {
        Route::get('/', 'App\Http\Controllers\ResourcesController@index');
        Route::post('/', 'App\Http\Controllers\ResourcesController@store');
        Route::get('/{id}/edit', 'App\Http\Controllers\ResourcesController@edit');
        Route::put('/{id}', 'App\Http\Controllers\ResourcesController@update');
        Route::delete('/{id}', 'App\Http\Controllers\ResourcesController@destroy');
    });

    // DISTRIBUIDORES
    Route::prefix('/distributors')->group(function () {
        Route::get('/', 'App\Http\Controllers\DistributorsController@index');
        Route::post('/', 'App\Http\Controllers\DistributorsController@store');
        Route::get('/{id}/edit', 'App\Http\Controllers\DistributorsController@edit');
        Route::put('/{id}', 'App\Http\Controllers\DistributorsController@update');
        Route::delete('/{id}', 'App\Http\Controllers\DistributorsController@destroy');
    });

    // GESTIÓN DE USUARIOS
    Route::prefix('/users')->group(function () {
        Route::get('/', 'App\Http\Controllers\UsersController@index');
        Route::post('/', 'App\Http\Controllers\UsersController@store');
        Route::get('/{id}/edit', 'App\Http\Controllers\UsersController@edit');
        Route::put('/{id}', 'App\Http\Controllers\UsersController@update');
        Route::delete('/{id}', 'App\Http\Controllers\UsersController@destroy');
        Route::post('/{id}/reset-password', 'App\Http\Controllers\UsersController@resetPassword');
    });

    // CONFIGURACIÓN DEL SISTEMA
    Route::prefix('/settings')->group(function () {
        Route::get('/', 'App\Http\Controllers\SettingsController@edit');
        Route::put('/', 'App\Http\Controllers\SettingsController@update');
    });

    // NAVEGACIÓN DEL CMS
    Route::prefix('/navigation')->group(function () {
        Route::get('/', 'App\Http\Controllers\NavigationController@index');
        Route::post('/', 'App\Http\Controllers\NavigationController@store');
        Route::get('/{id}/edit', 'App\Http\Controllers\NavigationController@edit');
        Route::put('/{id}', 'App\Http\Controllers\NavigationController@update');
        Route::delete('/{id}', 'App\Http\Controllers\NavigationController@destroy');
    });

    // ACTIVIDADES Y AUDITORÍA
    Route::prefix('/activities')->group(function () {
        Route::get('/', 'App\Http\Controllers\ActivitiesController@index');
        Route::get('/export/pdf', 'App\Http\Controllers\ActivitiesController@exportPdf');
        Route::get('/export/excel', 'App\Http\Controllers\ActivitiesController@exportExcel');
    });

    // MEDIA & STORAGE
    Route::prefix('/media')->group(function () {
        Route::post('/upload', 'App\Http\Controllers\MediaController@upload');
        Route::delete('/photo/{id}', 'App\Http\Controllers\MediaController@delete_photo');
        Route::delete('/file/{id}', 'App\Http\Controllers\MediaController@delete_file');
    });
});

---

## 🛠️ SERVICES Y UTILIDADES

### **1. FileUpload Service**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUpload {
    
    /**
     * Upload file with validation and organization
     * @param UploadedFile $file
     * @param string $directory
     * @param array $allowedTypes
     * @return array
     */
    public static function save(UploadedFile $file, string $directory, array $allowedTypes = ['jpg', 'png', 'webp', 'pdf']) {
        try {
            // Validate file type
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedTypes)) {
                throw new \Exception("File type not allowed");
            }
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs($directory, $filename, 'public');
            
            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];
        } catch (Exception $ex) {
            report($ex);
            return [
                'success' => false,
                'error' => $ex->getMessage()
            ];
        }
    }
    
    /**
     * Delete file from storage
     * @param string $path
     * @return bool
     */
    public static function delete(string $path) {
        try {
            return Storage::disk('public')->delete($path);
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }
}
```

### **2. CustomMail Service**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class CustomMail {
    
    /**
     * Send password reset email
     * @param string $email
     * @param string $token
     * @return bool
     */
    public static function passwordReset(string $email, string $token) {
        try {
            $data = [
                'email' => $email,
                'token' => $token,
                'resetLink' => route('password.reset', $token)
            ];
            
            Mail::send('emails.password-reset', $data, function($message) use ($email) {
                $message->to($email)
                        ->subject('Restablecimiento de Contraseña - Helin Latam');
            });
            
            return true;
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }
    
    /**
     * Send welcome email
     * @param array $user
     * @return bool
     */
    public static function welcome(array $user) {
        try {
            Mail::send('emails.welcome', $user, function($message) use ($user) {
                $message->to($user['email'])
                        ->subject('Bienvenido a Helin Latam');
            });
            
            return true;
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }
}
```

---

## 📝 FORM REQUESTS (VALIDACIÓN)

### **1. ProductRequest**
```php
<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest {
    
    public function authorize() {
        return true;
    }
    
    public function rules() {
        $productId = $this->route('id');
        
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
            'brand' => 'required|string|max:100',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'clinical_specs' => 'nullable|array',
            'attributes' => 'nullable|array',
            'gallery' => 'nullable|array',
            'files' => 'nullable|array',
            'is_active' => 'boolean'
        ];
    }
    
    public function messages() {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'sku.unique' => 'El SKU ya está en uso',
            'slug.unique' => 'El slug ya está en uso',
            'category_id.exists' => 'La categoría seleccionada no es válida'
        ];
    }
}
```

### **2. UserRequest**
```php
<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest {
    
    public function authorize() {
        return true;
    }
    
    public function rules() {
        $userId = $this->route('id');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'password' => $isUpdate ? 'nullable' : ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
            'rol_id' => 'required|exists:roles,id',
            'level' => 'required|integer|min:1|max:10',
            'image' => 'nullable|image|mimes:jpg,png,webp|max:2048'
        ];
    }
    
    public function messages() {
        return [
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'email.unique' => 'El email ya está registrado'
        ];
    }
}
```

### **3. BlogRequest**
```php
<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest {
    
    public function authorize() {
        return true;
    }
    
    public function rules() {
        $blogId = $this->route('id');
        
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blogId,
            'author' => 'nullable|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'boolean'
        ];
    }
}
```

### **4. ImportRequest**
```php
<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest {
    
    public function authorize() {
        return true;
    }
    
    public function rules() {
        return [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
            'type' => 'required|in:products,categories',
            'update_existing' => 'boolean',
            'skip_first_row' => 'boolean',
            'column_mapping' => 'nullable|array'
        ];
    }
    
    public function messages() {
        return [
            'csv_file.required' => 'El archivo CSV es obligatorio',
            'csv_file.mimes' => 'El archivo debe ser de tipo CSV',
            'csv_file.max' => 'El archivo no puede ser mayor a 10MB',
            'type.required' => 'Debe especificar el tipo de importación',
            'type.in' => 'El tipo de importación debe ser productos o categorías'
        ];
    }
}
```

---

## 🎨 COMPONENTES BLADE

### **1. Button Component**
```php
{{-- resources/views/components/button.blade.php --}}
@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'loading' => false])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "
        inline-flex items-center justify-center
        font-medium rounded-2xl
        transition-all duration-200
        focus:outline-none focus:ring-2 focus:ring-offset-2
        {$variant === 'primary' ? 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-blue-500' : ''}
        {$variant === 'secondary' ? 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500' : ''}
        {$variant === 'danger' ? 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500' : ''}
        {$size === 'sm' ? 'px-3 py-1.5 text-sm' : ''}
        {$size === 'md' ? 'px-4 py-2 text-base' : ''}
        {$size === 'lg' ? 'px-6 py-3 text-lg' : ''}
        {$loading ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-[0.98]'}
    "])}}
    {{ $loading ? 'disabled' : '' }}
>
    {{ $loading ? '<span class="animate-spin mr-2">⟳</span>' : '' }}
    {{ $slot }}
</button>
```

### **2. Input Component**
```php
{{-- resources/views/components/input.blade.php --}}
@props(['type' => 'text', 'label' => null, 'name', 'value' => '', 'error' => null, 'required' => false])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => "
            block w-full rounded-2xl border-gray-300
            focus:border-blue-500 focus:ring-blue-500
            shadow-sm
            {$error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}
        "])}}
    >
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
```

### **3. Card Component**
```php
{{-- resources/views/components/card.blade.php --}}
@props(['title' => null, 'padding' => 'normal'])

<div {{ $attributes->merge(['class' => "
    bg-white rounded-3xl shadow-lg shadow-black/5
    {$padding === 'normal' ? 'p-6' : ''}
    {$padding === 'tight' ? 'p-4' : ''}
    {$padding === 'loose' ? 'p-8' : ''}
"])}}>
    @if($title)
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    <div>
        {{ $slot }}
    </div>
</div>
```

---

## ⚡ EJEMPLOS DE ALPINE.JS

### **1. Dropdown de Usuario**
```php
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-2xl hover:bg-gray-100">
        <img src="{{ auth()->user()->image ?? '/default-avatar.png' }}" class="w-8 h-8 rounded-full">
        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="open = false"
        class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg shadow-black/10 py-1 z-50"
    >
        <a href="{{ route('admin.account') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Actualizar Perfil</a>
        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar Sesión</a>
    </div>
</div>
```

### **2. Modal de Confirmación**
```php
<div x-data="{ showModal: false }">
    <button @click="showModal = true" class="bg-red-600 text-white px-4 py-2 rounded-2xl hover:bg-red-700">
        Eliminar
    </button>
    
    <div 
        x-show="showModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-95"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Cuidado!</h3>
                <p class="text-gray-600 mb-6">¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.</p>
                
                <div class="flex space-x-3">
                    <button @click="showModal = false" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-2xl hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button @click="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-2xl hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 📨 MESSAGES.PHP (CONSTANTES)

```php
<?php

namespace App\Utils;

class Messages {
    
    // SUCCESS MESSAGES
    const LABEL_SUCCESS_CREATE = 'Registro guardado correctamente';
    const LABEL_SUCCESS_UPDATE = 'Cambios actualizados con éxito';
    const LABEL_SUCCESS_DELETE = 'Registro eliminado correctamente';
    const LABEL_SUCCESS_UPLOAD = 'Archivo subido correctamente';
    const LABEL_SUCCESS_PASSWORD_RESET = 'Contraseña restablecida correctamente';
    
    // ERROR MESSAGES
    const LABEL_ERROR_GENERAL = 'Ocurrió un error al procesar la solicitud';
    const LABEL_ERROR_VALIDATION = 'Por favor, revisa los errores en el formulario';
    const LABEL_ERROR_UPLOAD = 'Error al subir el archivo';
    const LABEL_ERROR_LOGIN = 'Credenciales incorrectas';
    const LABEL_ERROR_PERMISSION = 'No tiene permisos para realizar esta acción';
    
    // WARNING MESSAGES
    const LABEL_WARNING_INACTIVE = 'El registro está inactivo';
    const LABEL_WARNING_REQUIRED = 'Este campo es obligatorio';
    
    // INFO MESSAGES
    const LABEL_INFO_LOADING = 'Cargando...';
    const LABEL_INFO_PROCESSING = 'Procesando solicitud...';
    
    /**
     * Get message by key
     * @param string $key
     * @return string
     */
    public static function get(string $key): string {
        return constant("self::$key") ?? 'Mensaje no encontrado';
    }
}
```

---

## 🎨 DESIGN SYSTEM & UI COMPONENTS

### **🎯 DESIGN TOKENS (Sistema de Diseño)**

#### **1. Color System**
```css
/* resources/css/design-tokens.css */
:root {
  /* Brand Colors */
  --color-primary: 59 130 246;        /* blue-600 */
  --color-primary-light: 147 197 253; /* blue-300 */
  --color-primary-dark: 29 78 216;     /* blue-800 */
  
  /* Semantic Colors */
  --color-success: 34 197 94;          /* green-500 */
  --color-warning: 245 158 11;        /* amber-500 */
  --color-danger: 239 68 68;          /* red-500 */
  --color-info: 59 130 246;            /* blue-500 */
  
  /* Neutral Palette */
  --color-gray-50: 249 250 251;
  --color-gray-100: 243 244 246;
  --color-gray-200: 229 231 235;
  --color-gray-300: 203 213 225;
  --color-gray-400: 156 163 175;
  --color-gray-500: 107 114 128;
  --color-gray-600: 75 85 99;
  --color-gray-700: 55 65 81;
  --color-gray-800: 31 41 55;
  --color-gray-900: 17 24 39;
  
  /* Background Colors */
  --bg-primary: rgb(var(--color-gray-50));
  --bg-secondary: rgb(var(--color-gray-100));
  --bg-tertiary: rgb(var(--color-gray-200));
  --bg-dark: rgb(var(--color-gray-900));
  
  /* Text Colors */
  --text-primary: rgb(var(--color-gray-900));
  --text-secondary: rgb(var(--color-gray-600));
  --text-tertiary: rgb(var(--color-gray-400));
  --text-inverse: rgb(var(--color-gray-50));
}

/* Dark Mode */
:root.dark {
  --bg-primary: rgb(var(--color-gray-900));
  --bg-secondary: rgb(var(--color-gray-800));
  --bg-tertiary: rgb(var(--color-gray-700));
  --text-primary: rgb(var(--color-gray-50));
  --text-secondary: rgb(var(--color-gray-300));
  --text-tertiary: rgb(var(--color-gray-400));
}
```

#### **2. Typography System**
```css
:root {
  /* Font Families */
  --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
  --font-display: 'Inter Display', sans-serif;
  
  /* Font Sizes */
  --text-xs: 0.75rem;     /* 12px */
  --text-sm: 0.875rem;    /* 14px */
  --text-base: 1rem;      /* 16px */
  --text-lg: 1.125rem;    /* 18px */
  --text-xl: 1.25rem;     /* 20px */
  --text-2xl: 1.5rem;     /* 24px */
  --text-3xl: 1.875rem;   /* 30px */
  --text-4xl: 2.25rem;    /* 36px */
  
  /* Font Weights */
  --font-light: 300;
  --font-normal: 400;
  --font-medium: 500;
  --font-semibold: 600;
  --font-bold: 700;
  
  /* Line Heights */
  --leading-tight: 1.25;
  --leading-normal: 1.5;
  --leading-relaxed: 1.75;
}
```

#### **3. Spacing System**
```css
:root {
  /* Spacing Scale (8px base unit) */
  --space-0: 0;
  --space-1: 0.25rem;    /* 4px */
  --space-2: 0.5rem;     /* 8px */
  --space-3: 0.75rem;    /* 12px */
  --space-4: 1rem;       /* 16px */
  --space-5: 1.25rem;    /* 20px */
  --space-6: 1.5rem;     /* 24px */
  --space-8: 2rem;       /* 32px */
  --space-10: 2.5rem;    /* 40px */
  --space-12: 3rem;      /* 48px */
  --space-16: 4rem;      /* 64px */
  --space-20: 5rem;      /* 80px */
  --space-24: 6rem;      /* 96px */
}
```

#### **4. Border Radius System**
```css
:root {
  --radius-none: 0;
  --radius-sm: 0.25rem;   /* 4px - rounded */
  --radius-md: 0.375rem;  /* 6px - rounded-md */
  --radius-lg: 0.5rem;    /* 8px - rounded-lg */
  --radius-xl: 0.75rem;   /* 12px - rounded-xl */
  --radius-2xl: 1rem;     /* 16px - rounded-2xl */
  --radius-3xl: 1.5rem;   /* 24px - rounded-3xl */
  --radius-full: 9999px;
}
```

### **🏗️ COMPONENT ARCHITECTURE**

#### **1. File Structure**
```
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php              # Layout maestro
│   │   ├── auth.blade.php             # Login/Recuperación
│   │   └── dashboard.blade.php        # Con sidebar
│   ├── components/
│   │   ├── ui/                        # Componentes UI básicos
│   │   │   ├── button.blade.php
│   │   │   ├── input.blade.php
│   │   │   ├── modal.blade.php
│   │   │   ├── dropdown.blade.php
│   │   │   ├── badge.blade.php
│   │   │   ├── avatar.blade.php
│   │   │   ├── skeleton.blade.php
│   │   │   └── tooltip.blade.php
│   │   ├── forms/                     # Componentes de formulario
│   │   │   ├── form-group.blade.php
│   │   │   ├── form-textarea.blade.php
│   │   │   ├── form-select.blade.php
│   │   │   ├── form-checkbox.blade.php
│   │   │   ├── form-radio.blade.php
│   │   │   ├── form-file.blade.php
│   │   │   └── form-switch.blade.php
│   │   ├── layout/                    # Componentes de layout
│   │   │   ├── sidebar.blade.php
│   │   │   ├── header.blade.php
│   │   │   ├── footer.blade.php
│   │   │   ├── container.blade.php
│   │   │   ├── grid.blade.php
│   │   │   └── stack.blade.php
│   │   ├── data/                      # Componentes de datos
│   │   │   ├── data-table.blade.php
│   │   │   ├── data-card.blade.php
│   │   │   ├── empty-state.blade.php
│   │   │   ├── pagination.blade.php
│   │   │   └── breadcrumb.blade.php
│   │   └── content/                   # Componentes de contenido
│   │       ├── rich-text.blade.php
│   │       ├── image-upload.blade.php
│   │       ├── file-manager.blade.php
│   │       └── code-editor.blade.php
│   └── partials/
│       ├── navigation/
│       │   ├── sidebar-menu.blade.php
│       │   └── header-nav.blade.php
│       └── common/
│           ├── flash-messages.blade.php
│           └── loading-overlay.blade.php
├── css/
│   ├── base/
│   │   ├── reset.css
│   │   ├── typography.css
│   │   └── design-tokens.css
│   ├── components/
│   │   ├── ui.css
│   │   ├── forms.css
│   │   ├── layout.css
│   │   └── data.css
│   ├── utilities/
│   │   ├── spacing.css
│   │   ├── colors.css
│   │   └── animations.css
│   └── themes/
│       ├── light.css
│       └── dark.css
└── js/
    ├── components/
    │   ├── theme-toggle.js
    │   ├── modal.js
    │   ├── dropdown.js
    │   └── file-upload.js
    └── utils/
        ├── helpers.js
        └── animations.js
```

### **🧩 COMPONENT LIBRARY**

#### **1. Core UI Components**

##### **Button Component (Enhanced)**
```php
{{-- resources/views/components/ui/button.blade.php --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $variantClasses = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-amber-600 text-white hover:bg-amber-700 focus:ring-amber-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
        'link' => 'text-primary-600 hover:text-primary-700 focus:ring-blue-500 p-0'
    ];
    
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        'xl' => 'px-8 py-4 text-xl'
    ];
@endphp

<button
    {{ $attributes->merge([
        'type' => 'button',
        'disabled' => $disabled || $loading,
        'class' => trim("
            inline-flex items-center justify-center
            font-medium rounded-2xl
            transition-all duration-200
            focus:outline-none focus:ring-2 focus:ring-offset-2
            {$variantClasses[$variant] ?? $variantClasses['primary']}
            {$sizeClasses[$size] ?? $sizeClasses['md']}
            {$loading || $disabled ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-[0.98]'}
        ")
    ])}}
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    
    @if($icon && $iconPosition === 'left')
        <x-icon :name="$icon" class="mr-2 h-4 w-4" />
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <x-icon :name="$icon" class="ml-2 h-4 w-4" />
    @endif
</button>
```

##### **Modal Component**
```php
{{-- resources/views/components/ui/modal.blade.php --}}
@props([
    'id' => 'modal-' . uniqid(),
    'size' => 'md',
    'show' => false,
    'closable' => true,
    'title' => null
])

@php
    $sizeClasses = [
        'xs' => 'max-w-xs',
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        'full' => 'max-w-full mx-4'
    ];
@endphp

<div
    x-data="{ 
        showModal: @entangle('show'),
        id: '{{ $id }}'
    }"
    x-show="showModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"
        @click="showModal = false"
    ></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4">
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-95"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="{{ $sizeClasses[$size] ?? $sizeClasses['md'] }} w-full bg-white rounded-3xl shadow-2xl shadow-black/10"
        >
            <!-- Header -->
            @if($title || $closable)
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    @if($title)
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    @endif
                    
                    @if($closable)
                        <button
                            @click="showModal = false"
                            class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <x-icon name="x" class="h-5 w-5" />
                        </button>
                    @endif
                </div>
            @endif
            
            <!-- Body -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
```

##### **Data Table Component**
```php
{{-- resources/views/components/data/data-table.blade.php --}}
@props([
    'headers' => [],
    'data' => [],
    'actions' => true,
    'searchable' => true,
    'pagination' => true,
    'emptyMessage' => 'No hay registros disponibles'
])

<div x-data="dataTable()" class="bg-white rounded-3xl shadow-lg shadow-black/5">
    <!-- Search and Filters -->
    @if($searchable)
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="flex-1 relative">
                    <x-icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Buscar..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                
                <x-button variant="secondary" size="sm">
                    <x-icon name="filter" class="mr-2 h-4 w-4" />
                    Filtros
                </x-button>
            </div>
        </div>
    @endif
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header['label'] }}
                        </th>
                    @endforeach
                    
                    @if($actions)
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    @endif
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-200">
                @forelse($data as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        @foreach($headers as $key => $header)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item[$key] ?? '' }}
                            </td>
                        @endforeach
                        
                        @if($actions)
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <x-button variant="ghost" size="sm">
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </x-button>
                                    
                                    <x-button variant="ghost" size="sm" class="text-red-600 hover:text-red-700">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </x-button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center space-y-4">
                                <x-icon name="inbox" class="h-12 w-12 text-gray-400" />
                                <p class="text-gray-500">{{ $emptyMessage }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pagination && $data->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $data->links() }}
        </div>
    @endif
</div>

<script>
function dataTable() {
    return {
        search: '',
        
        get filteredData() {
            if (!this.search) return @json($data);
            
            return @json($data).filter(item => {
                return Object.values(item).some(value => 
                    String(value).toLowerCase().includes(this.search.toLowerCase())
                );
            });
        }
    }
}
</script>
```

### **🌓 THEME SYSTEM**

#### **1. Dark Mode Implementation**
```php
{{-- resources/views/components/theme-toggle.blade.php --}}
<div x-data="themeToggle()" class="relative">
    <button
        @click="toggleTheme()"
        class="p-2 rounded-2xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors"
        :title="isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
    >
        <x-icon 
            :name="isDark ? 'sun' : 'moon'" 
            class="h-5 w-5 text-gray-700 dark:text-gray-300"
        />
    </button>
</div>

<script>
function themeToggle() {
    return {
        isDark: false,
        
        init() {
            this.isDark = localStorage.getItem('theme') === 'dark' || 
                         (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            this.applyTheme();
        },
        
        toggleTheme() {
            this.isDark = !this.isDark;
            this.applyTheme();
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
        
        applyTheme() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }
}
</script>
```

### **📱 RESPONSIVE SYSTEM**

#### **1. Mobile-First Breakpoints**
```css
/* resources/css/utilities/responsive.css */
:root {
  --breakpoint-sm: 640px;
  --breakpoint-md: 768px;
  --breakpoint-lg: 1024px;
  --breakpoint-xl: 1280px;
  --breakpoint-2xl: 1536px;
}

/* Mobile First Approach */
.sidebar-mobile {
  @apply fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0;
}

.sidebar-mobile.open {
  @apply translate-x-0;
}

/* Responsive Grid */
.responsive-grid {
  @apply grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4;
}

/* Responsive Cards */
.responsive-card {
  @apply p-4 sm:p-6 lg:p-8;
}
```

### **✨ ANIMATION SYSTEM**

#### **1. Motion Library**
```css
/* resources/css/utilities/animations.css */
/* entrances */
@keyframes slideInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes scaleIn {
  from {
    transform: scale(0.95);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

/* Utility Classes */
.animate-slide-up {
  animation: slideInUp 0.3s ease-out;
}

.animate-fade-in {
  animation: fadeIn 0.2s ease-out;
}

.animate-scale-in {
  animation: scaleIn 0.2s ease-out;
}

/* Hover Effects */
.hover-lift {
  @apply transition-transform duration-200 hover:scale-[1.02];
}

.hover-glow {
  @apply transition-shadow duration-200 hover:shadow-lg hover:shadow-blue-500/20;
}

/* Loading States */
.loading-pulse {
  @apply animate-pulse;
}

.loading-spin {
  @apply animate-spin;
}

/* Page Transitions */
.page-transition-enter {
  @apply animate-fade-in;
}

.page-transition-leave {
  @apply animate-fade-out;
}
```

### **🎯 IMPLEMENTATION GUIDELINES**

#### **1. Component Usage Examples**
```php
{{-- Example: Complete Form with Components --}}
<x-card>
    <x-slot name="title">
        Nuevo Producto
    </x-slot>
    
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <x-form-group label="Nombre del Producto" required>
                <x-input 
                    name="name" 
                    wire:model="name" 
                    required
                    :error="$errors->first('name')"
                />
            </x-form-group>
            
            <x-form-group label="Descripción">
                <x-form-textarea 
                    name="description" 
                    wire:model="description"
                    rows="4"
                />
            </x-form-group>
            
            <x-form-group label="Categoría">
                <x-form-select 
                    name="category_id"
                    wire:model="category_id"
                    :options="$categories"
                    placeholder="Selecciona una categoría"
                />
            </x-form-group>
            
            <div class="flex justify-end space-x-3">
                <x-button variant="secondary" type="button">
                    Cancelar
                </x-button>
                
                <x-button 
                    type="submit" 
                    variant="primary"
                    :loading="$isSaving"
                >
                    Guardar Producto
                </x-button>
            </div>
        </div>
    </form>
</x-card>
```

#### **2. Layout Structure**
```php
{{-- resources/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Helin Latam CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="flex h-full">
        <!-- Sidebar -->
        <x-layout.sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-layout.header />
            
            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Flash Messages -->
    <x-common.flash-messages />
    
    <!-- Loading Overlay -->
    <x-common.loading-overlay />
</body>
</html>
```

---

## 🌟 PREMIUM UX & HUMAN-CENTERED DESIGN

### **🎯 MICRO-INTERACCIONES DELIGHT**

#### **1. Animaciones con Personalidad**
```css
/* Success Celebration */
@keyframes celebrate {
  0% { transform: scale(1) rotate(0deg); }
  25% { transform: scale(1.1) rotate(5deg); }
  50% { transform: scale(1) rotate(-5deg); }
  75% { transform: scale(1.05) rotate(2deg); }
  100% { transform: scale(1) rotate(0deg); }
}

.celebrate-success {
  animation: celebrate 0.6s ease-in-out;
}

/* Loading with Personality */
@keyframes pulse-gentle {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

.loading-personality {
  animation: pulse-gentle 2s ease-in-out infinite;
}

/* Hover Elegante */
.hover-elegant {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-elegant:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
```

#### **2. Feedback Emocional**
```php
{{-- resources/views/components/feedback/success-message.blade.php --}}
<div x-show="showMessage" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     class="fixed top-4 right-4 z-50">
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 shadow-lg celebrate-success">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h3 class="text-green-800 font-medium">{{ $title ?? '¡Excelente!' }}</h3>
                <p class="text-green-600 text-sm">{{ $message ?? 'Todo salió perfecto' }}</p>
            </div>
        </div>
    </div>
</div>
```

#### **3. Empty States con Propósito**
```php
{{-- resources/views/components/empty/first-product.blade.php --}}
<div class="text-center py-12">
    <!-- Illustration animada -->
    <div class="mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-primary-100 rounded-full animate-pulse">
            <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
    </div>
    
    <h3 class="text-xl font-semibold text-gray-900 mb-2">
        Tu catálogo está esperando
    </h3>
    
    <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Es hora de agregar tu primer producto. Cada gran negocio empieza con un paso.
    </p>
    
    <div class="flex items-center justify-center space-x-4">
        <x-button variant="primary" icon="plus" size="lg">
            Crear primer producto
        </x-button>
        
        <x-button variant="ghost" icon="play-circle">
            Ver tutorial
        </x-button>
    </div>
</div>
```

### **🧠 INTELIGENCIA CONTEXTUAL**

#### **1. Búsqueda Global Inteligente**
```php
{{-- resources/views/components/search/global-search.blade.php --}}
<div x-data="globalSearch()" 
     x-show="showSearch" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center pt-20">
    
    <div @click.away="closeSearch" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-4">
        
        <!-- Search Input -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <x-icon name="search" class="w-6 h-6 text-gray-400" />
                <input 
                    type="text" 
                    x-model="query"
                    x-ref="searchInput"
                    placeholder="Buscar productos, clientes, cotizaciones... (⌘K)"
                    class="flex-1 text-lg border-0 focus:ring-0 outline-none"
                    @keydown.escape="closeSearch()"
                    @keydown.down="highlightNext()"
                    @keydown.up="highlightPrev()"
                    @keydown.enter="selectHighlighted()"
                >
                
                <kbd class="px-2 py-1 text-xs bg-gray-100 rounded">ESC</kbd>
            </div>
        </div>
        
        <!-- Results -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="query && results.length > 0">
                <div class="p-2">
                    <template x-for="(category, categoryName) in groupedResults" :key="categoryName">
                        <div class="mb-4">
                            <h4 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                {{ categoryName }}
                            </h4>
                            <template x-for="item in category" :key="item.id">
                                <div class="px-3 py-2 hover:bg-gray-50 rounded-xl cursor-pointer transition-colors"
                                     :class="{ 'bg-primary-50': highlightedIndex === item.globalIndex }"
                                     @click="navigateTo(item)">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900" x-text="item.title"></div>
                                            <div class="text-sm text-gray-500" x-text="item.subtitle"></div>
                                        </div>
                                        <x-icon name="arrow-right" class="w-4 h-4 text-gray-400" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
            
            <template x-if="query && results.length === 0">
                <div class="p-8 text-center">
                    <x-icon name="search" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                    <p class="text-gray-500">No encontramos resultados para "<span x-text="query"></span>"</p>
                </div>
            </template>
            
            <template x-if="!query">
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-4">Búsquedas recientes</div>
                    <div class="space-y-2">
                        <template x-for="recent in recentSearches" :key="recent">
                            <div class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 cursor-pointer"
                                 @click="setQuery(recent)">
                                <x-icon name="clock" class="w-4 h-4" />
                                <span x-text="recent"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function globalSearch() {
    return {
        showSearch: false,
        query: '',
        results: [],
        recentSearches: ['Producto XYZ', 'Cliente ABC', 'Cotización #123'],
        highlightedIndex: 0,
        
        init() {
            // Keyboard shortcut
            document.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    this.openSearch();
                }
            });
        },
        
        openSearch() {
            this.showSearch = true;
            this.$nextTick(() => this.$refs.searchInput.focus());
        },
        
        closeSearch() {
            this.showSearch = false;
            this.query = '';
            this.results = [];
        },
        
        async search() {
            if (!this.query) return;
            
            try {
                const response = await fetch(`/admin/search?q=${this.query}`);
                this.results = await response.json();
            } catch (error) {
                console.error('Search error:', error);
            }
        },
        
        get groupedResults() {
            const groups = {};
            this.results.forEach((item, index) => {
                item.globalIndex = index;
                if (!groups[item.category]) {
                    groups[item.category] = [];
                }
                groups[item.category].push(item);
            });
            return groups;
        }
    }
}
</script>
```

#### **2. Auto-Save Inteligente**
```php
{{-- resources/views/components/auto-save-indicator.blade.php --}}
<div x-data="autoSave()" x-init="init()">
    <!-- Save Status -->
    <div x-show="status" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="fixed bottom-4 right-4 z-40">
        
        <template x-if="status === 'saving'">
            <div class="bg-primary-500 text-white px-4 py-2 rounded-2xl shadow-lg flex items-center space-x-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm">Guardando...</span>
            </div>
        </template>
        
        <template x-if="status === 'saved'">
            <div class="bg-green-500 text-white px-4 py-2 rounded-2xl shadow-lg flex items-center space-x-2 celebrate-success">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm">¡Guardado!</span>
            </div>
        </template>
        
        <template x-if="status === 'error'">
            <div class="bg-red-500 text-white px-4 py-2 rounded-2xl shadow-lg flex items-center space-x-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-sm">Error al guardar</span>
            </div>
        </template>
    </div>
</div>

<script>
function autoSave() {
    return {
        status: null,
        saveTimeout: null,
        
        init() {
            // Watch for form changes
            this.$watch('formData', () => {
                this.scheduleSave();
            });
        },
        
        scheduleSave() {
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.save();
            }, 2000); // Save after 2 seconds of inactivity
        },
        
        async save() {
            this.status = 'saving';
            
            try {
                await this.$wire.save();
                this.status = 'saved';
                
                setTimeout(() => {
                    this.status = null;
                }, 3000);
                
            } catch (error) {
                this.status = 'error';
                setTimeout(() => {
                    this.status = null;
                }, 5000);
            }
        }
    }
}
</script>
```

### **🎨 PERSONALIZACIÓN AVANZADA**

#### **1. Dashboard Configurable**
```php
{{-- resources/views/dashboard/customizable.blade.php --}}
<div x-data="dashboardConfig()" class="space-y-6">
    <!-- Header con personalización -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">
            Bienvenido de vuelta, {{ auth()->user()->name }}! 👋
        </h1>
        
        <div class="flex items-center space-x-3">
            <x-button variant="ghost" size="sm" @click="toggleEditMode()">
                <x-icon :name="editMode ? 'check' : 'settings'" class="mr-2 h-4 w-4" />
                {{ editMode ? 'Guardar layout' : 'Personalizar' }}
            </x-button>
            
            <x-theme-toggle />
        </div>
    </div>
    
    <!-- Widgets Arrastrables -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" 
         x-sortable="handle: '.drag-handle'"
         @sort="updateWidgetOrder">
        
        <template x-for="widget in widgets" :key="widget.id">
            <div class="bg-white rounded-3xl shadow-lg shadow-black/5 p-6 hover:shadow-xl transition-shadow"
                 :class="{ 'ring-2 ring-blue-500': editMode && selectedWidget === widget.id }"
                 @click="editMode ? selectWidget(widget.id) : null">
                
                <!-- Drag Handle (solo en edit mode) -->
                <div x-show="editMode" class="drag-handle cursor-move mb-4 text-center">
                    <div class="inline-flex space-x-1">
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                    </div>
                </div>
                
                <!-- Widget Content -->
                <div x-show="!editMode">
                    <!-- Dynamic widget content based on type -->
                    <div x-html="widget.content"></div>
                </div>
                
                <!-- Edit Mode Controls -->
                <div x-show="editMode" class="space-y-3">
                    <h3 class="font-semibold text-gray-900" x-text="widget.title"></h3>
                    
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="widget.visible" class="mr-2">
                            <span class="text-sm">Visible</span>
                        </label>
                        
                        <button @click="removeWidget(widget.id)" 
                                class="text-red-500 hover:text-red-700 text-sm">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Add Widget Button (edit mode) -->
        <div x-show="editMode" 
             class="border-2 border-dashed border-gray-300 rounded-3xl p-6 flex items-center justify-center min-h-[200px] hover:border-blue-500 transition-colors cursor-pointer"
             @click="showWidgetLibrary = true">
            <div class="text-center">
                <x-icon name="plus" class="w-8 h-8 text-gray-400 mx-auto mb-2" />
                <p class="text-gray-500">Agregar widget</p>
            </div>
        </div>
    </div>
    
    <!-- Widget Library Modal -->
    <x-modal :show="showWidgetLibrary" @close="showWidgetLibrary = false" title="Agregar Widget">
        <div class="grid grid-cols-2 gap-4">
            <template x-for="availableWidget in availableWidgets" :key="availableWidget.id">
                <div class="p-4 border border-gray-200 rounded-2xl hover:border-blue-500 cursor-pointer transition-colors"
                     @click="addWidget(availableWidget)">
                    <div class="text-center">
                        <x-icon :name="availableWidget.icon" class="w-8 h-8 text-primary-500 mx-auto mb-2" />
                        <h4 class="font-medium" x-text="availableWidget.title"></h4>
                        <p class="text-sm text-gray-500" x-text="availableWidget.description"></p>
                    </div>
                </div>
            </template>
        </div>
    </x-modal>
</div>

<script>
function dashboardConfig() {
    return {
        editMode: false,
        showWidgetLibrary: false,
        selectedWidget: null,
        widgets: @json($userWidgets),
        availableWidgets: [
            { id: 'stats', title: 'Estadísticas', icon: 'bar-chart', description: 'Métricas clave' },
            { id: 'recent-quotes', title: 'Cotizaciones', icon: 'document', description: 'Actividad reciente' },
            { id: 'quick-actions', title: 'Acciones Rápidas', icon: 'zap', description: 'Atajos útiles' },
            { id: 'calendar', title: 'Calendario', icon: 'calendar', description: 'Eventos próximos' }
        ],
        
        toggleEditMode() {
            this.editMode = !this.editMode;
            if (!this.editMode) {
                this.saveLayout();
            }
        },
        
        selectWidget(id) {
            this.selectedWidget = id;
        },
        
        addWidget(widget) {
            const newWidget = {
                ...widget,
                id: Date.now(),
                visible: true,
                content: this.generateWidgetContent(widget.type)
            };
            
            this.widgets.push(newWidget);
            this.showWidgetLibrary = false;
        },
        
        removeWidget(id) {
            this.widgets = this.widgets.filter(w => w.id !== id);
        },
        
        updateWidgetOrder(event) {
            // Update widget order based on drag and drop
            const newOrder = Array.from(event.target.children).map(child => 
                this.widgets.find(w => w.id == child.dataset.widgetId)
            );
            this.widgets = newOrder.filter(Boolean);
        },
        
        async saveLayout() {
            try {
                await this.$wire.saveDashboardLayout(this.widgets);
                this.showSuccessNotification('Layout guardado');
            } catch (error) {
                this.showErrorNotification('Error al guardar layout');
            }
        }
    }
}
</script>
```

#### **2. Atajos de Teclado Globales**
```php
{{-- resources/views/components/keyboard-shortcuts.blade.php --}}
<div x-data="keyboardShortcuts()" class="hidden">
    <!-- Keyboard Shortcuts Help Modal -->
    <x-modal :show="showHelp" @close="showHelp = false" title="Atajos de Teclado">
        <div class="space-y-4">
            <div class="space-y-2">
                <template x-for="shortcut in shortcuts" :key="shortcut.key">
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                        <div class="flex items-center space-x-3">
                            <x-icon :name="shortcut.icon" class="w-4 h-4 text-gray-400" />
                            <span x-text="shortcut.description"></span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <template x-for="key in shortcut.keys" :key="key">
                                <kbd class="px-2 py-1 text-xs bg-gray-100 border border-gray-300 rounded" x-text="key"></kbd>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </x-modal>
    
    <!-- Floating Help Button -->
    <button @click="showHelp = true" 
            class="fixed bottom-4 left-4 z-40 p-3 bg-gray-800 text-white rounded-full shadow-lg hover:bg-gray-700 transition-colors"
            title="Atajos de teclado (?)">
        <x-icon name="help-circle" class="w-5 h-5" />
    </button>
</div>

<script>
function keyboardShortcuts() {
    return {
        showHelp: false,
        shortcuts: [
            { key: '⌘K', description: 'Búsqueda global', icon: 'search', keys: ['⌘', 'K'] },
            { key: '⌘N', description: 'Nuevo registro', icon: 'plus', keys: ['⌘', 'N'] },
            { key: 'ESC', description: 'Cerrar modal', icon: 'x', keys: ['ESC'] },
            { key: '⌘/', description: 'Mostrar ayuda', icon: 'help-circle', keys: ['⌘', '/'] },
            { key: '⌘S', description: 'Guardar', icon: 'save', keys: ['⌘', 'S'] }
        ],
        
        init() {
            document.addEventListener('keydown', (e) => {
                // Global shortcuts
                if ((e.metaKey || e.ctrlKey) && e.key === '/') {
                    e.preventDefault();
                    this.showHelp = true;
                }
                
                // Context-sensitive shortcuts
                this.handleContextShortcuts(e);
            });
        },
        
        handleContextShortcuts(e) {
            // Save shortcut
            if ((e.metaKey || e.ctrlKey) && e.key === 's') {
                e.preventDefault();
                this.triggerSave();
            }
            
            // New record shortcut
            if ((e.metaKey || e.ctrlKey) && e.key === 'n') {
                e.preventDefault();
                this.triggerNew();
            }
        },
        
        triggerSave() {
            // Dispatch save event or call save method
            window.dispatchEvent(new CustomEvent('keyboard-save'));
        },
        
        triggerNew() {
            // Navigate to new record page or open modal
            window.location.href = '/admin/products/create';
        }
    }
}
</script>
```

### **📱 MOBILE-FIRST PREMIUM & RESPONSIVE DESIGN**

#### **1. Responsive Breakpoints System**
```css
/* resources/css/utilities/responsive-system.css */
:root {
  /* Mobile-First Breakpoints */
  --breakpoint-xs: 475px;   /* Extra small phones */
  --breakpoint-sm: 640px;   /* Small phones */
  --breakpoint-md: 768px;   /* Tablets */
  --breakpoint-lg: 1024px;  /* Small desktops */
  --breakpoint-xl: 1280px;  /* Desktops */
  --breakpoint-2xl: 1536px; /* Large desktops */
}

/* Responsive Container */
.container-responsive {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
  padding-left: 1rem;
  padding-right: 1rem;
}

@media (min-width: 640px) {
  .container-responsive { padding-left: 1.5rem; padding-right: 1.5rem; }
}

@media (min-width: 1024px) {
  .container-responsive { padding-left: 2rem; padding-right: 2rem; }
}

@media (min-width: 1280px) {
  .container-responsive { max-width: 1200px; }
}

/* Responsive Grid System */
.grid-responsive {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 640px) {
  .grid-responsive { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (min-width: 768px) {
  .grid-responsive { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (min-width: 1024px) {
  .grid-responsive { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

/* Responsive Typography */
.text-responsive {
  font-size: 0.875rem; /* 14px mobile */
  line-height: 1.5;
}

@media (min-width: 768px) {
  .text-responsive { font-size: 1rem; /* 16px tablet */ }
}

@media (min-width: 1024px) {
  .text-responsive { font-size: 1.125rem; /* 18px desktop */ }
}
```

#### **2. Mobile Navigation System**
```php
{{-- resources/views/components/navigation/mobile-responsive.blade.php --}}
<div x-data="mobileNavigation()" class="lg:hidden">
    <!-- Mobile Header -->
    <div class="bg-white border-b border-gray-200 px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="/logo.png" alt="Helin" class="h-8 w-8">
                <span class="font-semibold text-gray-900">Helin CMS</span>
            </div>
            
            <!-- Mobile Menu Button -->
            <button @click="toggleMobileMenu()" 
                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black bg-opacity-50"
         @click="mobileMenuOpen = false">
        
        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform -translate-x-full"
             x-transition:enter-end="transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform translate-x-0"
             x-transition:leave-end="transform -translate-x-full"
             @click.stop
             class="fixed left-0 top-0 h-full w-72 bg-white shadow-xl overflow-y-auto">
            
            <!-- Mobile Menu Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="/logo.png" alt="Helin" class="h-8 w-8">
                        <span class="font-semibold text-gray-900">Menú</span>
                    </div>
                    <button @click="mobileMenuOpen = false" 
                            class="p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu Items -->
            <nav class="p-4 space-y-2">
                <template x-for="item in menuItems" :key="item.id">
                    <div x-data="{ submenuOpen: false }">
                        <!-- Main Menu Item -->
                        <div class="flex items-center justify-between">
                            <a href="{{ item.url }}" 
                               wire:navigate
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <x-icon :name="item.icon" class="w-5 h-5 text-gray-500" />
                                <span class="text-gray-900">{{ item.name }}</span>
                            </a>
                            
                            <!-- Submenu Toggle (if has children) -->
                            <template x-if="item.children && item.children.length > 0">
                                <button @click="submenuOpen = !submenuOpen" 
                                        class="p-1 rounded-lg hover:bg-gray-100">
                                    <svg class="w-4 h-4 transform transition-transform"
                                         :class="{ 'rotate-180': submenuOpen }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </template>
                        </div>
                        
                        <!-- Submenu -->
                        <template x-if="item.children && item.children.length > 0">
                            <div x-show="submenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="ml-4 mt-2 space-y-1">
                                <template x-for="child in item.children" :key="child.id">
                                    <a href="{{ child.url }}" 
                                       wire:navigate
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                        {{ child.name }}
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </nav>
        </div>
    </div>
</div>

<script>
function mobileNavigation() {
    return {
        mobileMenuOpen: false,
        menuItems: @json($menuItems),
        
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
            
            // Prevent body scroll when menu is open
            if (this.mobileMenuOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    }
}
</script>
```

#### **3. Responsive Cards System**
```php
{{-- resources/views/components/cards/responsive-card.blade.php --}}
@props([
    'title',
    'description' => null,
    'image' => null,
    'actions' => [],
    'variant' => 'default'
])

@php
    $variantClasses = [
        'default' => 'bg-white rounded-2xl shadow-lg',
        'elevated' => 'bg-white rounded-3xl shadow-xl',
        'flat' => 'bg-gray-50 rounded-2xl',
        'bordered' => 'bg-white rounded-2xl border border-gray-200'
    ];
@endphp

<div class="{{ $variantClasses[$variant] ?? $variantClasses['default'] }} 
            overflow-hidden
            transition-all duration-300
            hover:shadow-xl
            hover:scale-[1.02]
            active:scale-[0.98]">
    
    <!-- Mobile Layout (Stacked) -->
    <div class="sm:hidden">
        <!-- Image (Mobile) -->
        @if($image)
            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">
            </div>
        @endif
        
        <!-- Content (Mobile) -->
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
            
            @if($description)
                <p class="text-gray-600 text-sm mb-4">{{ $description }}</p>
            @endif
            
            <!-- Actions (Mobile) -->
            @if(!empty($actions))
                <div class="flex flex-col space-y-2">
                    @foreach($actions as $action)
                        <x-button :variant="$action['variant'] ?? 'secondary'" 
                                   :size="$action['size'] ?? 'md'"
                                   class="w-full justify-center">
                            {{ $action['label'] }}
                        </x-button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Desktop Layout (Side by Side) -->
    <div class="hidden sm:flex sm:h-full">
        <!-- Image (Desktop) -->
        @if($image)
            <div class="sm:w-1/3 lg:w-2/5">
                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
            </div>
        @endif
        
        <!-- Content (Desktop) -->
        <div class="flex-1 p-6 sm:p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $title }}</h3>
            
            @if($description)
                <p class="text-gray-600 mb-6">{{ $description }}</p>
            @endif
            
            <!-- Actions (Desktop) -->
            @if(!empty($actions))
                <div class="flex items-center space-x-3">
                    @foreach($actions as $action)
                        <x-button :variant="$action['variant'] ?? 'primary'" 
                                   :size="$action['size'] ?? 'md'">
                            {{ $action['label'] }}
                        </x-button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
```

#### **4. Responsive Tables System**
```php
{{-- resources/views/components/data/responsive-table.blade.php --}}
@props([
    'headers' => [],
    'data' => [],
    'actions' => true
])

<!-- Mobile View (Card Layout) -->
<div class="sm:hidden space-y-4">
    @forelse($data as $item)
        <div class="bg-white rounded-2xl shadow-md p-4 space-y-3">
            <!-- Mobile Header -->
            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-gray-900">{{ $item[$headers[0]['key']] }}</h4>
                
                @if($actions)
                    <div class="flex items-center space-x-2">
                        <button class="p-1 text-gray-400 hover:text-gray-600">
                            <x-icon name="edit" class="w-4 h-4" />
                        </button>
                        <button class="p-1 text-gray-400 hover:text-red-600">
                            <x-icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>
                @endif
            </div>
            
            <!-- Mobile Details -->
            <div class="space-y-2">
                @foreach(array_slice($headers, 1) as $header)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">{{ $header['label'] }}:</span>
                        <span class="text-sm text-gray-900">{{ $item[$header['key']] ?? '-' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <x-icon name="inbox" class="w-12 h-12 mx-auto mb-2" />
            <p>No hay registros disponibles</p>
        </div>
    @endforelse
</div>

<!-- Desktop View (Table Layout) -->
<div class="hidden sm:block">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $header['label'] }}
                            </th>
                        @endforeach
                        
                        @if($actions)
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        @endif
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            @foreach($headers as $header)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item[$header['key']] ?? '' }}
                                </td>
                            @endforeach
                            
                            @if($actions)
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button class="p-1 text-gray-400 hover:text-gray-600">
                                            <x-icon name="edit" class="w-4 h-4" />
                                        </button>
                                        <button class="p-1 text-gray-400 hover:text-red-600">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center space-y-4">
                                    <x-icon name="inbox" class="w-12 h-12 text-gray-400" />
                                    <p class="text-gray-500">No hay registros disponibles</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
```

#### **5. Touch Gestures System**
```php
{{-- resources/views/components/mobile/touch-gestures.blade.php --}}
<div x-data="touchGestures()" 
     @touchstart="handleTouchStart($event)"
     @touchmove="handleTouchMove($event)"
     @touchend="handleTouchEnd($event)"
     class="swiper-container">
    
    <!-- Swipeable Cards -->
    <div class="overflow-hidden">
        <div class="flex transition-transform duration-300 ease-out"
             :style="`transform: translateX(${translateX}px)`">
            
            <template x-for="(item, index) in items" :key="item.id">
                <div class="w-full flex-shrink-0 p-4">
                    <div class="bg-white rounded-3xl shadow-lg p-6">
                        <h3 x-text="item.title"></h3>
                        <p x-text="item.description"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Swipe Indicators -->
    <div class="flex justify-center space-x-2 mt-4">
        <template x-for="(item, index) in items" :key="index">
            <div class="w-2 h-2 rounded-full transition-colors"
                 :class="index === currentIndex ? 'bg-primary-500' : 'bg-gray-300'">
            </div>
        </template>
    </div>
</div>

<script>
function touchGestures() {
    return {
        startX: 0,
        currentX: 0,
        translateX: 0,
        currentIndex: 0,
        items: [
            { id: 1, title: 'Producto 1', description: 'Descripción 1' },
            { id: 2, title: 'Producto 2', description: 'Descripción 2' },
            { id: 3, title: 'Producto 3', description: 'Descripción 3' }
        ],
        
        handleTouchStart(e) {
            this.startX = e.touches[0].clientX;
        },
        
        handleTouchMove(e) {
            this.currentX = e.touches[0].clientX;
            const diff = this.currentX - this.startX;
            
            // Limit swipe distance
            if (Math.abs(diff) < 100) {
                this.translateX = diff;
            }
        },
        
        handleTouchEnd(e) {
            const diff = this.currentX - this.startX;
            const threshold = 50; // Minimum swipe distance
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0 && this.currentIndex > 0) {
                    // Swipe right - previous
                    this.currentIndex--;
                } else if (diff < 0 && this.currentIndex < this.items.length - 1) {
                    // Swipe left - next
                    this.currentIndex++;
                }
            }
            
            // Snap to position
            this.translateX = -this.currentIndex * 100;
        }
    }
}
</script>
```

#### **6. Responsive Form System**
```php
{{-- resources/views/components/forms/responsive-form.blade.php --}}
<div class="space-y-6">
    <!-- Mobile: Single Column -->
    <div class="sm:hidden space-y-4">
        @foreach($fields as $field)
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    {{ $field['label'] }}
                    @if($field['required']) <span class="text-red-500">*</span> @endif
                </label>
                
                @switch($field['type'])
                    @case('text')
                        <input type="text" 
                               name="{{ $field['name'] }}"
                               placeholder="{{ $field['placeholder'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @break
                        
                    @case('textarea')
                        <textarea name="{{ $field['name'] }}"
                                  rows="4"
                                  placeholder="{{ $field['placeholder'] ?? '' }}"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @break
                        
                    @case('select')
                        <select name="{{ $field['name'] }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona una opción</option>
                            @foreach($field['options'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @break
                @endswitch
            </div>
        @endforeach
    </div>
    
    <!-- Desktop: Multi-Column Grid -->
    <div class="hidden sm:grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($fields as $field)
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    {{ $field['label'] }}
                    @if($field['required']) <span class="text-red-500">*</span> @endif
                </label>
                
                @switch($field['type'])
                    @case('text')
                        <input type="text" 
                               name="{{ $field['name'] }}"
                               placeholder="{{ $field['placeholder'] ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @break
                        
                    @case('textarea')
                        <textarea name="{{ $field['name'] }}"
                                  rows="3"
                                  placeholder="{{ $field['placeholder'] ?? '' }}"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @break
                        
                    @case('select')
                        <select name="{{ $field['name'] }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona una opción</option>
                            @foreach($field['options'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @break
                @endswitch
            </div>
        @endforeach
    </div>
    
    <!-- Responsive Actions -->
    <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
        <button type="button" 
                class="w-full sm:w-auto px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors">
            Cancelar
        </button>
        <button type="submit" 
                class="w-full sm:w-auto px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
            Guardar
        </button>
    </div>
</div>
```

### **🎭 ERRORES HUMANOS Y RECUPERACIÓN**

#### **1. Error States con Soluciones**
```php
{{-- resources/views/components/error/recovery.blade.php --}}
<div x-data="errorRecovery()" class="space-y-4">
    <!-- Network Error with Retry -->
    <div x-show="error.type === 'network'" 
         x-transition:enter="transition ease-out duration-200"
         class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                    <x-icon name="wifi-off" class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-amber-800 font-medium">Conexión interrumpida</h3>
                <p class="text-amber-600 text-sm mt-1">
                    Parece que tienes problemas de conexión. Estamos intentando reconectar...
                </p>
                <div class="mt-3 flex items-center space-x-3">
                    <button @click="retry()" 
                            class="bg-amber-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-amber-600">
                        Reintentar ahora
                    </button>
                    <span class="text-amber-600 text-sm" x-show="retryCount > 0">
                        Intento {{ retryCount }} de 3
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Validation Error with Suggestions -->
    <div x-show="error.type === 'validation'" 
         class="bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <x-icon name="alert-triangle" class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-red-800 font-medium">Algo necesita tu atención</h3>
                <div class="mt-2 space-y-1">
                    <template x-for="error in error.errors" :key="error.field">
                        <div class="flex items-center space-x-2">
                            <span class="text-red-600 text-sm" x-text="error.message"></span>
                            <button @click="focusField(error.field)" 
                                    class="text-red-500 hover:text-red-700 text-sm underline">
                                Corregir
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function errorRecovery() {
    return {
        error: {},
        retryCount: 0,
        maxRetries: 3,
        
        handleNetworkError() {
            this.error = {
                type: 'network',
                message: 'Error de conexión'
            };
            
            // Auto-retry after 3 seconds
            setTimeout(() => {
                if (this.retryCount < this.maxRetries) {
                    this.retry();
                }
            }, 3000);
        },
        
        async retry() {
            this.retryCount++;
            
            try {
                await this.$wire.retry();
                this.error = {};
                this.retryCount = 0;
            } catch (error) {
                if (this.retryCount >= this.maxRetries) {
                    this.error.message = 'No pudimos reconectar. Por favor, recarga la página.';
                }
            }
        },
        
        focusField(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.focus();
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }
}
</script>
```

---

**OBJETIVO FINAL:** El usuario debe sentir que está utilizando una herramienta de software de clase mundial como Vercel o Notion, pero con la calidez y personalización de un producto hecho por humanos para humanos.
