<?php

namespace App\Constants;

class FieldsOptionalies
{
    public const DESCRIPTION = 'description';
    public const CURRENCY = 'currency';

    public static function getDescription(string $constant): string
    {
        $descriptions = [
            self::DESCRIPTION => 'Pay\'s description.',
            self::CURRENCY => 'Pay\'s currency',
        ];

        return $descriptions[$constant] ?? 'No description available.';
    }

    public static function getAll(): array
    {
        return [
            self::DESCRIPTION => self::getDescription(self::DESCRIPTION),
            self::CURRENCY => self::getDescription(self::CURRENCY),
        ];
    }
}