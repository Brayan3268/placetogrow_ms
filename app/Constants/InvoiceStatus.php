<?php

namespace App\Constants;

enum InvoiceStatus: string
{
    case NOT_PAYED = 'not_payed';

    case PAYED = 'payed';

    case EXPIRATE = 'expirate';

    case PENDING = 'pending';

    case UNKNOW = 'unknow';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
