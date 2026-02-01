<?php

namespace App\Core\Interfaces;

interface IControllerContract {
    public static function handlerRoute(string $route, string $method): void;
}