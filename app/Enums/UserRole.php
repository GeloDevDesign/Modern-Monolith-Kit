<?php

namespace App\Enums;

enum UserRole: string
{
    case TYPE_USER = 'user';
    case TYPE_ADMIN = 'admin';
    case TYPE_SUPER_ADMIN = 'super_admin';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_SUPER_ADMIN => 'Super Admin',
            self::TYPE_USER => 'User',
        };
    }
}
