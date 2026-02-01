<?php

use App\Core\Enums\Views;
use App\Core\Enums\UserColumn;
use App\Core\Enums\Routes;
use App\Core\Enums\Methods;

/**
 * @var string $username
 * @var string $name
 * @var string $email
 * @var string $phone
 * @var string $profileUrl
 */

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

    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Profile/index.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Home/index.css">

    <script src="<?= ASSETS_PATH ?>/js/FormHelper.js"></script>

    <title>Octopus - Profile </title>
</head>

<body>

    <?php load_view(Views::LayoutsNavbar) ?>

    <div class="contenedor-principal">
        <div class="contenedor">

            <div class="box-form">
                <h2 class="profile-title">Editar perfil</h2>
                <p>Actualiza tu información personal. Los cambios se guardarán inmediatamente.</p>

                <form id="formulario-profile" enctype="multipart/form-data">

                    <!-- Avatar -->
                    <div class="avatar-section">
                        <img src="<?= $profileUrl ?>" alt="avatar" class="avatar-profile">
                        <label for="foto" class="btn-avatar">
                            Cambiar foto
                        </label>
                        <input type="file" id="foto" name="<?= UserColumn::UrlPhoto ?>" accept="image/*" hidden>
                    </div>

                    <!-- Campos -->
                    <div class="form-group">
                        <input type="text" name="<?= UserColumn::Name ?>" placeholder="Nombre completo" required value="<?= $name ?>">
                        <span class="text-error" data-error-for="<?= UserColumn::Name ?>"></span>
                    </div>

                    <div class="form-group">
                        <input type="email" name="<?= UserColumn::Email ?>" placeholder="Correo electrónico" required value="<?= $email ?>">
                        <span class="text-error" data-error-for="<?= UserColumn::Email ?>"></span>
                    </div>

                    <div class="form-group">
                        <input type="text" name="<?= UserColumn::Username ?>" placeholder="Nombre de usuario" required value="<?= $username ?>">
                        <span class="text-error" data-error-for="<?= UserColumn::Username ?>"></span>
                    </div>

                    <div class="form-group">
                        <input type="text" name="<?= UserColumn::Phone ?>" placeholder="Teléfono" value="<?= $phone ?>">
                        <span class="text-error" data-error-for="<?= UserColumn::Phone ?>"></span>
                    </div>

                    <input type="hidden" name="_method" value="PUT">

                    <span id="err-message-validation"></span>

                    <button id="btnGuardar">Guardar cambios</button>
                </form>

                <div class="condiciones">
                    Los datos serán visibles en tu perfil público.
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            FormHelper.Initialize('formulario-profile', 'btnGuardar');
        });

        document.addEventListener("submit", async (e) => {
            if (e.target?.id !== 'formulario-profile')
                return;

            e.preventDefault();

            const errMessageHTML = document.getElementById('err-message-validation');
            errMessageHTML.innerHTML = '';

            await fetch('<?= Routes::Profile ?>', {
                method: '<?= Methods::Post ?>', //se envia un post con _method put
                body: new FormData(e.target)
            }).then(async (res) => {
                if (!res.ok) {
                    const body = await res.json().catch(() => ({
                        error: 'Error inesperado'
                    }));
                    errMessageHTML.innerHTML = body?.error || "Ha ocurrido un error inesperado";
                    return;
                }

                window.location.reload();

            }).catch(async (res) => {
                errMessageHTML.innerHTML = await (res.json()).error
            })
        });

        (function() {
            const fileInput = document.getElementById('foto');
            if (!fileInput) return;

            const avatarImg = document.querySelector('.avatar-section .avatar-profile');
            const label = document.querySelector('label[for="foto"]');

            const errMessageHTML = document.getElementById('err-message-validation');
            
            const originalLabel = label ? label.textContent.trim() : 'Cambiar foto';
            const originalSrc = avatarImg ? avatarImg.src : null;

            fileInput.addEventListener('change', (e) => {
                errMessageHTML.innerHTML = '';
                const file = e.target.files?.[0];
                if (!file) {
                    if (label) label.textContent = originalLabel;
                    if (avatarImg && originalSrc) avatarImg.src = originalSrc;
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    errMessageHTML.innerHTML = 'El archivo seleccionado no es una imagen.';
                    fileInput.value = '';
                    return;
                }

                const maxSize = 2 * 1024 * 1024; // 2 MB
                if (file.size > maxSize) {
                    errMessageHTML.innerHTML = 'La imagen supera el tamaño máximo de 2 MB.';
                    fileInput.value = '';
                    return;
                }

                const shortName = file.name.length > 30 ? file.name.slice(0, 27) + '...' : file.name;
                if (label) label.textContent = `Cambiar foto — ${shortName}`;

                const reader = new FileReader();
                reader.onload = function(ev) {
                    if (avatarImg) avatarImg.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>


    <?php load_view(Views::LayoutsFooter) ?>

</html>