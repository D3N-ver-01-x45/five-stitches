<?php

namespace App\Enums;

enum ReleaseMethod: string
{
    case Pickup = 'pickup';
    case Meetup = 'meetup';
    case Delivery = 'delivery';

    public function label(): string
    {
        return match ($this) {
            self::Pickup => 'Pick up',
            self::Meetup => 'Meet-up',
            self::Delivery => 'Delivery',
        };
    }
}
