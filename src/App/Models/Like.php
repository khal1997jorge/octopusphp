<?php

namespace App\Models;

use App\Core\Enums\LikeColumn;
use App\Models\Base\Model;

class Like extends Model
{
    public int $id;
    public int $postId;
    public int $userId;

    public static function table(): string
    {
        return 'likes';
    }

    public static function primaryKey(): string
    {
        return LikeColumn::Id;
    }

    public static function columns(): array
    {
        return [
            LikeColumn::PostId,
            LikeColumn::UserId
        ];
    }
}
