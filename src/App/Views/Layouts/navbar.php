<?php
use App\Core\Enums\Routes;
use App\Core\Enums\Methods;
?>

<header>
    <img class="logo" id="logo-principal" src="<?= ASSETS_PATH ?>/img/lofofinall.png" alt="logo">

        <div class="middle-and-right">
        <input type="text" class="search" placeholder="Buscar...">

        <div class="right-group">
            <div class="icons">
                <a href="<?= Routes::Home ?>"><i class="fa-solid fa-house"></i></a>
                <a href="#"><i class="fa-solid fa-envelope"></i></a>
                <a href="#" id="logout-link"><i class="fa-solid fa-right-to-bracket"></i></a>
                <a href="#"><i class="fa-solid fa-bell"></i></a>
            </div>
            <div class="perfil" id="profile-image-navbar">
                <img src="<?= $_SESSION['profileURL'] ?>" class="rs-avatar" alt="perfil">
                <span><?= $_SESSION['username'] ?></span>
            </div>
        </div>
    </div>

    <div class="hamburger">☰</div>

    <script>
        document.getElementById('logo-principal').addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = '<?= Routes::Home ?>'
        })

        document.getElementById('profile-image-navbar').addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = '<?= Routes::Profile ?>'
        })

        document.getElementById('logout-link').addEventListener('click', (e) => {
            e.preventDefault();
            fetch('<?= Routes::Logout ?>', {method: '<?= Methods::Post ?>',})
                .then(async (res) => {
                    if(! res.ok){
                        alert('Ha ocurrido un error inesperado')
                        return;
                    }

                    window.location.href = '<?= Routes::Login ?>';
                })
                .catch(async () => alert('Ha ocurrido un error inesperado'))
        });

        const hamburger = document.querySelector('.hamburger');
        const menu = document.querySelector('.middle-and-right');

        hamburger.addEventListener('click', () => {
            menu.classList.toggle('open');
        });
    </script>
</header>