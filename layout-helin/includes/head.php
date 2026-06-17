<?php
/**
 * Head Include - Configuración base HTML
 * Variables esperadas: $pageTitle (opcional)
 */
$pageTitle = isset($pageTitle) ? $pageTitle : 'Helin - Material Dental';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/helin-components.css">
    <link rel="stylesheet" href="css/custom-container.css">
    <?php if(isset($customCSS)) echo $customCSS; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        turquesa: '#00A3A0',
                        'turquesa-light': '#4DD4D1',
                        'turquesa-dark': '#007A78'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
