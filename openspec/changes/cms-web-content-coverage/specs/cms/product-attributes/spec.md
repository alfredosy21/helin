## Purpose

Permite a los administradores gestionar atributos de productos (diámetro, longitud, material, etc.) y sus valores (variantes) desde el CMS, reutilizando las tablas `attributes`, `attribute_values` y `attribute_value_product` ya existentes, para que el selector de dimensiones de la ficha de producto consuma datos dinámicos.

## ADDED Requirements

### Requirement: Listado de atributos
El sistema SHALL mostrar un listado paginado de todos los atributos de producto, con búsqueda por nombre.

#### Scenario: Ver listado de atributos
- **WHEN** el administrador accede al submódulo de Atributos
- **THEN** el sistema muestra todos los atributos con su nombre, unidad, estado activo y número de valores asociados

### Requirement: Crear atributo
El sistema SHALL permitir crear un atributo con nombre, unidad de medida (opcional), descripción y estado activo.

#### Scenario: Crear atributo válido
- **WHEN** el administrador completa el formulario con un nombre
- **THEN** el sistema crea el atributo y muestra un mensaje de éxito

### Requirement: Editar atributo
El sistema SHALL permitir editar cualquier campo de un atributo existente.

#### Scenario: Cambiar unidad de un atributo
- **WHEN** el administrador cambia la unidad de medida de un atributo y guarda
- **THEN** el sistema persiste el cambio

### Requirement: Eliminar atributo
El sistema SHALL permitir eliminar un atributo que no tenga valores asociados.

#### Scenario: Eliminar atributo sin valores
- **WHEN** el administrador elimina un atributo sin valores asociados
- **THEN** el sistema borra el registro

#### Scenario: Eliminar atributo con valores
- **WHEN** el administrador intenta eliminar un atributo con valores asociados
- **THEN** el sistema bloquea la eliminación y muestra un mensaje explicativo

### Requirement: Gestión de valores de atributo
El sistema SHALL permitir gestionar los valores de cada atributo (crear, editar, eliminar, reordenar, activar/desactivar).

#### Scenario: Añadir valor a un atributo
- **WHEN** el administrador añade un valor (ej. "Ø3.3 mm") a un atributo
- **THEN** el sistema crea el valor asociado al atributo

#### Scenario: Reordenar valores
- **WHEN** el administrador arrastra un valor a una nueva posición
- **THEN** el sistema actualiza el orden de los valores

### Requirement: Asociar valores a productos
El sistema SHALL permitir asociar uno o más valores de atributo a un producto desde el editor de productos del CMS.

#### Scenario: Asociar dimensión a producto
- **WHEN** el administrador selecciona los valores de "diámetro" aplicables a un producto y guarda
- **THEN** el sistema persiste la asociación en la tabla pivote

#### Scenario: Quitar dimensión de producto
- **WHEN** el administrador desmarca un valor de un producto y guarda
- **THEN** el sistema elimina la asociación

### Requirement: Selector de dimensiones dinámico en la web
La ficha de producto de la web pública SHALL mostrar las dimensiones/variantes disponibles a partir de los `attribute_values` asociados al producto, en lugar de un array JavaScript hardcodeado.

#### Scenario: Producto con dimensiones
- **WHEN** un usuario visita la ficha de un producto que tiene valores de atributo asociados
- **THEN** el selector muestra las dimensiones dinámicamente desde la base de datos

#### Scenario: Producto sin dimensiones
- **WHEN** un usuario visita la ficha de un producto sin valores de atributo asociados
- **THEN** el selector no se muestra o muestra un mensaje indicando que no hay variantes
