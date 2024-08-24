<?php

namespace App\Constants;

enum Languages
{
    case en;

    case es;

    public static function get_all_languages(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
