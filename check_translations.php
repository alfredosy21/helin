<?php

// Script to find missing translation keys in CMS views

$viewsDir = __DIR__ . '/resources/views/cms';
$esLangFile = __DIR__ . '/lang/es/cms.php';
$enLangFile = __DIR__ . '/lang/en/cms.php';

// Load language files
$esTranslations = include $esLangFile;
$enTranslations = include $enLangFile;

// Recursively find all blade files
function findBladeFiles($dir) {
    $files = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            $files = array_merge($files, findBladeFiles($path));
        } elseif (preg_match('/\.blade\.php$/', $item)) {
            $files[] = $path;
        }
    }
    return $files;
}

// Extract all __('cms...') keys from a file
function extractKeys($filePath) {
    $content = file_get_contents($filePath);
    $keys = [];
    // Match __('cms.algo') or __('cms.algo.otro') with single or double quotes
    // Also match variations with spaces
    $pattern = "/__\(\s*['\"](cms\.[^'\"]+)['\"]/";
    if (preg_match_all($pattern, $content, $matches)) {
        foreach ($matches[1] as $key) {
            $keys[] = $key;
        }
    }
    return $keys;
}

// Check if a dotted key exists in a nested array
function keyExists($array, $dottedKey) {
    // Remove 'cms.' prefix
    $key = preg_replace('/^cms\./', '', $dottedKey);
    $parts = explode('.', $key);
    $current = $array;
    foreach ($parts as $part) {
        if (!is_array($current) || !array_key_exists($part, $current)) {
            return false;
        }
        $current = $current[$part];
    }
    return true;
}

// Find all blade files
$bladeFiles = findBladeFiles($viewsDir);

// Extract all keys with their file locations
$allKeys = []; // key => [files]
$keyFiles = []; // key => file (relative)

foreach ($bladeFiles as $file) {
    $relativePath = str_replace(__DIR__ . '/', '', $file);
    $keys = extractKeys($file);
    foreach ($keys as $key) {
        if (!isset($allKeys[$key])) {
            $allKeys[$key] = [];
        }
        if (!in_array($relativePath, $allKeys[$key])) {
            $allKeys[$key][] = $relativePath;
        }
    }
}

// Sort keys
ksort($allKeys);

// Check each key
$missingEs = [];
$missingEn = [];

foreach ($allKeys as $key => $files) {
    if (!keyExists($esTranslations, $key)) {
        $missingEs[$key] = $files;
    }
    if (!keyExists($enTranslations, $key)) {
        $missingEn[$key] = $files;
    }
}

// Output results
echo "=== TOTAL UNIQUE KEYS FOUND: " . count($allKeys) . " ===\n\n";

echo "=== KEYS MISSING IN lang/es/cms.php (" . count($missingEs) . ") ===\n";
foreach ($missingEs as $key => $files) {
    echo "  $key\n";
    foreach ($files as $f) {
        echo "    -> $f\n";
    }
}

echo "\n=== KEYS MISSING IN lang/en/cms.php (" . count($missingEn) . ") ===\n";
foreach ($missingEn as $key => $files) {
    echo "  $key\n";
    foreach ($files as $f) {
        echo "    -> $f\n";
    }
}

// Now search for hardcoded Spanish text in blade files
echo "\n=== HARDCODED SPANISH TEXT IN VIEWS ===\n";

// Common Spanish words/phrases that should be translated
$spanishPatterns = [
    // Common UI words in Spanish (with word boundaries to avoid partial matches)
    '/>\s*(Estado|Acciones|Nombre|Descripción|Imagen|Precio|Stock|Categoría|Marca|Autor|Orden|Color|Slug|Título|Contenido|Botón|URL|Activo|Inactivo|Visible|Oculto|Eliminar|Editar|Guardar|Cancelar|Crear|Nuevo|Nueva|Buscar|Filtrar|Seleccionar|Agregar|Añadir|Quitar|Subir|Bajar|Mostrar|Ocultar|Cerrar|Abrir|Sí|No|Todos|Todas|Ver|Vista|Detalle|Configuración|Opciones|Datos|Información|Teléfono|Correo|Dirección|Horario|Empresa|Sistema|Plataforma|Familia|Línea|Producto|Testimonio|Recurso|Especialidad|Método|Pago|Entrega|Cliente|Usuario|Rol|Permiso|Sección|Página|Menú|Dashboard|Escritorio|Perfil|Cuenta|Sesión|Contraseña|Confirmar|Actualizado|Creado|Eliminado|Guardado|Procesando|Cargando|Guardando|Subiendo|Esperando|Error|Éxito|Correcto|Incorrecto|Requerido|Obligatorio|Opcional|Destacado|Fijado|Publicado|Borrador|Pendiente|Aprobado|Rechazado|Cancelado|Activo|Inactivo|Habilitado|Deshabilitado|Visible|Oculto|Público|Privado|Interno|Externo|Principal|Secundario|Primario|Básico|Avanzado|Simple|Complejo|Total|Parcial|Completo|Incompleto|Válido|Inválido|Disponible|No disponible|Sí|No|True|False)\s*</',
    // Text in placeholders
    '/placeholder\s*=\s*"([^"]*[áéíóúñÁÉÍÓÚÑ][^"]*)"/',
    // Text in title attributes
    '/title\s*=\s*"([^"]*[áéíóúñÁÉÍÓÚÑ][^"]*)"/',
    // Text in option tags
    '/<option[^>]*>\s*([^<]*[áéíóúñÁÉÍÓÚÑ][^<]*)\s*<\/option>/',
    // Spanish text in button text (between tags)
    '/<button[^>]*>\s*([^<]*[áéíóúñÁÉÍÓÚÑ][^<]*)\s*<\/button>/',
    // Spanish text directly in HTML elements (h1-h6, p, span, label, td, th)
    '/<(?:h[1-6]|p|span|label|td|th|li|div)[^>]*>\s*([A-ZÁÉÍÓÚÑ][^<]*[áéíóúñÁÉÍÓÚÑ][^<]*)\s*<\/(?:h[1-6]|p|span|label|td|th|li|div)>/',
];

$hardcodedFindings = [];

foreach ($bladeFiles as $file) {
    $relativePath = str_replace(__DIR__ . '/', '', $file);
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    
    foreach ($lines as $lineNum => $line) {
        // Skip lines that are purely Blade directives or comments
        if (preg_match('/^\s*{{--/', $line) || preg_match('/^\s*@/', $line)) {
            continue;
        }
        
        // Skip lines that contain __() calls (they are already using translations)
        // But check for mixed content (text outside of __())
        
        // Check for Spanish text in HTML content
        // Look for text between > and < that contains Spanish characters and isn't a variable or directive
        if (preg_match_all('/>\s*([A-ZÁÉÍÓÚÑa-záéíóúñ][^<>{}]*[áéíóúñÁÉÍÓÚÑ][^<>{}]*)\s*</u', $line, $matches)) {
            foreach ($matches[1] as $match) {
                $match = trim($match);
                // Skip if it's just a variable or very short
                if (strlen($match) < 3) continue;
                // Skip if it looks like a class name or attribute
                if (preg_match('/^(class|style|id|src|href|alt|width|height|type|value|name|placeholder|title|data-|wire:|x-|d=|fill|stroke|viewBox)/', $match)) continue;
                // Skip SVG path data
                if (preg_match('/^[MmLlHhVvCcSsQqTtAaZz0-9.,\s\-]+$/', $match)) continue;
                // Skip if it contains $ or {{ (Blade variables)
                if (strpos($match, '$') !== false || strpos($match, '{{') !== false) continue;
                // Skip common non-Spanish technical terms
                if (preg_match('/^(true|false|null|undefined|auto|none|block|flex|grid|hidden|visible|absolute|relative|fixed|sticky)/i', $match)) continue;
                
                $hardcodedFindings[] = [
                    'file' => $relativePath,
                    'line' => $lineNum + 1,
                    'text' => $match,
                    'context' => trim($line),
                ];
            }
        }
        
        // Also check for Spanish text in placeholder attributes (not inside __())
        if (preg_match_all('/placeholder\s*=\s*"([^"]*[áéíóúñÁÉÍÓÚÑ][^"]*)"/u', $line, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[1] as $match) {
                $text = trim($match[0]);
                if (strlen($text) < 3) continue;
                $hardcodedFindings[] = [
                    'file' => $relativePath,
                    'line' => $lineNum + 1,
                    'text' => $text,
                    'context' => trim($line),
                ];
            }
        }
        
        // Check for Spanish text in description attributes (common in this codebase)
        if (preg_match_all('/description\s*=\s*"([^"]*[áéíóúñÁÉÍÓÚÑ][^"]*)"/u', $line, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[1] as $match) {
                $text = trim($match[0]);
                if (strlen($text) < 3) continue;
                $hardcodedFindings[] = [
                    'file' => $relativePath,
                    'line' => $lineNum + 1,
                    'text' => $text,
                    'context' => trim($line),
                ];
            }
        }
    }
}

// Deduplicate and sort
$uniqueFindings = [];
foreach ($hardcodedFindings as $f) {
    $key = $f['file'] . ':' . $f['line'] . ':' . $f['text'];
    if (!isset($uniqueFindings[$key])) {
        $uniqueFindings[$key] = $f;
    }
}

echo "Found " . count($uniqueFindings) . " hardcoded Spanish text instances:\n\n";
$lastFile = '';
foreach ($uniqueFindings as $f) {
    if ($f['file'] !== $lastFile) {
        echo "\n--- " . $f['file'] . " ---\n";
        $lastFile = $f['file'];
    }
    echo "  Line " . $f['line'] . ": \"" . $f['text'] . "\"\n";
}
