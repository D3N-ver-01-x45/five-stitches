{{-- Figma: Screens › BUYER pages › "B1 · Shop (Home)" (node 28:92) --}}
<x-shop-layout>
    <!-- Hero -->
    <section class="mx-auto flex max-w-[1440px] flex-col items-center gap-12 px-5 pb-14 pt-10 sm:px-10 lg:flex-row lg:pt-16 xl:px-[120px]">
        <div class="flex flex-1 flex-col items-start gap-5">
            <p class="font-script text-2xl italic text-forest">Crafted with Care, Stitched with Love</p>
            <h1 class="max-w-[560px] font-heading text-[42px] font-bold leading-[1.02] text-ink sm:text-[60px]">
                Handmade crochet, made one stitch at a time.
            </h1>
            <p class="max-w-[520px] text-lg leading-[1.55] text-muted">
                Bags, pouches, keychains and flowers, handmade in Legazpi City. Pay by cash or GCash — pick up, meet on campus, or have it delivered.
            </p>
            <a href="#shop"
               class="rounded-full bg-forest px-7 py-4 text-[17px] font-semibold leading-none text-white transition hover:bg-forest-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2 focus-visible:ring-offset-cream">
                Shop now
            </a>
        </div>
        @if ($heroProducts->isNotEmpty())
            <div class="grid w-full max-w-[534px] shrink-0 grid-cols-2 gap-3.5">
                @foreach ($heroProducts as $product)
                    <a href="{{ route('products.show', $product) }}" class="block aspect-square overflow-hidden rounded-[20px] focus:outline-none focus-visible:ring-2 focus-visible:ring-forest">
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="260" height="260" class="h-full w-full object-cover">
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Shop -->
    <section id="shop" class="scroll-mt-4 bg-white">
        <div class="mx-auto flex max-w-[1440px] flex-col gap-6 px-5 pb-[72px] pt-14 sm:px-10 xl:px-[120px]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="font-heading text-[42px] font-bold leading-none text-ink">
                    @if ($search !== '')
                        Results for “{{ $search }}”
                    @else
                        Shop
                    @endif
                </h2>
                <nav class="flex flex-wrap gap-2" aria-label="Filter by category">
                    @php $categories = ['' => 'All'] + collect(App\Enums\ProductCategory::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all(); @endphp
                    @foreach ($categories as $value => $label)
                        @php $isActive = ($activeCategory?->value ?? '') === $value; @endphp
                        <a href="{{ route('home', array_filter(['category' => $value, 'q' => $search])) }}#shop"
                           @if ($isActive) aria-current="true" @endif
                           class="rounded-full px-[18px] py-2.5 text-sm font-semibold leading-none transition focus:outline-none focus-visible:ring-2 focus-visible:ring-forest
                                  {{ $isActive ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:border-sage' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>

            @if ($products->isEmpty())
                <div class="rounded-[20px] border border-dashed border-line px-6 py-16 text-center">
                    <p class="font-heading text-2xl font-bold text-ink">Nothing here yet</p>
                    <p class="mt-2 text-sm text-muted">
                        @if ($search !== '')
                            No items match “{{ $search }}”.
                        @else
                            No items in this category right now.
                        @endif
                        <a href="{{ route('home') }}#shop" class="font-semibold text-forest underline underline-offset-4">See everything</a>
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 min-[480px]:grid-cols-2 lg:grid-cols-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- How ordering works -->
    <section id="how-to-order" class="mx-auto max-w-[1440px] scroll-mt-4 px-5 py-16 sm:px-10 xl:px-[120px]">
        <h2 class="font-heading text-[40px] font-bold leading-none text-ink">How ordering works</h2>
        <ol class="mt-7 grid gap-10 md:grid-cols-3">
            @foreach ([
                ['Add to cart', 'Pick your items and quantity. Note your color, size and add-ons.'],
                ['Fill in the order form', 'Leave your name, contact and release method. We message you the final price and schedule.'],
                ['Pay, then we crochet', 'Made-to-order items need a 50% down payment. Pay the balance on release.'],
            ] as $i => [$title, $body])
                <li class="flex flex-col items-start gap-2.5">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-sage-light font-heading text-xl font-bold text-forest" aria-hidden="true">{{ $i + 1 }}</span>
                    <h3 class="font-heading text-[22px] font-bold text-ink">{{ $title }}</h3>
                    <p class="text-base leading-normal text-muted">{{ $body }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <!-- FAQ -->
    <section id="faq" class="mx-auto max-w-[1440px] scroll-mt-4 px-5 pb-[72px] sm:px-10 xl:px-[120px]">
        <h2 class="font-heading text-[40px] font-bold leading-none text-ink">Questions</h2>
        <dl class="mt-5">
            @foreach ([
                ['How long until my order is ready?', 'Keychains and flowers 1–2 days, pouches 2–3 days, bags 4–7 days. Bulk or complicated orders are scheduled with you.'],
                ['How do I pay?', 'Cash for pickups, campus meet-ups and events; GCash for online orders. Made-to-order items need a 50% down payment.'],
                ['What if my item has a defect?', 'Report it within 7 days of release, and we will repair, remake or replace it.'],
            ] as [$question, $answer])
                <div class="flex flex-col gap-1.5 border-t border-line py-4">
                    <dt class="text-[17px] font-semibold text-ink">{{ $question }}</dt>
                    <dd class="text-base leading-normal text-muted">{{ $answer }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
</x-shop-layout>
