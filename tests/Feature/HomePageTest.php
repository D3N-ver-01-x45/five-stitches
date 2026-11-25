<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * "/" is the shop front page (Figma B1) for everyone. Replaces the Laravel starter
 * page and the buyer "Dashboard" loop (status report problems #1 and #2).
 */
class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    public function test_guests_see_the_shop_with_a_log_in_button(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Handmade crochet, made one stitch at a time.')
            ->assertSee('Tote Bag')
            ->assertSee(route('login'), false)
            ->assertDontSee('Laravel');
    }

    public function test_buyers_see_the_shop_with_my_account(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertOk()
            ->assertSee('My account')
            ->assertDontSee('>Dashboard<', false);
    }

    public function test_the_team_sees_the_shop_with_a_dashboard_button(): void
    {
        $this->actingAs(User::factory()->seller()->create())
            ->get('/')
            ->assertOk()
            ->assertSee(route('admin.orders.index'), false)
            ->assertSee('Dashboard');
    }

    public function test_buyer_dashboard_link_goes_to_the_shop(): void
    {
        $this->actingAs(User::factory()->create())->get('/dashboard')->assertRedirect('/');
    }

    public function test_the_laravel_starter_page_is_gone(): void
    {
        $this->assertFalse(view()->exists('welcome'));
    }
}
