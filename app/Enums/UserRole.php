<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';   // General Manager / site owner
    case Seller = 'seller'; // Five Stitches team members who handle orders and products
    case Buyer = 'buyer';   // Customers

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Seller => 'Seller',
            self::Buyer => 'Buyer',
        };
    }
}
