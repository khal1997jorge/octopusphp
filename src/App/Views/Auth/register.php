<?php
use App\Core\Enums\Routes;
use App\Core\Enums\UserColumn;
?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        FormHelper.Initialize('formulario-register', 'btnGuardar');
    });

    document.addEventListener("submit", async (e) => {
        if (e.target?.id !== 'formulario-register')
            return;

        e.preventDefault();

        const errMessageHTML = document.getElementById('err-message-validation');
        errMessageHTML.innerHTML = ''

        await fetch('<?= Routes::Register ?>', {
            method: 'POST',
            body: new FormData(e.target)
        }).then(async (res) => {
            if(! res.ok){
                errMessageHTML.innerHTML = (await res.json())?.error || "Ha ocurrido un error inesperado"
                return;
            }

            const data = await res.json();
            window.location.href = data.routeToRedirect;

        }).catch(async (res) => {
            errMessageHTML.innerHTML = await (res.json()).error
        })
    });
</script>

<div class="contenedor-principal">
    <div class="contenedor">
        <div class="box-img">
            <img src="<?= ASSETS_PATH ?>/img/portada.png">
        </div>
        <div class="box-form">
            <div class="logo1">
                <img src="<?= ASSETS_PATH ?>/img/lofofinall.png" alt="log">
            </div>

            <p>Crea una cuenta es fácil y gratis. Puedes compartir fotos y videos con tus amigos.</p>

            <form id="formulario-register">
                <input type="text" name="<?= UserColumn::Name ?>" placeholder="Nombre Completo" required>
                <span class="text-error" data-error-for="<?= UserColumn::Name ?>"></span>

                <input type="email" name="<?= UserColumn::Email ?>" placeholder="Correo Electrónico" required>
                <span class="text-error" data-error-for="<?= UserColumn::Email ?>"></span>

                <input type="text" name="<?= UserColumn::Username ?>" placeholder="Nombre de Usuario" required>
                <span class="text-error" data-error-for="<?= UserColumn::Username ?>"></span>

                <input type="password" name="<?= UserColumn::Password ?>" placeholder="Contraseña" required>
                <span class="text-error" data-error-for="<?= UserColumn::Password ?>"></span>

                <span id="err-message-validation"></span>
                <button id="btnGuardar" disabled>Crear</button>
            </form>

            <div class="condiciones">
                Al registrarte, aceptas nuestras <a href="#">condiciones</a> y <a href="#">política de privacidad</a>.
            </div>
        </div>
    </div>
</div>