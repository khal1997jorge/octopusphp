<?php

namespace App\Controllers\Home;

use App\Controllers\Auth\AuthController;
use App\Core\Enums\HttpCodes;
use App\Core\Enums\Methods;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;
use App\Core\Interfaces\IControllerContract;

class HomeController implements IControllerContract
{
    public static function handlerRoute(string $route, string $method): void
    {
        if($method === Methods::Get){
            self::redirectIfNotLoggedIn();
            load_view(Views::Home);

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