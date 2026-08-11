## Purpose

Permite a los administradores gestionar los números de WhatsApp asociados a cada estado (incluyendo el nombre del ejecutivo y estado activo) desde el CMS, reutilizando la tabla `whatsapp_numbers` ya existente, para que la web pública consuma datos dinámicos en lugar de arrays hardcodeados.

## ADDED Requirements

### Requirement: Listado de números de WhatsApp
El sistema SHALL mostrar un listado paginado de todos los números de WhatsApp registrados, con búsqueda por número, nombre del ejecutivo o estado asociado.

#### Scenario: Ver listado de números
- **WHEN** el administrador accede al submódulo de WhatsApp Numbers
- **THEN** el sistema muestra todos los números con su estado asociado, ejecutivo, descripción y estado activo

#### Scenario: Buscar un número
- **WHEN** el administrador escribe un término de búsqueda
- **THEN** el sistema filtra los resultados por número, ejecutivo o estado

### Requirement: Crear número de WhatsApp
El sistema SHALL permitir crear un nuevo número de WhatsApp asociado a un estado, con número de teléfono, nombre del ejecutivo, descripción y estado activo.

#### Scenario: Crear número válido
- **WHEN** el administrador completa el formulario con un número válido y un estado existente
- **THEN** el sistema crea el registro y muestra un mensaje de éxito

#### Scenario: Crear número con datos inválidos
- **WHEN** el administrador envía el formulario sin número o con un estado inexistente
- **THEN** el sistema muestra errores de validación

### Requirement: Editar número de WhatsApp
El sistema SHALL permitir editar cualquier campo de un número de WhatsApp existente.

#### Scenario: Editar ejecutivo de un número
- **WHEN** el administrador cambia el nombre del ejecutivo y guarda
- **THEN** el sistema persiste el cambio

### Requirement: Eliminar número de WhatsApp
El sistema SHALL permitir eliminar un número de WhatsApp que no tenga solicitudes asociadas.

#### Scenario: Eliminar número sin asociaciones
- **WHEN** el administrador elimina un número sin solicitudes asociadas
- **THEN** el sistema borra el registro

#### Scenario: Eliminar número con asociaciones
- **WHEN** el administrador intenta eliminar un número con solicitudes asociadas
- **THEN** el sistema bloquea la eliminación y muestra un mensaje explicativo

### Requirement: Activar/desactivar número
El sistema SHALL permitir activar o desactivar un número de WhatsApp sin eliminarlo.

#### Scenario: Desactivar un número
- **WHEN** el administrador desactiva un número
- **THEN** el número no se ofrece en la web pública pero sigue en la base de datos

### Requirement: URL de WhatsApp generada automáticamente
El sistema SHALL generar la URL de WhatsApp (`https://wa.me/...`) a partir del número de teléfono almacenado.

#### Scenario: Acceder a la URL de WhatsApp
- **WHEN** la web pública necesita el enlace de WhatsApp de un estado
- **THEN** el sistema retorna la URL construida a partir del número almacenado

### Requirement: Reemplazo del array hardcodeado
El controlador web SHALL obtener los datos de pickup/sedes desde la base de datos (`WhatsAppNumber` + `State`) en lugar de un array hardcodeado en el código.

#### Scenario: Carga de la página de solicitud
- **WHEN** un usuario visita la página de solicitud
- **THEN** los estados, ciudades y números de WhatsApp mostrados provienen de la base de datos
- **AND** no hay ningún array `$pickupInfo` hardcodeado en el controlador
