<?php

namespace App\Constants;

enum FrecuencyCollection: string
{
    case WEEK = 'WEEK';

    case FORTNIGHTLY = 'FORTNIGHTLY';

    case MONTH = 'MONTH';

    case QUARTERLY = 'QUARTERLY';

    case BIANNUAL = 'BIANNUAL';

    case ANNUAL = 'ANNUAL';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
