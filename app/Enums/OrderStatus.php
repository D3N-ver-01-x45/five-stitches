<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case BeingCrocheted = 'being_crocheted';
    case Shipped = 'shipped';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::BeingCrocheted => 'Being crocheted',
            self::Shipped => 'Shipped',
        };
    }

    /** Colors from the Figma "Status Pill" component. */
    public function pillClasses(): string
    {
        return match ($this) {
            self::New => 'bg-[#DCE6F5] text-[#1F3A63]',
            self::BeingCrocheted => 'bg-[#F6E5C6] text-[#5E3F0C]',
            self::Shipped => 'bg-sage-light text-[#2C4537]',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::New => 'Received — check if the design can be made and confirm the price.',
            self::BeingCrocheted => 'Down payment received, the item is being made.',
            self::Shipped => 'Released to the customer by pickup, meet-up or delivery.',
        };
    }
}
