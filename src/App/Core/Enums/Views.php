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
    private const _Home_ = '/Home';
    const Home = self::_Home_ . '/index.php';
    const Profile = self::_Home_ . '/profile.php';

    /** LAYOUT MODULE */
    private const _Layouts_ = '/Layouts';
    const LayoutsFooter = self::_Layouts_ . '/footer.php';

    /** NOT FOUND MODULE */
    private const _NotFound_ = '/NotFound';
    const NotFound = self::_NotFound_ . '/index.php';
}