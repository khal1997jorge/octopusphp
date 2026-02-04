<?php

namespace App\Controllers\Home;

use App\Controllers\Auth\AuthController;
use App\Core\Database\Clauses\OrderBy;
use App\Core\Database\Clauses\Where;
use App\Core\Enums\HttpCodes;
use App\Core\Enums\LikeColumn;
use App\Core\Enums\Methods;
use App\Core\Enums\PostColumn;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;
use App\Core\Interfaces\IControllerContract;
use App\Models\Like;
use App\Models\Post;

class HomeController implements IControllerContract
{
    public static function handlerRoute(string $route, string $method): void
    {
        if($method === Methods::Get){
            self::redirectIfNotLoggedIn();

            $userId = AuthController::currentUser()->id;
            $posts = Post::findBy([
                new Where(PostColumn::UserId, '=', $userId)
            ], new OrderBy(PostColumn::Id, OrderBy::DESCENDENT));

            $postIds = array_column($posts, PostColumn::Id);

            $idsPostLikedByUser = Like::findBy([
                new Where(LikeColumn::UserId, '=', $userId),
                new Where(LikeColumn::PostId, 'IN', $postIds)
            ]);

            load_view(Views::Home, [
                'posts' => $posts,
                'idsPostLikedByUser' => array_column($idsPostLikedByUser, LikeColumn::PostId)
            ]);

            return;
        }

        self::sendErrorJsonResponse(new \BadMethodCallException('Método no permitido', HttpCodes::BadMethodCallException));
    }

    private static function redirectIfNotLoggedIn(): void
    {
        $authenticatedUser = AuthController::currentUser();
        if(!$authenticatedUser) {
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
}