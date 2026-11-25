<?php

namespace Tests\Feature\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin/orders')->assertRedirect('/login');
    }

    public function test_staff_are_sent_from_dashboard_to_orders(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertRedirect(route('admin.orders.index'));

        $this->actingAs(User::factory()->seller()->create())
            ->get('/dashboard')
            ->assertRedirect(route('admin.orders.index'));
    }

    public function test_buyers_are_sent_back_to_the_shop_and_cannot_see_orders(): void
    {
        $buyer = User::factory()->create();
        $order = Order::factory()->create();

        $this->actingAs($buyer)->get('/dashboard')->assertRedirect('/');
        $this->actingAs($buyer)->get('/admin/orders')->assertForbidden();
        $this->actingAs($buyer)->get(route('admin.orders.show', $order))->assertForbidden();
        $this->actingAs($buyer)
            ->patch(route('admin.orders.status', $order), ['status' => 'shipped'])
            ->assertForbidden();

        $this->assertSame(OrderStatus::New, $order->fresh()->status);
    }

    public function test_new_sign_ups_are_buyers_even_if_they_send_a_role(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky',
            'email' => 'sneaky@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $this->assertSame(UserRole::Buyer, User::where('email', 'sneaky@example.com')->first()->role);
    }

    public function test_orders_page_shows_counts_and_rows(): void
    {
        Order::factory()->count(2)->create();
        Order::factory()->count(3)->status(OrderStatus::BeingCrocheted)->create();
        $shipped = Order::factory()->status(OrderStatus::Shipped)->create(['customer_name' => 'Maria Santos']);

        $this->actingAs(User::factory()->seller()->create())
            ->get('/admin/orders')
            ->assertOk()
            ->assertSee('Orders')
            ->assertSeeInOrder(['New', '2', 'Being crocheted', '3', 'Shipped', '1'])
            ->assertSee($shipped->order_number)
            ->assertSee('Maria Santos')
            ->assertDontSee('3.00'); // counts are whole numbers, not money
    }

    public function test_orders_can_be_filtered_by_status(): void
    {
        $new = Order::factory()->create(['customer_name' => 'Nina New']);
        $shipped = Order::factory()->status(OrderStatus::Shipped)->create(['customer_name' => 'Sam Shipped']);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/orders?status=shipped')
            ->assertOk()
            ->assertSee('Sam Shipped')
            ->assertDontSee('Nina New');

        // An unknown status shows everything instead of crashing.
        $this->get('/admin/orders?status=banana')
            ->assertOk()
            ->assertSee('Sam Shipped')
            ->assertSee('Nina New');
    }

    public function test_order_numbers_look_like_fs_001(): void
    {
        $order = Order::factory()->create();

        $this->assertMatchesRegularExpression('/^FS-\d{3,}$/', $order->order_number);
    }

    public function test_staff_can_view_an_order(): void
    {
        $order = Order::factory()->create(['customer_name' => 'Ana Lopez', 'notes' => 'With lining.']);

        $this->actingAs(User::factory()->seller()->create())
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Ana Lopez')
            ->assertSee('With lining.')
            ->assertSee($order->items->first()->product_name);
    }

    public function test_staff_can_update_status_and_down_payment(): void
    {
        $order = Order::factory()->create();

        $this->actingAs(User::factory()->seller()->create())
            ->patch(route('admin.orders.status', $order), ['status' => 'being_crocheted', 'down_payment_paid' => '1'])
            ->assertRedirect(route('admin.orders.show', $order))
            ->assertSessionHas('status');

        $order->refresh();
        $this->assertSame(OrderStatus::BeingCrocheted, $order->status);
        $this->assertTrue($order->down_payment_paid);
    }

    public function test_status_must_be_a_real_status(): void
    {
        $order = Order::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.orders.status', $order), ['status' => 'cancelled-lol'])
            ->assertSessionHasErrors('status');

        $this->assertSame(OrderStatus::New, $order->fresh()->status);
    }

    public function test_customer_text_is_escaped(): void
    {
        Order::factory()->create(['customer_name' => '<script>alert(1)</script>']);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/orders')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }
}
