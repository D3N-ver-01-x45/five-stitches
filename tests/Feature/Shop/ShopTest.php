<?php

namespace Tests\Feature\Shop;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    public function test_home_lists_the_four_products_with_prices_and_ready_times(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['Tote Bag', 'Coin Purse', 'Daisy Keychain', 'Crochet Tulip'])
            ->assertSee('from ₱250')
            ->assertSee('Ready in 4–7 days')
            ->assertSee('How ordering works')
            ->assertSee('Questions');
    }

    public function test_hidden_products_are_not_shown_or_reachable(): void
    {
        $hidden = Product::factory()->hidden()->create(['name' => 'Secret Scarf', 'slug' => 'secret-scarf']);

        $this->get('/')->assertDontSee('Secret Scarf');
        $this->get(route('products.show', $hidden))->assertNotFound();
    }

    public function test_category_filter(): void
    {
        // The hero photos always show every product, so check the product cards.
        $this->get('/?category=pouches')
            ->assertOk()
            ->assertSee('Add Coin Purse to cart')
            ->assertDontSee('Add Daisy Keychain to cart');

        // Unknown category shows everything.
        $this->get('/?category=hats')->assertOk()->assertSee('Add Daisy Keychain to cart')->assertSee('Add Coin Purse to cart');
    }

    public function test_search(): void
    {
        $this->get('/?q=tulip')
            ->assertOk()
            ->assertSee('Results for')
            ->assertSee('Add Crochet Tulip to cart')
            ->assertDontSee('Add Coin Purse to cart');

        $this->get('/?q=zzzz')->assertOk()->assertSee('No items match');

        // Wildcards are treated as normal text.
        $this->get('/?q=%25')->assertOk()->assertSee('No items match');
    }

    public function test_product_page(): void
    {
        $this->get('/products/tote-bag')
            ->assertOk()
            ->assertSee('Tote Bag')
            ->assertSee('from ₱250')
            ->assertSee('You can choose')
            ->assertSee('strap length')
            ->assertSee('Ready in 4–7 days');

        $this->get('/products/does-not-exist')->assertNotFound();
    }

    public function test_search_text_is_escaped(): void
    {
        $this->get('/?q='.urlencode('<script>alert(1)</script>'))
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
