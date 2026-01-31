<?php

namespace App\Controllers\Auth;

use App\Core\Enums\HttpCodes;
use App\Core\Enums\Methods;
use App\Core\Enums\Routes;
use App\Core\Enums\UserColumn;
use App\Core\Enums\Views;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\InvalidParametersException;
use App\Core\Exceptions\NotFoundException;
use App\Core\Exceptions\UnauthorizedException;
use App\Core\Interfaces\IControllerContract;
use App\Models\User;

class AuthController implements IControllerContract
{
    /**
     * Maneja las solicitudes relacionadas con la autenticación
     */
    public static function handlerRoute(string $route, string $method): void{
        switch($method) {
            case Methods::Get:
                self::redirectIfAuthenticated();

                $viewName = Views::Login;

                if($route === Routes::Register){
                    $viewName = Views::Register;
                }

                load_view(Views::AuthIndex, [
                    'page' => $viewName,
                ]);
                break;

            case Methods::Post:
                if($route === Routes::Login) {
                    try {

                        $username = $_POST[UserColumn::Username];
                        $password = $_POST[UserColumn::Password];

                        self::authenticate($username, $password);
                        self::sendSuccessJsonResponse(Routes::Home, HttpCodes::Ok);

                        break;

                    } catch (\Exception $e) {
                        self::sendErrorJsonResponse($e);
                        break;
                    }

                }

                if ($route === Routes::Register) {
                    $email = $_POST[UserColumn::Email];
                    $password = $_POST[UserColumn::Password];
                    $name = $_POST[UserColumn::Name];
                    $username = $_POST[UserColumn::Username];

                    try {
                        self::registerUser($email, $password, $name, $username);
                        self::sendSuccessJsonResponse(Routes::Login, HttpCodes::Created);
                        break;

                    } catch (\Exception $e) {
                        self::sendErrorJsonResponse($e);
                        break;
                    }
                }

                if ($route === Routes::Logout) {
                    self::logout();
                    redirect(Routes::Login);
                    return;
                }

                break;

            default:
                self::sendErrorJsonResponse(new \BadMethodCallException('Método no permitido', HttpCodes::BadMethodCallException));
                break;
        }

    }

    /**
     * @throws DatabaseException
     */
    private static function registerUser(string $email, string $password, string $name, string $username): User
    {
        $existingUser = User::findOne([
            UserColumn::Email => $email
        ]);

        if ($existingUser) {
            throw new DatabaseException('Ya existe un usuario con este email');
        }

        $existingUser = User::findOne([
            UserColumn::Username => $username
        ]);

        if ($existingUser) {
            throw new DatabaseException('Ya existe un usuario con este nombre de usuario');
        }

        $user = new User();
        $user->email = trim($email);
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->username = trim($username);
        $user->name = trim($name);

        if (! $user->insert()) {
            throw new DatabaseException('Error al registrar usuario');
        }

        return $user;
    }

    private static function authenticate(string $username, string $password): ?User
    {
        if( !$username || !$password){
            throw new InvalidParametersException('Los parámetros enviados para el login son inválidos');
        }

        $user = User::findOne([
            UserColumn::Username => $username
        ]);

        if( !$user){
            throw new NotFoundException('No se encontró el usuario');
        }
        
        if (!password_verify($password, $user->password)) {
            throw new UnauthorizedException('Contraseña incorrecta');
        }

        // Grabar id en la sesión
        $_SESSION['userId'] = $user->id;

        return $user;
    }


    public static function currentUser(): ?User
    {
        if (!isset($_SESSION['userId'])) {
            return null;
        }

        $userId = $_SESSION['userId'];
        
        return User::findOne([
            UserColumn::Id => $userId
        ]);
    }

    public static function logout(): void
    {
        session_destroy();
        redirect(Routes::Login);
    }

    private static function redirectIfAuthenticated(): void
    {
        $user = self::currentUser();
        if ($user) {
            redirect(Routes::Home);
        }
    }

    private static function sendErrorJsonResponse(\Exception $e): void
    {
        http_response_code($e->getCode());
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
    private static function sendSuccessJsonResponse(string $route, int $code): void
    {
        http_response_code($code);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            'success' => true,
            'routeToRedirect' => $route
        ]);
        exit;
    }
}
