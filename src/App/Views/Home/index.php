<?php

use App\Controllers\Auth\AuthController;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;


$authenticatedUser = AuthController::currentUser();
if(!$authenticatedUser) {
    redirect(Routes::Login);
    exit;
}

?>

<h1> Este es home </h1>
