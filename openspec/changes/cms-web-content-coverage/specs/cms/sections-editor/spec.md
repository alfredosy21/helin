## Purpose

Permite a los administradores editar todo el contenido estructurado de las secciones del sitio (subtítulos, descripciones, items JSON, botones, layout, estilo de iconos) desde el CMS, sin tocar código, arreglando además el bug que impedía guardar cambios.

## ADDED Requirements

### Requirement: Guardado de secciones funcional
El sistema SHALL permitir guardar cambios en cualquier sección desde el CMS sin que la petición se interrumpa por sentencias de depuración.

#### Scenario: Edición de sección existente
- **WHEN** el administrador edita una sección y hace clic en guardar
- **THEN** el sistema persiste los cambios y muestra un mensaje de éxito
- **AND** no se ejecuta ninguna sentencia `dd()` o `dump()` que interrumpa el flujo

#### Scenario: Error de validación al guardar
- **WHEN** el administrador envía datos inválidos al guardar una sección
- **THEN** el sistema muestra los errores de validación sin perder los datos ingresados

### Requirement: Exposición de campos estructurados en el modelo
El modelo de secciones SHALL permitir asignación masiva de los campos `subtitle`, `description`, `items`, `buttons`, `layout_type` e `icon_style` que ya existen en la base de datos.

#### Scenario: Asignación masiva de campos estructurados
- **WHEN** el controlador intenta guardar `subtitle`, `description`, `items`, `buttons`, `layout_type` e `icon_style` mediante asignación masiva
- **THEN** todos los campos se persisten correctamente en la base de datos

### Requirement: Editor de items JSON
El sistema SHALL proporcionar un editor visual de tipo repeater para gestionar los items JSON de una sección, permitiendo añadir, editar, reordenar y eliminar items sin que el administrador manipule JSON crudo.

#### Scenario: Añadir un item a una sección
- **WHEN** el administrador hace clic en "Añadir item" y completa los campos (icon, title, description, order)
- **THEN** el sistema añade el item al JSON de items de la sección
- **AND** el item se persiste al guardar

#### Scenario: Reordenar items
- **WHEN** el administrador arrastra un item a una nueva posición
- **THEN** el sistema actualiza el orden de los items en el JSON

#### Scenario: Eliminar un item
- **WHEN** el administrador elimina un item del repeater
- **THEN** el item se quita del JSON de items y no se persiste al guardar

### Requirement: Editor de botones JSON
El sistema SHALL proporcionar un editor visual para gestionar los botones de una sección (texto y URL), permitiendo añadir, editar y eliminar botones.

#### Scenario: Gestionar botones de una sección
- **WHEN** el administrador añade o edita un botón con su texto y URL
- **THEN** el sistema persiste el botón en el JSON de botones de la sección

### Requirement: Gestión de visibilidad
El sistema SHALL permitir controlar la visibilidad de una sección mediante los campos `status` y `status_content` desde el CMS.

#### Scenario: Ocultar contenido de una sección
- **WHEN** el administrador desactiva `status_content` de una sección
- **THEN** la sección no muestra su contenido en la web pública aunque la sección exista
