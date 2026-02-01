<?php
use App\Core\Enums\Views;
use App\Core\Enums\Routes;

/**
 * @var string $page //puede ser login.php o register.php
 */
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Archivos estilos css -->
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/header.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/footer.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/all.min.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Auth/slider.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Auth/index.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Auth/index.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Auth/index.css">
    <!-- Importaciones de Helpers y JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= ASSETS_PATH ?>/js/JQueryHelper.js"></script>
    <script src="<?= ASSETS_PATH ?>/js/FormHelper.js"></script>

    <title>Octopus</title>
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            JQueryHelper.InitializeNavbar();
        });
    </script>
    <header>
        <div class="logo">
            <a href="#">
                <img src="<?= ASSETS_PATH ?>/img/lofofinall.png" alt="logo">
            </a>
        </div>

        <div class="menu"><i class="fa-solid fa-bars"></i></div>
        <nav class="navegacion">
            <ul>
                <li>
                    <a href="">Informacion</a>
                </li>
                <li>
                    <a href="">Condiciones</a>
                </li>
                <li>
                    <a href="">Nosotros</a>
                </li>
                <li>
                    <a href="" class="cuadrado">
                        <i class="fa-solid fa-business-time"></i> Politica
                    </a>
                </li>
                <li>
                    <a href="<?= Routes::Login ?>" class="cuadrado">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrar
                    </a>
                </li>
            </ul>
        </nav>
    </header>


    <?php load_view($page) ?>

    <?php load_view(Views::LayoutsFooter); ?>

</body>

</html>