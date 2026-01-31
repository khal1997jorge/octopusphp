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
    private const __moduleHome__ = '/Home';

    const Home = self::__moduleHome__;
    const Profile = self::__moduleHome__ . '/Profile';
}