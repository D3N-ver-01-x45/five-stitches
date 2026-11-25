<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-forest bg-transparent px-5 py-2.5 text-sm font-semibold text-forest transition hover:bg-forest hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
