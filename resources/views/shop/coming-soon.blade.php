{{-- Temporary front page for buyers until the shop home (Figma B1) is built. --}}
<x-guest-layout>
    <x-slot:title>Shop opening soon</x-slot:title>

    <h1 class="font-heading text-[2rem] font-bold leading-tight text-ink">
        Hi {{ Str::before(auth()->user()->name, ' ') }}, the shop opens soon
    </h1>
    <p class="mt-3 text-sm leading-relaxed text-muted">
        We're still stitching the online shop together. Bags, pouches, keychains and flowers
        will be here for ordering soon. For now, message us on our Facebook page to order.
    </p>

    <div class="mt-8 flex flex-col gap-3">
        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center justify-center rounded-full bg-forest px-7 py-3.5 text-base font-semibold text-white transition hover:bg-forest-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2">
            My account
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full border border-forest px-7 py-3.5 text-base font-semibold text-forest transition hover:bg-forest hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2">
                Log out
            </button>
        </form>
    </div>
</x-guest-layout>
