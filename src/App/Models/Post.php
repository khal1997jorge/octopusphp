<?php

namespace App\Models;

use App\Core\Enums\PostColumn;
use App\Models\Base\Model;

class Post extends Model
{
    public int $id;
    public int $userId;
    public string $content;
    public ?string $imageUrl = null;
    public int $totalLikes = 0;

    public static function table(): string
    {
        return 'posts';
    }

    public static function primaryKey(): string
    {
        return PostColumn::Id;
    }

    public static function columns(): array
    {
        return [
            PostColumn::UserId,
            PostColumn::Content,
            PostColumn::ImageUrl,
            PostColumn::TotalLikes,
        ];
    }
}
