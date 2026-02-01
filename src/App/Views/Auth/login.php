<?php
use App\Core\Enums\Routes;
use App\Core\Enums\UserColumn;
?>

<script>
    document.addEventListener("submit", async (e) => {
        if (e.target?.id !== 'formulario-login')
            return;

        e.preventDefault();

        const errMessageHTML = document.getElementById('err-message-validation');
        errMessageHTML.innerHTML = ''

        fetch('<?= Routes::Login ?>', {
            method: 'POST',
            body: new FormData(e.target)
        }).then(async (res) => {
            if(! res.ok){
                errMessageHTML.innerHTML = (await res.json())?.error || "Ha ocurrido un error inesperado"
                return;
            }

            const data = await res.json();
            window.location.href = data.routeToRedirect;

        }).catch(() => errMessageHTML.innerHTML = 'Error de red o del servidor';)
    });
</script>
<div class="slider">
    <img src="<?= ASSETS_PATH ?>/img/1.jpeg" class="slide active">
    <img src="<?= ASSETS_PATH ?>/img/3.jpg" class="slide">
</div>

<div class="form-container">
    <form class="form-box" style="gap: 3px" id="formulario-login">
        <img src="<?= ASSETS_PATH ?>/img/lofofinall.png" class="logo" alt="logo" />
        <p>Sign in seen photos and videos from your friends.</p>

        <input
                type="text"
                name="<?= UserColumn::Username ?>"
                placeholder="Nombre de Usuario"
                required autocomplete="off"
        >
        <input
                type="password"
                name="<?= UserColumn::Password ?>"
                placeholder="Contraseña"
                required autocomplete="off"
        >

        <button type="submit">Entrar</button>

        <span id="err-message-validation"></span>

        <p class="registro">
            ¿No tienes una cuenta?
            <a href="<?= Routes::Register ?>">
                Regístrate
            </a>
        </p>
    </form>
</div>