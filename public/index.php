<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\Auth\AuthController;
use App\Controllers\NotFound\NotFoundController;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if( !$route || $route === '/' || str_starts_with( $route, '/?'))
    $route = Routes::Home;

switch ($route) {
    case Routes::Register:
    case Routes::Login:
    case Routes::Logout:

        AuthController::handlerRoute($route, $method);
        break;

    case Routes::Home:
        load_view(Views::Home);
        break;

    default: //NotFound
        NotFoundController::handlerRoute($route, $method);
        return;
}
