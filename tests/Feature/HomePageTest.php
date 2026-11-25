<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Status report problems #1 and #2: the Laravel starter page showed on "/",
 * and a buyer clicking "Dashboard" just landed back on it with no way to log out.
 */
class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_go_straight_to_the_login_form(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_the_team_goes_straight_to_orders(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/')->assertRedirect(route('admin.orders.index'));
        $this->actingAs(User::factory()->seller()->create())->get('/')->assertRedirect(route('admin.orders.index'));
    }

    public function test_buyers_see_a_five_stitches_page_with_account_and_log_out(): void
    {
        $buyer = User::factory()->create(['name' => 'Juan Dela Cruz']);

        $this->actingAs($buyer)->get('/')
            ->assertOk()
            ->assertSee('Hi Juan, the shop opens soon')
            ->assertSee(route('profile.edit'), false)
            ->assertSee('action="'.route('logout').'"', false)
            ->assertDontSee('Laravel')
            ->assertDontSee('Dashboard');
    }

    public function test_buyer_dashboard_link_lands_on_that_page_not_a_loop(): void
    {
        $buyer = User::factory()->create();

        $this->actingAs($buyer)->get('/dashboard')->assertRedirect('/');
        $this->actingAs($buyer)->get('/')->assertOk();
    }

    public function test_the_laravel_starter_page_is_gone(): void
    {
        $this->assertFalse(view()->exists('welcome'));
    }
}
