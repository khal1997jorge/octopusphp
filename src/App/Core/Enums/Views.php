<?php

namespace App\Core\Enums;

class Views
{
    /** AUTH MODULE */
    private const _Auth_ = '/Auth';
    const AuthIndex = self::_Auth_.'/index.php';
    const Login = self::_Auth_ . '/login.php';
    const Register = self::_Auth_ . '/register.php';

    /** HOME MODULE */
    const Home = '/Home/index.php';

    /** PROFILE MODULE */
    const Profile = '/Profile/index.php';

    /** NOT FOUND MODULE */
    const NotFound = '/NotFound/index.php';

    /** LAYOUT */
    const LayoutsFooter = '/Layouts/footer.php';
    const LayoutsNavbar = '/Layouts/navbar.php';

}