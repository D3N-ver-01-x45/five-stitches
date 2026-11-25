<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name', 'Five Stitches') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@1,400;1,500&family=Inter:wght@400;500;600&family=Zilla+Slab:wght@600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F3EFE6] font-sans text-muted antialiased" x-data="{ menuOpen: false }">
        <div class="flex min-h-screen">
            <!-- Mobile top bar -->
            <div class="fixed inset-x-0 top-0 z-30 flex h-16 items-center justify-between bg-ink px-4 lg:hidden">
                <span class="font-heading text-xl font-bold text-cream">Five Stitches</span>
                <button type="button" x-on:click="menuOpen = !menuOpen"
                        class="rounded-lg p-2 text-sage-light hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage"
                        x-bind:aria-expanded="menuOpen.toString()" aria-controls="sidebar" aria-label="Menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path x-show="!menuOpen" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="menuOpen" x-cloak stroke-linecap="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div x-show="menuOpen" x-cloak x-on:click="menuOpen = false" class="fixed inset-0 z-30 bg-ink/40 lg:hidden"></div>

            <!-- Sidebar (Figma: S4 · Sidebar) -->
            <aside id="sidebar"
                   class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col gap-7 overflow-y-auto bg-ink px-4 pb-6 pt-7 transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
                   x-bind:class="menuOpen && '!translate-x-0'">
                <a href="{{ route('dashboard') }}" class="flex flex-col gap-0.5 pl-3">
                    <span class="font-heading text-2xl font-bold text-cream">Five Stitches</span>
                    <span class="text-[13px] text-sage">{{ auth()->user()->isStaff() ? 'Shop admin' : 'My account' }}</span>
                </a>

                <nav class="flex flex-col gap-1" aria-label="Main">
                    @can('viewAny', App\Models\Order::class)
                        <x-sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">Orders</x-sidebar-link>
                        <x-sidebar-link soon>Product &amp; price</x-sidebar-link>
                        <x-sidebar-link soon>Shop settings</x-sidebar-link>
                    @endcan
                    <x-sidebar-link :href="url('/')">View shop</x-sidebar-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center rounded-[10px] p-3 text-left text-[15px] text-sage-light transition hover:bg-white/5 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-sage">
                            Log out
                        </button>
                    </form>
                </nav>

                <!-- Signed-in user -->
                <a href="{{ route('profile.edit') }}"
                   class="mt-auto flex items-center gap-3 rounded-[10px] p-3 transition hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage {{ request()->routeIs('profile.*') ? 'bg-[#3A4A3B]' : '' }}">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sage font-semibold text-ink">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                        <span class="block text-xs text-sage">{{ auth()->user()->role?->label() ?? 'Buyer' }} · My account</span>
                    </span>
                </a>
            </aside>

            <!-- Main -->
            <main class="min-w-0 flex-1 px-5 pb-12 pt-24 sm:px-8 lg:px-10 lg:pt-9">
                @isset($header)
                    <div class="mb-6">{{ $header }}</div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
