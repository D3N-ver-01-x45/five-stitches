{{-- Figma: Screens › BUYER pages › "B3 · Cart" (node 29:152) --}}
<x-shop-layout title="Your cart">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-6 px-5 pb-[88px] pt-9 sm:px-10 xl:px-[120px]">
        <a href="{{ route('home') }}#shop" class="self-start py-2.5 text-[15px] font-semibold text-forest hover:underline hover:underline-offset-4">← Continue shopping</a>
        <h1 class="font-heading text-[40px] font-bold leading-none text-ink sm:text-5xl">Your cart</h1>

        <x-auth-session-status :status="session('status')" />

        @if ($lines->isEmpty())
            <div class="rounded-[20px] border border-dashed border-line bg-white px-6 py-16 text-center">
                <p class="font-heading text-2xl font-bold text-ink">Your cart is empty</p>
                <p class="mt-2 text-sm text-muted">Bags, pouches, keychains and flowers are waiting for you.</p>
                <a href="{{ route('home') }}#shop" class="mt-6 inline-block rounded-full bg-forest px-7 py-4 text-[17px] font-semibold leading-none text-white hover:bg-forest-dark">Shop now</a>
            </div>
        @else
            <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
                <!-- Items -->
                <ul class="flex-1 overflow-hidden rounded-[20px] border border-line bg-white">
                    @foreach ($lines as $line)
                        <li class="flex flex-wrap items-center gap-4 border-b border-line px-5 py-[18px] last:border-b-0 sm:flex-nowrap">
                            <a href="{{ route('products.show', $line->product) }}" class="shrink-0">
                                <img src="{{ $line->product->imageUrl() }}" alt="" width="76" height="76" class="h-[76px] w-[76px] rounded-xl object-cover">
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('products.show', $line->product) }}" class="text-[17px] font-semibold text-ink hover:underline">{{ $line->product->name }}</a>
                                <p class="text-[13px] text-muted">{{ $line->product->price() }} each · ready in {{ $line->product->readyIn() }}</p>
                            </div>

                            <!-- Stepper: each button is its own small form, so it works without JavaScript -->
                            <div class="flex items-center rounded-full border border-line bg-white" role="group" aria-label="Quantity of {{ $line->product->name }}">
                                <form method="POST" action="{{ route('cart.update', $line->product) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $line->quantity - 1 }}">
                                    <button type="submit" class="flex h-[46px] w-[46px] items-center justify-center rounded-full text-[21px] text-ink hover:bg-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-forest"
                                            aria-label="{{ $line->quantity === 1 ? 'Remove' : 'Decrease' }} {{ $line->product->name }}">−</button>
                                </form>
                                <span class="flex h-[46px] w-[46px] items-center justify-center text-base font-semibold text-ink" aria-live="polite">{{ $line->quantity }}</span>
                                <form method="POST" action="{{ route('cart.update', $line->product) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ min($line->quantity + 1, App\Services\Cart::MAX_QUANTITY) }}">
                                    <button type="submit" class="flex h-[46px] w-[46px] items-center justify-center rounded-full text-[21px] text-ink hover:bg-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-forest disabled:opacity-30"
                                            aria-label="Increase {{ $line->product->name }}" @disabled($line->quantity >= App\Services\Cart::MAX_QUANTITY)>+</button>
                                </form>
                            </div>

                            <p class="w-16 text-right text-base font-semibold text-ink">{{ App\Support\Money::peso($line->total) }}</p>

                            <form method="POST" action="{{ route('cart.destroy', $line->product) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded text-sm font-semibold text-[#8A3B33] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8A3B33]">
                                    Remove<span class="sr-only"> {{ $line->product->name }}</span>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <!-- Summary -->
                <aside class="flex w-full shrink-0 flex-col gap-3 rounded-[20px] border border-line bg-white p-6 lg:w-[360px]">
                    <h2 class="text-xl font-semibold text-ink">Summary</h2>
                    <div class="flex justify-between text-[15px]"><span class="text-muted">Items</span><span class="text-ink">{{ $itemCount }}</span></div>
                    <div class="flex justify-between text-[19px] font-semibold text-ink"><span>Estimated total</span><span>{{ App\Support\Money::peso($total) }}</span></div>
                    <p class="text-[13px] leading-normal text-muted">
                        Base prices. Final price, release date and any delivery fee are confirmed by message. Made-to-order items need a 50% down payment.
                    </p>
                    {{-- Order form (Figma B4) is the next step; until then the button explains how to order. --}}
                    <button type="button" disabled
                            class="w-full cursor-not-allowed rounded-full bg-forest px-7 py-4 text-[17px] font-semibold leading-none text-white opacity-60">
                        Check out
                    </button>
                    <p class="text-center text-[13px] text-muted">Online checkout opens soon. For now, message us on Facebook with your cart.</p>
                </aside>
            </div>
        @endif
    </div>
</x-shop-layout>
