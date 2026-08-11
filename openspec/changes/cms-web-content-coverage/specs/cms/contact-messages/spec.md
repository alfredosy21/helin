## Purpose

Permite a los administradores gestionar los mensajes recibidos desde el formulario de contacto de la web pública, persistiéndolos en base de datos además de enviarlos por email, para que puedan consultarse, marcarse como leídos y eliminarse desde el CMS.

## ADDED Requirements

### Requirement: Persistencia de mensajes de contacto
El sistema SHALL guardar en base de datos todo mensaje enviado desde el formulario de contacto público, además de enviarlo por email.

#### Scenario: Envío de formulario de contacto
- **WHEN** un usuario envía el formulario de contacto con datos válidos
- **THEN** el sistema guarda el mensaje en la tabla `contact_messages` con `is_read = false`
- **AND** envía el email al destinatario configurado

#### Scenario: Envío falla pero el mensaje se guarda
- **WHEN** el envío de email falla por un problema de SMTP
- **THEN** el mensaje ya está guardado en base de datos y puede consultarse desde el CMS

### Requirement: Listado de mensajes
El sistema SHALL mostrar un listado paginado de los mensajes recibidos, ordenados por fecha descendente, con búsqueda por nombre, email o asunto, y filtro por estado leído/no leído.

#### Scenario: Ver mensajes no leídos
- **WHEN** el administrador accede al submódulo de Mensajes de Contacto y filtra por "no leídos"
- **THEN** el sistema muestra solo los mensajes con `is_read = false`

### Requirement: Ver detalle de mensaje
El sistema SHALL permitir ver el detalle completo de un mensaje (nombre, email, teléfono, asunto, mensaje, fecha).

#### Scenario: Abrir un mensaje
- **WHEN** el administrador hace clic en un mensaje
- **THEN** el sistema muestra el detalle completo del mensaje

### Requirement: Marcar como leído
El sistema SHALL permitir marcar un mensaje como leído, automáticamente al abrirlo o manualmente.

#### Scenario: Marcar automáticamente al abrir
- **WHEN** el administrador abre un mensaje no leído
- **THEN** el sistema marca el mensaje como leído (`is_read = true`)

#### Scenario: Marcar manualmente
- **WHEN** el administrador hace clic en "Marcar como leído" sin abrir el mensaje
- **THEN** el sistema marca el mensaje como leído

### Requirement: Eliminar mensaje
El sistema SHALL permitir eliminar un mensaje de contacto.

#### Scenario: Eliminar un mensaje
- **WHEN** el administrador elimina un mensaje
- **THEN** el sistema borra el registro de la base de datos

### Requirement: Permisos
El sistema SHALL verificar que el usuario tiene permisos de administrador antes de acceder a la gestión de mensajes de contacto.

#### Scenario: Acceso sin permisos
- **WHEN** un usuario sin permisos intenta acceder al submódulo
- **THEN** el sistema devuelve error 403
