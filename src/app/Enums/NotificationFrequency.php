<?php

namespace App\Enums;

enum NotificationFrequency: string
{
    case Instant = 'instant';
    case Daily = 'daily';
    case None = 'none';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
