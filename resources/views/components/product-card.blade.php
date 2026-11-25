@props(['product'])
{{-- Figma component "Product Card" (11:85) --}}
<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-[20px] border border-line bg-white transition hover:border-sage']) }}>
    <a href="{{ route('products.show', $product) }}" class="block aspect-square overflow-hidden focus:outline-none" tabindex="-1" aria-hidden="true">
        <img src="{{ $product->imageUrl() }}" alt="" width="282" height="282" loading="lazy"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
    </a>
    <div class="flex flex-1 flex-col gap-1.5 px-[18px] pb-[18px] pt-4">
        <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-forest">{{ $product->category->label() }}</p>
        <h3 class="font-heading text-[21px] font-bold leading-tight text-ink">
            <a href="{{ route('products.show', $product) }}" class="rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-forest">{{ $product->name }}</a>
        </h3>
        <p class="text-[13px] text-muted">Ready in {{ $product->readyIn() }}</p>
        <div class="mt-auto flex items-center justify-between gap-2 pt-2">
            <p class="text-lg font-semibold text-ink">from {{ $product->price() }}</p>
            <form method="POST" action="{{ route('cart.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit"
                        class="whitespace-nowrap rounded-full bg-forest px-3.5 py-[9px] text-[13px] font-semibold leading-none text-white transition hover:bg-forest-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2"
                        aria-label="Add {{ $product->name }} to cart">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</article>
