<?php

namespace App\Enums;

enum ProductCategory: string
{
    case Bags = 'bags';
    case Pouches = 'pouches';
    case Keychains = 'keychains';
    case Flowers = 'flowers';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
