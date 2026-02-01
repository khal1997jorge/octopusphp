<?php

namespace App\Controllers\Profile;

use App\Controllers\Auth\AuthController;
use App\Core\Database\Clauses\Where;
use App\Core\Enums\HttpCodes;
use App\Core\Enums\Methods;
use App\Core\Enums\Routes;
use App\Core\Enums\Views;
use App\Core\Enums\UserColumn;
use App\Core\Exceptions\DatabaseException;
use App\Core\Exceptions\InvalidParametersException;
use App\Core\Interfaces\IControllerContract;
use App\Models\User;

class ProfileController implements IControllerContract
{
    public static function handlerRoute(string $route, string $method): void
    {
        if($method === Methods::Get){
            self::redirectIfNotLoggedIn();

            $User = AuthController::currentUser();

            load_view(Views::Profile, [
                'username' => $User->username,
                'name' => $User->name,
                'email' => $User->email,
                'phone' => $User->phone,
                'profileUrl' => $User->getUrlAvatar()
            ]);

        } else if($method === Methods::Put){
            
            self::redirectIfNotLoggedIn();

            try{
                $User = AuthController::currentUser();

                $User->email =  $_POST[UserColumn::Email];
                $User->name = $_POST[UserColumn::Name];
                $User->username = $_POST[UserColumn::Username];
                $User->phone = $_POST[UserColumn::Phone];

                $isDuplicatingEmail = User::exists([
                    new Where(UserColumn::Email, '=', $User->email),
                    new Where(UserColumn::Id, '!=', $User->id)
                ]);

                $isDuplicatingUsername = User::exists([
                    new Where(UserColumn::Username, '=', $User->username),
                    new Where(UserColumn::Id, '!=', $User->id)
                ]);

                if($isDuplicatingEmail || $isDuplicatingUsername){
                    $fieldDuplicated = $isDuplicatingEmail ? UserColumn::Email : UserColumn::Username;
                    throw new DatabaseException("Ya existe un usuario con este: $fieldDuplicated ");
                }

                self::uploadAvatarIfApply($User);

                if(! $User->update()){
                    throw new \Exception('No se pudo actualizar el usuario', HttpCodes::InternalServerError);
                }

                //Actualizamos los datos de la sessión
                $_SESSION['profileURL'] = $User->getUrlAvatar();
                $_SESSION['username'] = $User->username;

                //Respondemos al JS indicando que salió bien el proceso
                http_response_code(HttpCodes::Ok);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(['success' => true]);

                exit;

            } catch(\Exception $e){
                self::sendErrorJsonResponse($e);
            }
        }else {
            self::sendErrorJsonResponse(new \BadMethodCallException('Método no permitido', HttpCodes::BadMethodCallException));
        }
    }

    private static function redirectIfNotLoggedIn(): void
    {
        $authenticatedUser = AuthController::currentUser();
        if(!$authenticatedUser) {
            redirect(Routes::Login);
            exit;
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

    private static function uploadAvatarIfApply($user): bool {
        if (!isset($_FILES[UserColumn::UrlPhoto]) || !$_FILES[UserColumn::UrlPhoto]['name'] ) {
            return false;
        }

        if( $_FILES[UserColumn::UrlPhoto]['error'] !== UPLOAD_ERR_OK ) {
            throw new InvalidParametersException('La imagen se corrompió al enviarse');
        }

        $file = $_FILES[UserColumn::UrlPhoto];

        $imageInfo = getimagesize($file['tmp_name']);
        if (! $imageInfo) {
            throw new InvalidParametersException('No se pudo verificar la imagen del archivo');
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new InvalidParametersException('La imagen no es del formato correcto: .jpg, .jpeg, .png, .webp');
        }

        $uploadDir = ABSOLUTE_PATH_UPLOAD . '/avatars/';

        $fileName = "user_" . $user->id. ".$extension";
        $destination = $uploadDir . "$fileName";

        // Mover archivo a ruta uploads
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new InvalidParametersException('Ha ocurrido un error al guardar el archivo');
        }

        $user->urlPhoto = str_replace(ABSOLUTE_PATH_UPLOAD, RELATIVE_UPLOADS_PATH, $destination);

        return true;
    }
}