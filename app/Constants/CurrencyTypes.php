<?php

namespace App\Constants;

enum CurrencyTypes: string
{
    case COP = 'COP';

    case USD = 'USD';

    case CLP = 'CLP';

    case CRC = 'CRC';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
