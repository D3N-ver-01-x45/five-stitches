<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * The real Five Stitches product lines (business plan, Chapters 2–3).
 * Safe to run on the live site: it only adds or updates these four products.
 *
 *   php artisan db:seed --class=ProductSeeder
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'slug' => 'tote-bag',
                'category' => 'bags',
                'name' => 'Tote Bag',
                'description' => 'Hand-crocheted cotton tote. Also available as a shoulder bag or mini sling bag.',
                'options' => 'Yarn color or color combination, size, stitch pattern, strap length, lining, button or drawstring closure',
                'base_price' => 250,
                'ready_min_days' => 4,
                'ready_max_days' => 7,
                'image' => 'images/products/tote-bag.webp',
            ],
            [
                'slug' => 'coin-purse',
                'category' => 'pouches',
                'name' => 'Coin Purse',
                'description' => 'Hand-crocheted coin purse. Also available as a zipper pouch or a pencil or makeup pouch.',
                'options' => 'Color, size, zipper or button closure, small appliqué or initial',
                'base_price' => 120,
                'ready_min_days' => 2,
                'ready_max_days' => 3,
                'image' => 'images/products/coin-purse.webp',
            ],
            [
                'slug' => 'daisy-keychain',
                'category' => 'keychains',
                'name' => 'Daisy Keychain',
                'description' => 'Crochet daisy keychain. Also available as original mini characters, fruits, hearts, stars or initials.',
                'options' => 'Color, shape, beads or charms, name or initial',
                'base_price' => 50,
                'ready_min_days' => 1,
                'ready_max_days' => 2,
                'image' => 'images/products/daisy-keychain.webp',
            ],
            [
                'slug' => 'crochet-tulip',
                'category' => 'flowers',
                'name' => 'Crochet Tulip',
                'description' => 'Crochet tulips, as a single stem or a small bouquet. Also available as roses or sunflowers.',
                'options' => 'Petal and stem color, number of stems, wrapping style',
                'base_price' => 80,
                'ready_min_days' => 1,
                'ready_max_days' => 2,
                'image' => 'images/products/crochet-tulip.webp',
            ],
        ];

        foreach ($products as $i => $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product + ['sort_order' => $i + 1, 'is_active' => true],
            );
        }
    }
}
