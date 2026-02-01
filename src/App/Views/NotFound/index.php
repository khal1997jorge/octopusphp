<?php
/**
 * Vista 404 - Not Found
 *
 * @var string $originalRoute
 * @var string $routeToRedirect
 * @var string $buttonText
 */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Octopus | Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/NotFound/index.css">
</head>
<body>
<div class="container">
    <h1>404</h1>
    <h2>Página no encontrada</h2>

    <p>
        Lo siento, la página que buscas no existe.<br>
        Por favor presiona el siguiente botón para ser redireccionado:
    </p>

    <div class="route">
        Error - 404 <strong><?= $originalRoute ?></strong>
    </div>

    <a class="button" href="<?= $routeToRedirect ?>">
        <?= $buttonText ?>
    </a>
</div>
</body>
</html>
