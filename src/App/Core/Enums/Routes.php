<?php

namespace App\Core\Enums;

/**
 * Es una clase que recopila todos las rutas accesibles del sistema a traves del navegador.
 *
 * Ejemplo:  https://mi-dominio.com/Auth/Login
 *
 * Esta clase se debe en los controladores definidos en public/index.php
 */
class Routes
{
    /** AUTH SECTION */
    private const __moduleAuth__ = '/Auth';

    const Login = self::__moduleAuth__ . '/Login';
    const Register = self::__moduleAuth__ . '/Register';
    const Logout = self::__moduleAuth__ . '/Logout';

    /** HOME SECTION */
    const Home = '/Home';

    /** Profile SECTION */
    const Profile = '/Profile';
}