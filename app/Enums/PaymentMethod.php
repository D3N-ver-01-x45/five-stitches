<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case GCash = 'gcash';
    case Cash = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::GCash => 'GCash',
            self::Cash => 'Cash',
        };
    }
}
