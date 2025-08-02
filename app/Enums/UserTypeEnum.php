<?php

namespace App\Enums;

enum UserTypeEnum: int
{
    case Admin = 0;
    case User = 1;
    case Volunteer = 2;
    case LawEnforcement = 3;

    public static function getItems(): array
    {
        return [
            self::Admin,
            self::User,
            self::Volunteer,
            self::LawEnforcement,
        ];
    }

    public static function getValues($exclude_admin = false): array
    {
        if ($exclude_admin) {
            return [
                self::User->value,
                self::Volunteer->value,
                self::LawEnforcement->value,
            ];
        }

        return [
            self::Admin->value,
            self::User->value,
            self::Volunteer->value,
            self::LawEnforcement->value,
        ];
    }
}
