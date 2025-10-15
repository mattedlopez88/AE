<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        return array_column(UserStatus::cases(), 'value');
    }
}
