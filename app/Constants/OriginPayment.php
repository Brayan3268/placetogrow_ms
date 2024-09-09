<?php

namespace App\Constants;

enum OriginPayment: string
{
    case INVOICE = 'invoice';

    case STANDART = 'standart';

    case SUSCRIPTION = 'suscription';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
