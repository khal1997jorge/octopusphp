<?php

namespace App\Controllers\NotFound;

use App\Controllers\Auth\AuthController;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;
use App\Core\Interfaces\IControllerContract;

class NotFoundController implements IControllerContract
{

    public static function handlerRoute(string $route, string $method): void
    {
        $userIsAuthenticated = AuthController::currentUser();

        if($userIsAuthenticated) {
            $routeToRedirect = Routes::Home;
            $textButtonRedirect = 'Ir a Home';
        }else{
            $routeToRedirect = Routes::Login;
            $textButtonRedirect = 'Ir a Login';
        }

        load_view(Views::NotFound, [
            'originalRoute' => $route,
            'routeToRedirect' => $routeToRedirect,
            'buttonText' => $textButtonRedirect,
        ]);
    }
}