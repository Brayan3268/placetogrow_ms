<?php

namespace App\Constants;

enum PaymentStatus: string
{
    case APPROVED = 'APPROVED';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case EXPIRED = 'EXPIRED';
    case APPROVED_PARCIAL = 'APPROVED_PARCIAL';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
    case UNKNOW = 'UNKNOW';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
