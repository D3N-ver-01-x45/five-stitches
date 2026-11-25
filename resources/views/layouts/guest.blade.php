<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name', 'Five Stitches') }}</title>

        <!-- Fonts (same as Figma): Zilla Slab, Alegreya Italic, Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@1,400;1,500&family=Inter:wght@400;500;600&family=Zilla+Slab:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream font-sans text-muted antialiased">
        <div class="flex min-h-screen">
            <!-- Brand panel (desktop only) -->
            <aside class="relative hidden w-[46%] max-w-[680px] overflow-hidden bg-ink lg:block">
                <img src="{{ asset('images/makers.webp') }}" alt=""
                     class="absolute inset-0 h-full w-full object-cover opacity-55">
                <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-ink/10"></div>

                <div class="relative flex h-full flex-col justify-end p-14 text-cream">
                    <p class="font-script text-xl italic text-sage">Crafted with Care, Stitched with Love</p>
                    <h2 class="mt-3 font-heading text-5xl font-bold leading-tight text-white">
                        Handmade crochet, made one stitch at a time.
                    </h2>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-cream/85">
                        Bags, pouches, keychains and flowers, handmade in Legazpi City.
                    </p>
                </div>
            </aside>

            <!-- Form side -->
            <main class="flex flex-1 flex-col items-center justify-center px-6 py-10 sm:px-10">
                <a href="{{ url('/') }}" class="mb-8 inline-block focus:outline-none focus-visible:ring-2 focus-visible:ring-forest rounded-lg">
                    <x-application-logo class="h-auto w-[204px]" />
                </a>

                <div class="w-full max-w-[440px] rounded-2xl border border-line bg-white p-8 shadow-[0_1px_2px_rgba(39,51,39,0.04),0_8px_24px_rgba(39,51,39,0.06)] sm:p-10">
                    {{ $slot }}
                </div>

                <p class="mt-8 text-center text-sm text-muted/80">
                    &copy; {{ date('Y') }} Five Stitches · Barangay 42 – Rawis, Legazpi City
                </p>
            </main>
        </div>
    </body>
</html>
