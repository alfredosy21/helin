<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Messages Utility Class
 *
 * Centralized message constants for consistent user feedback
 * throughout the Helin Latam CMS application.
 *
 * @package App\Utils
 * @author  Helin Latam Development Team
 * @version 1.0.0
 */
class Messages
{
    // SUCCESS MESSAGES
    /** @var string Record created successfully */
    const LABEL_SUCCESS_CREATE = 'Registro guardado correctamente';

    /** @var string Changes updated successfully */
    const LABEL_SUCCESS_UPDATE = 'Cambios actualizados con éxito';

    /** @var string Record deleted successfully */
    const LABEL_SUCCESS_DELETE = 'Registro eliminado correctamente';

    /** @var string File uploaded successfully */
    const LABEL_SUCCESS_UPLOAD = 'Archivo subido correctamente';

    /** @var string Password reset successfully */
    const LABEL_SUCCESS_PASSWORD_RESET = 'Contraseña restablecida correctamente';

    /** @var string Email sent successfully */
    const LABEL_SUCCESS_EMAIL_SENT = 'Correo electrónico enviado correctamente';

    /** @var string Data imported successfully */
    const LABEL_SUCCESS_IMPORT = 'Datos importados correctamente';

    /** @var string Settings updated successfully */
    const LABEL_SUCCESS_SETTINGS = 'Configuración actualizada correctamente';

    // ERROR MESSAGES
    /** @var string General error occurred */
    const LABEL_ERROR_GENERAL = 'Ocurrió un error al procesar la solicitud';

    /** @var string Validation errors in form */
    const LABEL_ERROR_VALIDATION = 'Por favor, revisa los errores en el formulario';

    /** @var string File upload error */
    const LABEL_ERROR_UPLOAD = 'Error al subir el archivo';

    /** @var string Invalid login credentials */
    const LABEL_ERROR_LOGIN = 'Credenciales incorrectas';

    /** @var string Account inactive */
    const auth_inactive = 'Tu cuenta está inactiva. Por favor, contacta al administrador.';

    /** @var string Account not verified */
    const auth_unverified = 'Tu cuenta no ha sido verificada. Por favor, revisa tu correo electrónico.';

    /** @var string Account locked */
    const auth_locked = 'Tu cuenta está bloqueada. Por favor, contacta al administrador.';

    /** @var string Account inactive for password reset */
    const password_reset_inactive = 'Tu cuenta está inactiva. Por favor, contacta al administrador.';

    /** @var string Too many password reset attempts */
    const password_reset_too_many_attempts = 'Demasiados intentos. Por favor, espera antes de intentar nuevamente.';

    /** @var string Permission denied */
    const LABEL_ERROR_PERMISSION = 'No tiene permisos para realizar esta acción';

    /** @var string Record not found */
    const LABEL_ERROR_NOT_FOUND = 'Registro no encontrado';

    /** @var string Network connection error */
    const LABEL_ERROR_NETWORK = 'Error de conexión. Por favor, verifica tu internet';

    /** @var string File type not allowed */
    const LABEL_ERROR_FILE_TYPE = 'Tipo de archivo no permitido';

    /** @var string File size exceeded */
    const LABEL_ERROR_FILE_SIZE = 'El archivo excede el tamaño máximo permitido';

    // WARNING MESSAGES
    /** @var string Record is inactive */
    const LABEL_WARNING_INACTIVE = 'El registro está inactivo';

    /** @var string Field is required */
    const LABEL_WARNING_REQUIRED = 'Este campo es obligatorio';

    /** @var string Unsaved changes warning */
    const LABEL_WARNING_UNSAVED = 'Tienes cambios sin guardar';

    /** @var string Delete confirmation */
    const LABEL_WARNING_DELETE = '¿Estás seguro de eliminar este registro? Esta acción no se puede deshacer.';

    /** @var string Duplicate record warning */
    const LABEL_WARNING_DUPLICATE = 'Ya existe un registro con estos datos';

    // INFO MESSAGES
    /** @var string Loading indicator */
    const LABEL_INFO_LOADING = 'Cargando...';

    /** @var string Processing request */
    const LABEL_INFO_PROCESSING = 'Procesando solicitud...';

    /** @var string Saving data */
    const LABEL_INFO_SAVING = 'Guardando...';

    /** @var string Uploading file */
    const LABEL_INFO_UPLOADING = 'Subiendo archivo...';

    /** @var string No data available */
    const LABEL_INFO_NO_DATA = 'No hay registros disponibles';

    /** @var string Search results empty */
    const LABEL_INFO_NO_RESULTS = 'No se encontraron resultados para tu búsqueda';

    // AUTHENTICATION MESSAGES
    /** @var string Login successful */
    const LABEL_AUTH_LOGIN_SUCCESS = 'Inicio de sesión exitoso';

    /** @var string Logout successful */
    const LABEL_AUTH_LOGOUT_SUCCESS = 'Sesión cerrada correctamente';

    /** @var string Account created */
    const LABEL_AUTH_ACCOUNT_CREATED = 'Cuenta creada correctamente';

    /** @var string Password reset email sent */
    const LABEL_AUTH_PASSWORD_RESET_SENT = 'Se ha enviado un correo para restablecer tu contraseña';

    // VALIDATION MESSAGES
    /** @var string Email already exists */
    const LABEL_VALIDATION_EMAIL_EXISTS = 'El correo electrónico ya está registrado';

    /** @var string Invalid email format */
    const LABEL_VALIDATION_EMAIL_INVALID = 'El formato del correo electrónico no es válido';

    /** @var string Password too short */
    const LABEL_VALIDATION_PASSWORD_SHORT = 'La contraseña debe tener al menos 8 caracteres';

    /** @var string Password weak */
    const LABEL_VALIDATION_PASSWORD_WEAK = 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos';

    /** @var string Required field */
    const LABEL_VALIDATION_REQUIRED = 'Este campo es obligatorio';

    /** @var string Invalid format */
    const LABEL_VALIDATION_INVALID_FORMAT = 'El formato no es válido';

    // FILE MESSAGES
    /** @var string Image uploaded successfully */
    const LABEL_FILE_IMAGE_UPLOADED = 'Imagen subida correctamente';

    /** @var string Document uploaded successfully */
    const LABEL_FILE_DOCUMENT_UPLOADED = 'Documento subido correctamente';

    /** @var string File deleted successfully */
    const LABEL_FILE_DELETED = 'Archivo eliminado correctamente';

    /** @var string Invalid image format */
    const LABEL_FILE_INVALID_IMAGE = 'Formato de imagen no válido. Usa JPG, PNG o WebP';

    /** @var string Invalid document format */
    const LABEL_FILE_INVALID_DOCUMENT = 'Formato de documento no válido. Usa PDF';

    // IMPORT/EXPORT MESSAGES
    /** @var string Import started */
    const LABEL_IMPORT_STARTED = 'Importación iniciada';

    /** @var string Import completed */
    const LABEL_IMPORT_COMPLETED = 'Importación completada exitosamente';

    /** @var string Import failed */
    const LABEL_IMPORT_FAILED = 'La importación falló. Por favor, revisa el archivo';

    /** @var string Export ready */
    const LABEL_EXPORT_READY = 'Archivo exportado correctamente';

    /** @var string Invalid CSV format */
    const LABEL_IMPORT_INVALID_CSV = 'El archivo CSV no tiene el formato correcto';

    // SYSTEM MESSAGES
    /** @var string System maintenance */
    const LABEL_SYSTEM_MAINTENANCE = 'Sistema en mantenimiento. Por favor, intenta más tarde';

    /** @var string Feature not available */
    const LABEL_SYSTEM_FEATURE_UNAVAILABLE = 'Esta función no está disponible actualmente';

    /** @var string Session expired */
    const LABEL_SYSTEM_SESSION_EXPIRED = 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente';

    /** @var string Session expired for auth */
    const auth_session_expired = 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.';

    /**
     * Get message by constant name
     *
     * @param string $key The constant key
     * @return string The message text or default message if not found
     */
    public static function get(string $key): string
    {
        try {
            return constant("self::{$key}");
        } catch (\Throwable $e) {
            return 'Mensaje no encontrado';
        }
    }

    /**
     * Get all success messages
     *
     * @return array<string> Array of all success message constants
     */
    public static function getSuccessMessages(): array
    {
        return [
            self::LABEL_SUCCESS_CREATE,
            self::LABEL_SUCCESS_UPDATE,
            self::LABEL_SUCCESS_DELETE,
            self::LABEL_SUCCESS_UPLOAD,
            self::LABEL_SUCCESS_PASSWORD_RESET,
            self::LABEL_SUCCESS_EMAIL_SENT,
            self::LABEL_SUCCESS_IMPORT,
            self::LABEL_SUCCESS_SETTINGS,
        ];
    }

    /**
     * Get all error messages
     *
     * @return array<string> Array of all error message constants
     */
    public static function getErrorMessages(): array
    {
        return [
            self::LABEL_ERROR_GENERAL,
            self::LABEL_ERROR_VALIDATION,
            self::LABEL_ERROR_UPLOAD,
            self::LABEL_ERROR_LOGIN,
            self::LABEL_ERROR_PERMISSION,
            self::LABEL_ERROR_NOT_FOUND,
            self::LABEL_ERROR_NETWORK,
            self::LABEL_ERROR_FILE_TYPE,
            self::LABEL_ERROR_FILE_SIZE,
        ];
    }

    /**
     * Get all warning messages
     *
     * @return array<string> Array of all warning message constants
     */
    public static function getWarningMessages(): array
    {
        return [
            self::LABEL_WARNING_INACTIVE,
            self::LABEL_WARNING_REQUIRED,
            self::LABEL_WARNING_UNSAVED,
            self::LABEL_WARNING_DELETE,
            self::LABEL_WARNING_DUPLICATE,
        ];
    }

    /**
     * Get all info messages
     *
     * @return array<string> Array of all info message constants
     */
    public static function getInfoMessages(): array
    {
        return [
            self::LABEL_INFO_LOADING,
            self::LABEL_INFO_PROCESSING,
            self::LABEL_INFO_SAVING,
            self::LABEL_INFO_UPLOADING,
            self::LABEL_INFO_NO_DATA,
            self::LABEL_INFO_NO_RESULTS,
        ];
    }
}
