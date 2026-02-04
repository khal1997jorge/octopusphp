<?php

use App\Core\Enums\Methods;
use App\Core\Enums\Routes;
use App\Core\Enums\PostColumn;
use App\Models\Post;

/**
 * @var Post $post
 * @var bool $likedByCurrentUser
 */
?>
<div class="rs-post" data-post-id="<?= $post->id ?>">
    <div class="rs-post-header">
        <img src="<?= $_SESSION['profileURL'] ?>" class="rs-avatar" alt="perfil">
        <div>
            <strong><?= $_SESSION['username'] ?></strong>
        </div>

        <?php if($_SESSION['userId'] == $post->userId): ?>
            <form class="form-eliminar-post" style="margin-left:auto" onsubmit="return false;">
                <input type="hidden" name="<?= PostColumn::Id ?>" value="<?= $post->id ?>">
                <input type="hidden" name="_method" value="<?= Methods::Delete ?>">
                <button type="button" class="btn-delete">Eliminar</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="publica">
        <p><?= nl2br(htmlspecialchars($post->content)) ?></p>
        <?php if(!empty($post->imageUrl)): ?>
            <img src="<?= $post->imageUrl ?>" alt="user">
        <?php endif; ?>
    </div>
    <div class="rs-reactions">
        <button class="btn-like <?php if ($likedByCurrentUser) echo 'liked'; ?>">
            <i class="fa-solid fa-heart"></i> 
            <span class="likes-count">
                <?= $post->totalLikes ?>
            </span>
        </button>
    </div>
</div>