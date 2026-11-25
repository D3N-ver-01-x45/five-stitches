<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-[#B4382B] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#9A2F24] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B4382B] focus-visible:ring-offset-2']) }}>
    {{ $slot }}
</button>
