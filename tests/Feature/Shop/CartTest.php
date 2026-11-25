<?php

namespace Tests\Feature\Shop;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private Product $tote;

    private Product $daisy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
        $this->tote = Product::where('slug', 'tote-bag')->first();
        $this->daisy = Product::where('slug', 'daisy-keychain')->first();
    }

    public function test_empty_cart(): void
    {
        $this->get('/cart')->assertOk()->assertSee('Your cart is empty');
    }

    public function test_guest_can_add_items_and_see_figma_example_totals(): void
    {
        // Figma B3: 1 × Tote Bag + 2 × Daisy Keychain = 3 items, ₱350
        $this->post('/cart', ['product_id' => $this->tote->id])->assertSessionHas('cart_added');
        $this->post('/cart', ['product_id' => $this->daisy->id, 'quantity' => 2]);

        $this->get('/cart')
            ->assertOk()
            ->assertSee('Tote Bag')
            ->assertSee('₱250 each · ready in 4–7 days')
            ->assertSee('Daisy Keychain')
            ->assertSee('₱100')
            ->assertSeeInOrder(['Items', '3', 'Estimated total', '₱350']);
    }

    public function test_header_shows_cart_count(): void
    {
        $this->post('/cart', ['product_id' => $this->daisy->id, 'quantity' => 2]);

        $this->get('/')->assertSee('Cart, 2 items');
    }

    public function test_adding_the_same_item_twice_adds_up(): void
    {
        $this->post('/cart', ['product_id' => $this->tote->id]);
        $this->post('/cart', ['product_id' => $this->tote->id, 'quantity' => 2]);

        $this->assertSame([$this->tote->id => 3], session('cart'));
    }

    public function test_quantity_buttons_and_remove(): void
    {
        $this->post('/cart', ['product_id' => $this->tote->id, 'quantity' => 2]);

        $this->patch(route('cart.update', $this->tote), ['quantity' => 3])->assertRedirect('/cart');
        $this->assertSame([$this->tote->id => 3], session('cart'));

        // "−" on a quantity of 1 sends 0, which removes the line.
        $this->patch(route('cart.update', $this->tote), ['quantity' => 0]);
        $this->assertSame([], session('cart'));

        $this->post('/cart', ['product_id' => $this->tote->id]);
        $this->delete(route('cart.destroy', $this->tote))->assertRedirect('/cart');
        $this->assertSame([], session('cart'));
    }

    public function test_bad_input_is_rejected(): void
    {
        $this->post('/cart', ['product_id' => 999])->assertSessionHasErrors('product_id');
        $this->post('/cart', ['product_id' => $this->tote->id, 'quantity' => 0])->assertSessionHasErrors('quantity');
        $this->post('/cart', ['product_id' => $this->tote->id, 'quantity' => 51])->assertSessionHasErrors('quantity');
        $this->post('/cart', ['product_id' => $this->tote->id, 'quantity' => 'abc'])->assertSessionHasErrors('quantity');
        $this->patch(route('cart.update', $this->tote), ['quantity' => -1])->assertSessionHasErrors('quantity');

        $this->assertEmpty(session('cart', []));
    }

    public function test_hidden_products_cannot_be_added_and_drop_out_of_the_cart(): void
    {
        $this->post('/cart', ['product_id' => $this->tote->id]);
        $this->tote->update(['is_active' => false]);

        $this->post('/cart', ['product_id' => $this->tote->id])->assertSessionHasErrors('product_id');
        $this->get('/cart')->assertSee('Your cart is empty');
    }

    public function test_prices_come_from_the_database_not_the_browser(): void
    {
        $this->post('/cart', ['product_id' => $this->tote->id, 'price' => 1]);
        $this->tote->update(['base_price' => 300]);

        $this->get('/cart')->assertSee('₱300');
    }
}
