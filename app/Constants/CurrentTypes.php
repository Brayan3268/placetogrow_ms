<?php

namespace App\Constants;

enum CurrentTypes
{
    case COP;

    case USD;

    case CLP;

    case CRC;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
