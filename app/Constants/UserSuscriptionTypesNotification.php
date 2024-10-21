<?php

namespace App\Constants;

enum UserSuscriptionTypesNotification: string
{
    case SUSCRIPTION = 'suscription';

    case UNSUSCRIPTION = 'unsuscription';

    case NOTICE_NEXT_PAYMENT = 'notice_next_payment';

    case NOTICE_EXPIRATION_SUSCRIPTION = 'notice_expiration_suscription';

    case NOTICE_DELETED_EXPIRATION_SUSCRIPTION = 'notice_deleted_expiration_suscription';

    case NOTICE_DELETED_NOT_PAYED_SUSCRIPTION = 'notice_deleted_not_payed_suscription';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
