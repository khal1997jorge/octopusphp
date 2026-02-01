<?php
use App\Core\Enums\Views;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/all.min.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/footer.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/navbar.css">

    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Home/index.css">
    <title>Octopus - Profile </title>
</head>

<body>

<?php load_view(Views::LayoutsNavbar) ?>

<div class="rs-container">

    <!-- CAJA DE TEXTO -->
    <div class="rs-box">
        <img src="<?= $_SESSION['profileURL'] ?>" class="rs-avatar" alt="perfil">

        <div class="rs-input-area">
            <textarea placeholder="¿Qué estás pensando?"></textarea>

            <div class="rs-actions">
                <label class="rs-photo">
                    <i class="tama fa-regular fa-image"></i>
                    <input type="file" accept="image/*" hidden>
                </label>
                <button class="rs-publish">Publicar</button>
            </div>
        </div>
    </div>

    <!-- PUBLICACIÓN -->
    <div class="rs-post">
        <div class="rs-post-header">
            <img src="https://i.pravatar.cc/100" class="rs-avatar" alt="perfil">
            <div>
                <strong>Jorge Andrés</strong>
                <p>The Beatles xs</p>

            </div>
        </div>
        <div class="publica">
            <img src="<?= ASSETS_PATH ?>/img/publi.jpg" alt="user">
        </div>



        <div class="rs-reactions">
            <button><i class="fa-solid fa-heart"></i> 75</button>
            <button><i class="fa-solid fa-heart-crack"></i> 30</button>
        </div>
    </div>
</div>

<?php load_view(Views::LayoutsFooter) ?>
</html>