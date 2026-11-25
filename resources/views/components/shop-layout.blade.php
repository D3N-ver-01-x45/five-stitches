@props(['title' => null])
{{-- Customer pages layout: Figma components "Site Header" (11:95) and "Site Footer" (28:76). --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Five Stitches — handmade crochet bags, pouches, keychains and flowers from Legazpi City.">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Five Stitches') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@1,400;1,500&family=Inter:wght@400;500;600&family=Zilla+Slab:wght@600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream font-sans text-muted antialiased">
        <!-- Site Header -->
        <header class="border-b border-line bg-cream">
            <div class="mx-auto flex max-w-[1440px] flex-wrap items-center gap-x-8 gap-y-3 px-5 py-2.5 sm:px-10 lg:flex-nowrap xl:px-20">
                <a href="{{ route('home') }}" class="shrink-0 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-forest">
                    <x-application-logo class="h-auto w-[136px] sm:w-[170px] xl:w-[204px]" />
                </a>

                <nav class="hidden items-center gap-7 text-[15px] font-medium text-ink md:flex" aria-label="Main">
                    <a href="{{ route('home') }}#shop" class="hover:text-forest">Shop</a>
                    <a href="{{ route('home') }}#how-to-order" class="whitespace-nowrap hover:text-forest">How to order</a>
                    <a href="{{ route('home') }}#faq" class="hover:text-forest">FAQ</a>
                </nav>

                <!-- Cart + account (right side; moves before search on phones) -->
                <div class="ml-auto flex items-center gap-4 sm:gap-6 lg:order-last">
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2 rounded-lg text-[15px] font-medium text-ink hover:text-forest focus:outline-none focus-visible:ring-2 focus-visible:ring-forest"
                       aria-label="Cart, {{ $cartCount }} {{ Str::plural('item', $cartCount) }}">
                        {{-- icon/bag --}}
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 7h12l-1 13H7L6 7Z" /><path d="M9 7V6a3 3 0 0 1 6 0v1" />
                        </svg>
                        <span class="hidden sm:inline">Cart</span>
                        <span class="rounded-full bg-coral px-[7px] py-0.5 text-xs font-semibold leading-none text-ink">{{ $cartCount }}</span>
                    </a>

                    @auth
                        <a href="{{ auth()->user()->isStaff() ? route('admin.orders.index') : route('profile.edit') }}"
                           class="whitespace-nowrap rounded-full border-[1.5px] border-forest px-[18px] py-2.5 text-sm font-semibold leading-none text-forest transition hover:bg-forest hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2">
                            {{ auth()->user()->isStaff() ? 'Dashboard' : 'My account' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="whitespace-nowrap rounded-full border-[1.5px] border-forest px-[18px] py-2.5 text-sm font-semibold leading-none text-forest transition hover:bg-forest hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2">
                            Log in
                        </a>
                    @endauth
                </div>

                <!-- Search -->
                <form method="GET" action="{{ route('home') }}#shop" role="search" class="order-last w-full lg:order-none lg:w-auto lg:flex-1">
                    <label for="site-search" class="sr-only">Search crochet items</label>
                    <div class="flex items-center gap-2.5 rounded-full border border-line bg-white px-4 py-3 focus-within:border-forest focus-within:ring-1 focus-within:ring-forest">
                        {{-- icon/search --}}
                        <svg class="h-[18px] w-[18px] shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" />
                        </svg>
                        <input id="site-search" type="search" name="q" value="{{ request('q') }}" maxlength="80"
                               placeholder="Search crochet items…"
                               class="w-full border-0 bg-transparent p-0 text-sm text-ink placeholder:text-muted focus:ring-0">
                    </div>
                </form>
            </div>
        </header>

        <!-- "Added to cart" notice -->
        @if (session('cart_added'))
            <div class="border-b border-sage bg-sage-light" role="status">
                <div class="mx-auto flex max-w-[1440px] flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm sm:px-10 xl:px-[120px]">
                    <span class="font-medium text-forest">{{ session('cart_added') }}</span>
                    <a href="{{ route('cart.index') }}" class="font-semibold text-forest underline underline-offset-4">View cart →</a>
                </div>
            </div>
        @endif

        <main>
            {{ $slot }}
        </main>

        <!-- Site Footer -->
        <footer class="bg-ink">
            <div class="mx-auto flex max-w-[1440px] flex-col gap-10 px-5 py-14 sm:flex-row sm:gap-16 sm:px-10 xl:px-[120px]">
                <div class="flex flex-1 flex-col gap-2">
                    <p class="font-heading text-[30px] font-bold text-cream">Five Stitches</p>
                    <p class="font-script text-[19px] italic text-sage">Crafted with Care, Stitched with Love</p>
                    <p class="max-w-[360px] text-sm leading-normal text-sage-light">
                        Barangay 42 – Rawis, Legazpi City, Albay. Orders and inquiries through our Facebook page.
                    </p>
                </div>
                <nav class="flex flex-col gap-2.5 text-sm" aria-label="Shop categories">
                    <p class="text-[15px] font-semibold text-cream">Shop</p>
                    @foreach (App\Enums\ProductCategory::cases() as $category)
                        <a href="{{ route('home', ['category' => $category->value]) }}#shop" class="text-sage-light hover:text-white">{{ $category->label() }}</a>
                    @endforeach
                </nav>
                <nav class="flex flex-col gap-2.5 text-sm" aria-label="Help">
                    <p class="text-[15px] font-semibold text-cream">Help</p>
                    <a href="{{ route('home') }}#how-to-order" class="text-sage-light hover:text-white">How to order</a>
                    <a href="{{ route('home') }}#faq" class="text-sage-light hover:text-white">Payment &amp; release</a>
                    <a href="{{ route('home') }}#faq" class="text-sage-light hover:text-white">After-sales care</a>
                </nav>
            </div>
        </footer>
    </body>
</html>
