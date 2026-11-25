<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Order::class);

        // Unknown ?status= values are ignored and show all orders.
        $status = OrderStatus::tryFrom((string) $request->query('status'));

        // toBase() so the count isn't run through the Order model's casts.
        $counts = Order::query()
            ->toBase()
            ->selectRaw('status, count(*) as aggregate')
            ->whereNull('deleted_at')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $orders = Order::query()
            ->with('items')
            ->status($status)
            ->latest()
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'counts' => $counts,
            'activeStatus' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        Gate::authorize('view', $order);

        return view('admin.orders.show', [
            'order' => $order->load('items'),
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $old = $order->status;

        $order->status = $request->enum('status', OrderStatus::class);
        $order->down_payment_paid = $request->boolean('down_payment_paid');
        $order->save();

        Log::info('Order status changed', [
            'order' => $order->order_number,
            'from' => $old->value,
            'to' => $order->status->value,
            'by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', "{$order->order_number} saved as \"{$order->status->label()}\".");
    }
}
