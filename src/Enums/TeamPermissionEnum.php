<?php

namespace IAleroy\Teams\Enums;

enum TeamPermissionEnum: string
{
    case MANAGE_TEAM = 'manage team';
    case MANAGE_USER = 'manage user';
    case ASSIGN_MODERATOR = 'assign moderator';

    public static function values(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->toArray();
    }
}
