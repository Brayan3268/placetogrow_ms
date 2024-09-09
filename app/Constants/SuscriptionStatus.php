<?php

namespace App\Constants;

enum SuscriptionStatus: string
{
    case OK = 'OK';

    case FAILED = 'FAILED';

    case APPROVED = 'APPROVED';

    case PENDING = 'PENDING';

    case REJECTED = 'REJECTED';

    case EXPIRATED = 'EXPIRATED';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
