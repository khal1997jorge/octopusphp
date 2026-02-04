<?php

session_start();

// Definimos Path absolutos para ubicar carpetas importantes
const PUBLIC_PATH = __DIR__;
const ASSETS_PATH = '/assets';
const RELATIVE_UPLOADS_PATH = '/uploads';
const ABSOLUTE_PATH_UPLOAD = '/var/www/html/uploads';
const APP_PATH = PUBLIC_PATH . '/src/App';
const VIEW_PATH = APP_PATH . '/Views';
const CONFIG_PATH = PUBLIC_PATH . '/config';

// simple autoloader para trabajar con namespaces
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace(
        ['App\\', '\\'],
        ['', '/'],
        $class
    ) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Definimos una función global para cargar vistas
if( !function_exists('load_view')) {
    function load_view($filePhp, $data = []) {
        extract($data, EXTR_OVERWRITE);
        require VIEW_PATH . "$filePhp"; // permite cargar multiples veces una vista
    }
}

// Definimos una función global para redireccionamiento por header
if( !function_exists('redirect')) {
    function redirect(string $route) {
        header("Location: $route");
        exit;
    }
}

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);