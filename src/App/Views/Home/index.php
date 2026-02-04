<?php
use App\Core\Enums\Views;
use App\Core\Enums\Routes;
use App\Core\Enums\PostColumn;
use App\Core\Enums\Methods;

/**
 * @var \App\Models\Post[] $posts
 * @var int[] $idsPostLikedByUser
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

    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/Home/index.css">
    <title>Octopus - Home </title>
</head>

<body>

<?php load_view(Views::LayoutsNavbar) ?>

<div class="rs-container">

    <!-- CAJA DE TEXTO -->
    <div class="rs-box">
        <img src="<?= $_SESSION['profileURL'] ?>" class="rs-avatar" alt="perfil">

        <div class="rs-input-area">
            <form id="form-publicar" enctype="multipart/form-data">
                <textarea name="<?= PostColumn::Content ?>" placeholder="¿Qué estás pensando?" required></textarea>

                <div class="rs-actions">
                    <label class="rs-photo">
                        <i class="tama fa-regular fa-image"></i>
                        <input type="file" name="image" accept="image/*">
                    </label>
                    <button type="submit" class="rs-publish">Publicar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LISTADO DE PUBLICACIONES -->
    <?php if(isset($posts) && count($posts) > 0){
        /** @var \App\Models\Post $post */
        foreach($posts as $post){
            load_view(Views::HomePost, [
                'post' => $post,
                'likedByCurrentUser' => in_array($post->id, $idsPostLikedByUser),
            ]);
        }
    }else {?> 
        <p class="no-content-post"> No hay publicaciones aún.</p> <?php
    } ?>
</div>

<script>
    document.addEventListener('submit', async (e) => {
        if (e.target && e.target.id === 'form-publicar') {
            e.preventDefault();
            
            const formData = new FormData(e.target);

            try {
                const res = await fetch('<?= Routes::PostCreate ?>', {
                    method: '<?= Methods::Post ?>',
                    body: formData
                });
                
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    alert(data.error || 'Error al crear publicación');
                    return;
                }
                const data = await res.json();
                window.location.href = data.routeToRedirect || '<?= Routes::Home ?>';
            } catch (err) {
                alert('Error de red');
            }
        }
    });

    // Previsualización de imagen al seleccionar archivo en el formulario de publicar
    const formPublicar = document.getElementById('form-publicar');
    if (formPublicar) {
        const fileInput = formPublicar.querySelector('input[name="image"]');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                let preview = formPublicar.querySelector('.rs-image-preview');
                const actions = formPublicar.querySelector('.rs-actions');

                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'rs-image-preview';
                    preview.style.maxWidth = '100%';
                    preview.style.maxHeight = '300px';
                    preview.style.display = 'block';
                    preview.style.marginTop = '8px';
                    actions.parentNode.insertBefore(preview, actions);
                }

                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => preview.src = e.target.result;
                    reader.readAsDataURL(file);
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                    preview.src = '';
                }
            });
        }
    }

    const container = document.querySelector('.rs-container');

    container.addEventListener('click', async (e) => {
        // Like
        const likeBtn = e.target.closest('.btn-like');
        if (likeBtn && container.contains(likeBtn)) {
            e.preventDefault();
            const postEl = likeBtn.closest('[data-post-id]');
            if (!postEl) return;
            const postId = postEl.dataset.postId;
            likeBtn.disabled = true;
            try {
                const formData = new FormData();
                formData.append('<?= PostColumn::Id ?>', postId);
                const res = await fetch('<?= Routes::PostLike ?>', {
                    method: '<?= Methods::Post ?>',
                    body: formData
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    throw new Error(data.error || 'Error al dar like');
                }
                window.location.reload();
            } catch (err) {
                alert('Error de red: ' + (err.message || 'Error desconocido'));
            } finally {
                likeBtn.disabled = false;
            }
            return;
        }

        // Delete
        const deleteBtn = e.target.closest('.btn-delete');
        if (deleteBtn && container.contains(deleteBtn)) {
            e.preventDefault();
            const postEl = deleteBtn.closest('[data-post-id]');
            if (!postEl) return;
            const postId = postEl.dataset.postId;
            deleteBtn.disabled = true;
            try {
                const formData = new FormData();
                formData.append('<?= PostColumn::Id ?>', postId);
                formData.append('_method', '<?= Methods::Delete ?>');
                const res = await fetch('<?= Routes::PostDelete ?>', {
                    method: '<?= Methods::Post ?>',
                    body: formData
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    throw new Error(data.error || 'Error al eliminar');
                }
                window.location.reload();
            } catch (err) {
                alert('Error de red: ' + (err.message || 'Error desconocido'));
            } finally {
                deleteBtn.disabled = false;
            }
        }
    })
</script>

<?php load_view(Views::LayoutsFooter) ?>
</html>