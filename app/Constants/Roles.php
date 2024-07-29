<?php

namespace App\Constants;

class Roles
{
    public const SUPER_ADMIN = 'super_admin';

    public const ADMIN = 'admin';

    public const GUEST = 'guest';

    public static function get_all_roles(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
