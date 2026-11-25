<?php

namespace App\Support;

class Money
{
    /** ₱250, ₱1,250, ₱87.50 */
    public static function peso(float|int|string $amount): string
    {
        $amount = (float) $amount;

        return '₱'.number_format($amount, fmod($amount, 1.0) === 0.0 ? 0 : 2);
    }
}
