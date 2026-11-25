<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\ReleaseMethod;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /** Base prices from the Five Stitches business plan (PRODUCTS.md). */
    public const PRODUCTS = [
        ['Tote Bag', 250],
        ['Shoulder Bag', 250],
        ['Mini Sling Bag', 250],
        ['Coin Purse', 120],
        ['Zipper Pouch', 120],
        ['Daisy Keychain', 50],
        ['Heart Keychain', 50],
        ['Crochet Tulip', 80],
        ['Sunflower Bouquet', 80],
    ];

    public const COLORS = ['Sage', 'Cream', 'Coral', 'Oat', 'Forest', 'Blush', 'Lavender', 'Charcoal'];

    public function definition(): array
    {
        $release = fake()->randomElement(ReleaseMethod::cases());

        return [
            'customer_name' => fake()->firstName().' '.fake()->lastName(),
            'customer_contact' => '09'.fake()->numerify('## ### ####'),
            'release_method' => $release,
            'delivery_address' => $release === ReleaseMethod::Delivery ? 'Brgy. '.fake()->numberBetween(1, 70).', Legazpi City' : null,
            'date_needed' => fake()->optional(0.7)->dateTimeBetween('now', '+3 weeks'),
            'payment_method' => fake()->randomElement(PaymentMethod::cases()),
            'down_payment_paid' => false,
            'status' => OrderStatus::New,
            'notes' => fake()->optional(0.4)->randomElement([
                'Please add the initial K.',
                'Gift — wrap it please.',
                'Bigger size if possible.',
                'With lining.',
                'Meet at the UST-Legazpi gate.',
            ]),
            'total' => 0,
        ];
    }

    public function status(OrderStatus $status): static
    {
        return $this->state(fn () => [
            'status' => $status,
            'down_payment_paid' => $status !== OrderStatus::New,
        ]);
    }

    /** Adds 1–3 items and sets the total from them. */
    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            if ($order->items()->exists()) {
                return;
            }

            foreach (fake()->randomElements(self::PRODUCTS, fake()->numberBetween(1, 3)) as [$name, $price]) {
                $order->items()->create([
                    'product_name' => $name,
                    'color' => fake()->randomElement(self::COLORS),
                    'quantity' => fake()->numberBetween(1, 3),
                    'unit_price' => $price,
                ]);
            }

            $order->load('items');
            $order->updateQuietly(['total' => $order->items->sum(fn (OrderItem $i) => $i->lineTotal())]);
        });
    }
}
