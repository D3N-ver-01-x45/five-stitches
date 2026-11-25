@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-12 rounded-lg border-line bg-white px-4 text-ink placeholder:text-muted/60 focus:border-forest focus:ring-forest disabled:opacity-60']) }}>
