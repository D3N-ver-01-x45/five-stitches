{{-- Figma: Screens › BUYER pages › "B2 · Product — Tote Bag" (node 29:90) --}}
<x-shop-layout :title="$product->name">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-6 px-5 pb-[88px] pt-9 sm:px-10 xl:px-[120px]">
        <a href="{{ route('home') }}#shop" class="self-start py-2.5 text-[15px] font-semibold text-forest hover:underline hover:underline-offset-4">← Back to shop</a>

        <div class="flex flex-col gap-10 lg:flex-row lg:gap-14">
            <div class="aspect-square w-full shrink-0 overflow-hidden rounded-3xl bg-white lg:w-[560px]">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="560" height="560" class="h-full w-full object-cover">
            </div>

            <div class="flex flex-1 flex-col items-start gap-[18px]">
                <p class="text-[13px] font-semibold uppercase tracking-[0.08em] text-forest">{{ $product->category->label() }}</p>
                <h1 class="font-heading text-[40px] font-bold leading-none text-ink sm:text-[54px]">{{ $product->name }}</h1>
                <p class="text-[30px] font-semibold leading-none text-ink">from {{ $product->price() }}</p>
                <p class="text-[17px] leading-[1.55] text-muted">{{ $product->description }}</p>

                @if ($product->options)
                    <div class="flex w-full flex-col gap-1.5 rounded-2xl border border-line bg-white px-[18px] py-4 text-[15px]">
                        <p class="font-semibold text-ink">You can choose</p>
                        <p class="leading-normal text-muted">{{ $product->options }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('cart.store') }}" class="flex w-full flex-col items-start gap-[18px]"
                      x-data="{ qty: {{ (int) old('quantity', 1) }}, max: {{ App\Services\Cart::MAX_QUANTITY }} }">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex flex-col gap-2">
                        <label for="quantity" class="text-[15px] text-muted">Quantity</label>
                        <div class="flex items-center rounded-full border border-line bg-white">
                            <button type="button" x-on:click="qty = Math.max(1, qty - 1)" class="flex h-[46px] w-[46px] items-center justify-center rounded-full text-[21px] text-ink hover:bg-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-forest" aria-label="Decrease quantity">−</button>
                            <input id="quantity" name="quantity" type="number" min="1" max="{{ App\Services\Cart::MAX_QUANTITY }}" x-model.number="qty" value="{{ (int) old('quantity', 1) }}"
                                   class="h-[46px] w-[46px] border-0 bg-transparent p-0 text-center text-base font-semibold text-ink [appearance:textfield] focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            <button type="button" x-on:click="qty = Math.min(max, qty + 1)" class="flex h-[46px] w-[46px] items-center justify-center rounded-full text-[21px] text-ink hover:bg-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-forest" aria-label="Increase quantity">+</button>
                        </div>
                        <x-input-error :messages="$errors->get('quantity')" />
                        <x-input-error :messages="$errors->get('product_id')" />
                    </div>

                    <x-primary-button class="w-full max-w-[420px] text-[17px]">Add to cart</x-primary-button>
                </form>

                <p class="text-sm leading-normal text-muted">
                    Ready in {{ $product->readyIn() }} · Bigger sizes, complex patterns and add-ons are quoted before your order is confirmed.
                </p>
            </div>
        </div>
    </div>
</x-shop-layout>
