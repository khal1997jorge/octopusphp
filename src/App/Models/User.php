<?php

namespace App\Models;

use App\Core\Enums\UserColumn;
use App\Models\Base\Model;

class User extends Model
{
    public int $id;
    public string $email;
    public string $password;
    public string $username;
    public string $name;
    public ?string $phone = null;
    public ?string $urlPhoto = null;

    public static function table(): string
    {
        return 'users';
    }

    public static function primaryKeys(): array
    {
        return [
            UserColumn::Id
        ];
    }

    public static function columns(): array
    {
        return [
            UserColumn::Email,
            UserColumn::Password,
            UserColumn::Username,
            UserColumn::Name,
            UserColumn::Phone,
            UserColumn::UrlPhoto
        ];
    }

    public function getUrlAvatar(): string {
        if ($this->urlPhoto) {
            return $this->urlPhoto;
        }

        return RELATIVE_UPLOADS_PATH. '/avatars/default.png';
    }
}