<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Enums\UserColumn;
use App\Core\Interfaces\IModelContract;

class User extends Model implements IModelContract
{
    public int $id;
    public string $email;
    public string $password;
    public string $username;
    public string $name;
    public ?string $phone = null;
    public ?string $photo = null;

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
            UserColumn::Photo
        ];
    }
}