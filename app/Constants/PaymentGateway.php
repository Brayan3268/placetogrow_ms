<?php

namespace App\Constants;

enum PaymentGateway: string
{
    case PLACETOPAY = 'placetopay';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function return_ptp(): string
    {
        return self::PLACETOPAY->value;
    }

    public static function toOptions(): array
    {
        return [
            [
                'value' => self::PLACETOPAY->value,
                'text' => 'PlacetoPay',
            ],
        ];
    }
}
