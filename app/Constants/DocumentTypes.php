<?php

namespace App\Constants;

enum DocumentTypes: string
{
    case CC = 'CC';

    case NIT = 'NIT';

    case CE = 'CE';

    case PPT = 'PPT';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
