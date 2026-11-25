<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * Shopping cart stored in the visitor's session (works without an account).
 * Session holds only [product_id => quantity]; prices always come from the
 * database, so nobody can change a price from the browser.
 */
class Cart
{
    public const MAX_QUANTITY = 50;

    private const KEY = 'cart';

    public function __construct(private Session $session) {}

    /** @return array<int, int> product_id => quantity */
    private function raw(): array
    {
        return (array) $this->session->get(self::KEY, []);
    }

    private function save(array $lines): void
    {
        $this->session->put(self::KEY, array_filter($lines, fn ($qty) => $qty > 0));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $lines = $this->raw();
        $lines[$product->id] = min(self::MAX_QUANTITY, ($lines[$product->id] ?? 0) + max(1, $quantity));
        $this->save($lines);
    }

    public function set(Product $product, int $quantity): void
    {
        $lines = $this->raw();
        $lines[$product->id] = min(self::MAX_QUANTITY, max(0, $quantity));
        $this->save($lines);
    }

    public function remove(Product $product): void
    {
        $lines = $this->raw();
        unset($lines[$product->id]);
        $this->save($lines);
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }

    /**
     * Cart lines with fresh product data. Products that were hidden or
     * deleted since they were added are dropped.
     *
     * @return Collection<int, object{product: Product, quantity: int, total: float}>
     */
    public function lines(): Collection
    {
        $raw = $this->raw();

        if ($raw === []) {
            return collect();
        }

        $products = Product::query()
            ->where('is_active', true)
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(fn ($qty, $id) => (object) [
                'product' => $products[$id],
                'quantity' => (int) $qty,
                'total' => (float) $products[$id]->base_price * $qty,
            ])
            ->values();
    }

    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('total');
    }
}
