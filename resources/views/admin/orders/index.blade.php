{{-- Figma: Screens › SELLER pages › "S4 · Seller orders" (node 5:152) --}}
<x-app-layout>
    <x-slot:title>Orders</x-slot:title>

    <!-- Title row -->
    <div class="flex items-center justify-between gap-4">
        <h1 class="font-heading text-[40px] font-bold leading-none text-ink">Orders</h1>
        <span class="text-sm text-muted">{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}{{ $activeStatus ? ' · '.$activeStatus->label() : '' }}</span>
    </div>

    <!-- Stats -->
    <div class="mt-6 grid grid-cols-3 gap-2 sm:gap-4">
        @foreach (App\Enums\OrderStatus::cases() as $status)
            <a href="{{ route('admin.orders.index', ['status' => $status->value]) }}"
               class="flex flex-col gap-1 rounded-2xl border border-line bg-white px-3 py-3 sm:px-5 sm:py-[18px] transition hover:border-sage focus:outline-none focus-visible:ring-2 focus-visible:ring-forest">
                <span class="text-xs leading-tight text-muted sm:text-sm">{{ $status->label() }}</span>
                <span class="font-heading text-[28px] sm:text-[36px] font-bold leading-tight text-ink">{{ $counts[$status->value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <!-- Filters -->
    @php
        $filters = ['' => 'All'] + collect(App\Enums\OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all();
    @endphp
    <nav class="mt-6 flex flex-wrap gap-2" aria-label="Filter orders by status">
        @foreach ($filters as $value => $label)
            @php $isActive = ($activeStatus?->value ?? '') === $value; @endphp
            <a href="{{ route('admin.orders.index', $value ? ['status' => $value] : []) }}"
               @if ($isActive) aria-current="true" @endif
               class="rounded-full px-4 py-2.5 text-sm font-semibold leading-none transition focus:outline-none focus-visible:ring-2 focus-visible:ring-forest
                      {{ $isActive ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:border-sage' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <!-- Orders table -->
    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white">
        @if ($orders->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="font-heading text-2xl font-bold text-ink">No orders here yet</p>
                <p class="mt-2 text-sm text-muted">
                    {{ $activeStatus ? 'No orders are "'.$activeStatus->label().'" right now.' : 'New orders from the shop will show up here.' }}
                </p>
            </div>
        @else
            <!-- Phones: one card per order -->
            <ul class="divide-y divide-line md:hidden">
                @foreach ($orders as $order)
                    <li>
                        <a href="{{ route('admin.orders.show', $order) }}" class="block px-4 py-4 hover:bg-cream/60 focus:outline-none focus-visible:bg-cream">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold text-ink">{{ $order->order_number }}</span>
                                <x-status-pill :status="$order->status" />
                            </div>
                            <p class="mt-1 truncate text-[15px] text-ink">{{ $order->customer_name }}</p>
                            <p class="mt-1 text-sm text-muted">
                                {{ $order->totalQuantity() }} {{ Str::plural('item', $order->totalQuantity()) }} · {{ $order->colorSummary() }} · {{ $order->payment_method->label() }} · {{ $order->release_method->label() }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Tablet & desktop: table (Figma "Orders table") -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[860px] text-left">
                    <thead class="border-b border-line bg-[#F7F4EE]">
                        <tr class="text-[13px] font-semibold text-muted">
                            <th scope="col" class="w-[110px] py-3 pl-4">Order</th>
                            <th scope="col" class="w-[170px] py-3 pl-4">Customer</th>
                            <th scope="col" class="w-[120px] py-3 pl-4">Color</th>
                            <th scope="col" class="w-[70px] py-3 pl-4">Qty</th>
                            <th scope="col" class="w-[120px] py-3 pl-4">Payment</th>
                            <th scope="col" class="w-[120px] py-3 pl-4">Delivery</th>
                            <th scope="col" class="py-3 pl-4">Status</th>
                            <th scope="col" class="w-[80px] py-3 pl-4"><span class="sr-only">Action</span></th>
                        </tr>
                    </thead>
                    <tbody class="text-[15px] text-ink">
                        @foreach ($orders as $order)
                            <tr class="border-b border-line last:border-b-0 hover:bg-cream/60">
                                <td class="py-4 pl-4 font-semibold">{{ $order->order_number }}</td>
                                <td class="max-w-[170px] truncate py-4 pl-4">{{ $order->customer_name }}</td>
                                <td class="max-w-[120px] truncate py-4 pl-4" title="{{ $order->colorSummary() }}">{{ $order->colorSummary() }}</td>
                                <td class="py-4 pl-4">{{ $order->totalQuantity() }}</td>
                                <td class="py-4 pl-4">{{ $order->payment_method->label() }}</td>
                                <td class="py-4 pl-4">{{ $order->release_method->label() }}</td>
                                <td class="py-4 pl-4"><x-status-pill :status="$order->status" /></td>
                                <td class="py-4 pl-4 pr-4">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-semibold text-forest underline-offset-4 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-forest rounded">
                                        View<span class="sr-only"> {{ $order->order_number }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if ($orders->hasPages())
        <nav class="mt-6 flex items-center justify-between text-sm" aria-label="Pages">
            <span class="text-muted">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
            <div class="flex gap-2">
                @if ($orders->onFirstPage())
                    <span class="rounded-full border border-line px-4 py-2 text-muted/50">← Newer</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="rounded-full border border-line bg-white px-4 py-2 font-semibold text-ink hover:border-sage">← Newer</a>
                @endif
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="rounded-full border border-line bg-white px-4 py-2 font-semibold text-ink hover:border-sage">Older →</a>
                @else
                    <span class="rounded-full border border-line px-4 py-2 text-muted/50">Older →</span>
                @endif
            </div>
        </nav>
    @endif
</x-app-layout>
