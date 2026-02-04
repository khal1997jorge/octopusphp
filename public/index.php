<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\Auth\AuthController;
use App\Controllers\Home\HomeController;
use App\Controllers\Home\PostController;
use App\Controllers\NotFound\NotFoundController;
use App\Controllers\Profile\ProfileController;
use App\Core\Enums\Routes;

$route = $_SERVER['REQUEST_URI'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

if( !$route || $route === '/' || str_starts_with( $route, '/?'))
    $route = Routes::Home;

switch ($route) {
    case Routes::Register:
    case Routes::Login:
    case Routes::Logout:
        AuthController::handlerRoute($route, $method);
        break;

    case Routes::Home:
        HomeController::handlerRoute($route, $method);
        break;

    case Routes::PostCreate:
    case Routes::PostDelete:
    case Routes::PostLike:
        PostController::handlerRoute($route, $method);
        break;

    case Routes::Profile:
        ProfileController::handlerRoute($route, $method);
        break;

    default:
        NotFoundController::handlerRoute($route, $method);
        return;
}
