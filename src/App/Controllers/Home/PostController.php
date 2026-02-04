<?php

namespace App\Controllers\Home;

use App\Core\Database\Clauses\Where;
use App\Core\Enums\HttpCodes;
use App\Core\Enums\Methods;
use App\Core\Enums\Routes;
use App\Core\Interfaces\IControllerContract;
use App\Controllers\Auth\AuthController;
use App\Core\Enums\LikeColumn;
use App\Core\Enums\PostColumn;
use App\Models\Post;
use App\Models\Like;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\InvalidParametersException;
use App\Core\Exceptions\UnauthorizedException;

class PostController implements IControllerContract
{
    public static function handlerRoute(string $route, string $method): void
    {
        self::redirectIfNotLoggedIn();

        $user = AuthController::currentUser();

        switch ($method) {
            case Methods::Post:
                if ($route === Routes::PostCreate) {
                    self::createPost($user->id);
                    return;
                } else if ($route === Routes::PostLike) {
                    $idPost = (int) $_POST[PostColumn::Id];
                    self::toggleLike($user->id, $idPost);
                    return;
                }

            case Methods::Delete:
                $idPost = (int) $_POST[PostColumn::Id];

                self::deletePost($user->id, $idPost);
                return;

            default:
                self::sendErrorJsonResponse(new \BadMethodCallException('Método no permitido', HttpCodes::BadMethodCallException));
        }
    }

    private static function createPost(int $userId): void
    {
        try {
            $content = trim($_POST['content'] ?? '');

            if (!$content && (!isset($_FILES['image']) || !$_FILES['image']['tmp_name'])) {
                throw new InvalidParametersException('Contenido vacío');
            }

            $post = new Post();
            $post->userId = $userId;
            $post->content = $content;
            $post->totalLikes = 0;
            self::uploadImagePostIfApply($post);

            if ( !$post->insert() ) {
                throw new DatabaseException('Error al crear post');
            }

            self::sendSuccessJsonResponse(Routes::Home, HttpCodes::Created);
        } catch (\Exception $e) {
            self::sendErrorJsonResponse($e);
        }
    }

    private static function uploadImagePostIfApply(Post $post): void
    {
        if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
            $uploadDir = ABSOLUTE_PATH_UPLOAD . '/posts';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $name = time() . '_' . basename($_FILES['image']['name']);
            $dest = $uploadDir . '/' . $name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                throw new \Exception('Error al subir imagen');
            }

            $post->imageUrl = RELATIVE_UPLOADS_PATH . '/posts/' . $name;
        }
    }

    private static function deletePost(int $userId, int $postId): void
    {
        try {

            $post = Post::findOne([
                new Where(PostColumn::Id, '=', $postId)
            ]);

            if (! $post) {
                //Si no existe, consideramos que ya está eliminado
                self::sendSuccessJsonResponse(Routes::Home, HttpCodes::Ok);
                return;
            }
            
            if ($post->userId !== $userId) {
                throw new UnauthorizedException('No está autorizado a borrar el Post de otro usuario');
            }

            if (! $post->delete()) {
                throw new DatabaseException('No se pudo eliminar la publicación');
            }

            self::sendSuccessJsonResponse(Routes::Home, HttpCodes::Ok);
        } catch (\Exception $e) {
            self::sendErrorJsonResponse($e);
        }
    }

    private static function toggleLike(int $userId, int $postId): void
    {
        try {
            $postId = (int) $_POST[PostColumn::Id];
            
            if ( !$postId ) {
                throw new InvalidParametersException('postId inválido');
            }

            $PostDB = Post::findOne([
                new Where(PostColumn::Id, '=', $postId)
            ]);

            if(!$PostDB){
                throw new InvalidParametersException('Post no existe');
            }

            $LikeDB = Like::findOne([
                new Where(LikeColumn::PostId, '=', $postId),
                new Where(LikeColumn::UserId, '=', $userId)
            ]);

            if( $LikeDB ){
                $LikeDB->delete();
            }else{
                $newLike = new Like();
                $newLike->userId = $userId;
                $newLike->postId = $postId;
                $newLike->insert();
            }


            $PostDB->totalLikes = count(Like::findBy([ 
                new Where(LikeColumn::PostId, '=', $postId)
            ]));
            $PostDB->update();           

            self::sendSuccessJsonResponse('', HttpCodes::Ok);

        } catch (\Exception $e) {
            self::sendErrorJsonResponse($e);
        }
    }

    private static function redirectIfNotLoggedIn(): void
    {
        $authenticatedUser = AuthController::currentUser();
        if (!$authenticatedUser) {
            redirect(Routes::Login);
            exit;
        }
    }

    private static function sendErrorJsonResponse(\Exception $e): void
    {
        http_response_code($e->getCode());
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }

    private static function sendSuccessJsonResponse(string $route, int $code): void
    {
        http_response_code($code);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            'success' => true,
            'routeToRedirect' => $route
        ]);
        exit;
    }
}
