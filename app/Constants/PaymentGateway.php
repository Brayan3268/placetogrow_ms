<?php

namespace App\Constants;

enum PaymentGateway: string
{
    case PLACETOPAY = 'placetopay';

    case PAYPAL = 'paypal';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function test(): string
    {
        return 'placetopay';
    }

    public static function toOptions(): array
    {
        return [
            [
                'value' => self::PLACETOPAY->value,
                'text' => 'PlacetoPay',
            ],
            [
                'value' => self::PAYPAL->value,
                'text' => 'PayPal',
            ],
        ];
    }
}
