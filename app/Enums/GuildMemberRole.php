<?php

namespace App\Enums;

enum GuildMemberRole: string
{
    case Admin = 'admin';
    case Member = 'member';

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
