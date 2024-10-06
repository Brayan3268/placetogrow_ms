<?php

namespace App\Constants;

enum SurchargeInvoiceTypesNotification: string
{
    case CREATED = 'created';

    case SURCHARGE = 'surcharge';

    case EXPIRATED = 'expirated';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
