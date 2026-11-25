{{-- Order details. Not in Figma yet — built from the S4 / B5 styles and the "Option" component. --}}
<x-app-layout>
    <x-slot:title>{{ $order->order_number }}</x-slot:title>

    <a href="{{ route('admin.orders.index') }}" class="inline-block py-2 text-sm text-muted underline-offset-4 hover:text-ink hover:underline">← Back to orders</a>

    <div class="mt-2 flex flex-wrap items-center gap-4">
        <h1 class="font-heading text-[40px] font-bold leading-none text-ink">{{ $order->order_number }}</h1>
        <x-status-pill :status="$order->status" />
    </div>
    <p class="mt-2 text-sm text-muted">Placed {{ $order->created_at->format('M j, Y · g:i A') }}</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            <!-- Items -->
            <section class="overflow-hidden rounded-2xl border border-line bg-white">
                <h2 class="border-b border-line bg-[#F7F4EE] px-5 py-3 text-[13px] font-semibold text-muted">Items</h2>
                <ul class="divide-y divide-line">
                    @foreach ($order->items as $item)
                        <li class="flex items-start justify-between gap-4 px-5 py-4">
                            <div>
                                <p class="font-semibold text-ink">{{ $item->quantity }} × {{ $item->product_name }}</p>
                                <p class="mt-0.5 text-sm text-muted">
                                    {{ $item->color ?? 'No color chosen' }} · ₱{{ number_format((float) $item->unit_price) }} each
                                    @if ($item->details) · {{ $item->details }} @endif
                                </p>
                            </div>
                            <p class="font-semibold text-ink">₱{{ number_format($item->lineTotal()) }}</p>
                        </li>
                    @endforeach
                </ul>
                <dl class="space-y-2 border-t border-line px-5 py-4 text-sm">
                    <div class="flex justify-between font-semibold text-ink"><dt class="text-base">Estimated total</dt><dd class="text-base">₱{{ number_format((float) $order->total) }}</dd></div>
                    <div class="flex justify-between"><dt>50% down payment</dt><dd>₱{{ number_format((float) $order->downPayment()) }}</dd></div>
                </dl>
            </section>

            <!-- Customer -->
            <section class="rounded-2xl border border-line bg-white p-5">
                <h2 class="font-heading text-xl font-bold text-ink">Customer</h2>
                <dl class="mt-4 grid gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-muted">Name</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->customer_name }}</dd></div>
                    <div><dt class="text-muted">Facebook or mobile</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->customer_contact }}</dd></div>
                    <div><dt class="text-muted">Release</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->release_method->label() }}</dd></div>
                    <div><dt class="text-muted">Date needed</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->date_needed?->format('M j, Y') ?? 'Not set' }}</dd></div>
                    <div><dt class="text-muted">Payment</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->payment_method->label() }}</dd></div>
                    @if ($order->delivery_address)
                        <div><dt class="text-muted">Delivery address</dt><dd class="mt-0.5 font-medium text-ink">{{ $order->delivery_address }}</dd></div>
                    @endif
                    @if ($order->notes)
                        <div class="sm:col-span-2"><dt class="text-muted">Design details / notes</dt><dd class="mt-0.5 whitespace-pre-line font-medium text-ink">{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </section>
        </div>

        <!-- Update status -->
        <section class="h-fit rounded-2xl border border-line bg-white p-5">
            <h2 class="font-heading text-xl font-bold text-ink">Update order</h2>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                @csrf
                @method('PATCH')

                <fieldset class="space-y-3">
                    <legend class="sr-only">Status</legend>
                    @foreach (App\Enums\OrderStatus::cases() as $status)
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-line p-4 transition hover:border-sage has-[:checked]:border-forest has-[:checked]:ring-1 has-[:checked]:ring-forest">
                            <input type="radio" name="status" value="{{ $status->value }}"
                                   class="mt-0.5 h-[18px] w-[18px] border-line text-forest focus:ring-forest"
                                   @checked(old('status', $order->status->value) === $status->value)>
                            <span>
                                <span class="block text-sm font-semibold text-ink">{{ $status->label() }}</span>
                                <span class="mt-0.5 block text-[13px] text-muted">{{ $status->description() }}</span>
                            </span>
                        </label>
                    @endforeach
                </fieldset>
                <x-input-error :messages="$errors->get('status')" />

                <label class="flex cursor-pointer items-center gap-2.5 pt-2">
                    <input type="hidden" name="down_payment_paid" value="0">
                    <input type="checkbox" name="down_payment_paid" value="1"
                           class="h-[18px] w-[18px] rounded border-line text-forest focus:ring-forest"
                           @checked(old('down_payment_paid', $order->down_payment_paid))>
                    <span class="text-sm text-ink">50% down payment received</span>
                </label>

                <x-primary-button class="mt-2 w-full">Save changes</x-primary-button>
            </form>
        </section>
    </div>
</x-app-layout>
