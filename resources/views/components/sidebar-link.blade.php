@props(['href' => null, 'active' => false, 'soon' => false])

@php
    $base = 'flex w-full items-center justify-between rounded-[10px] p-3 text-[15px] transition focus:outline-none focus-visible:ring-2 focus-visible:ring-sage';
    $classes = $active
        ? $base.' bg-[#3A4A3B] font-semibold text-white'
        : $base.' text-sage-light hover:bg-white/5 hover:text-white';
@endphp

@if ($soon)
    <span {{ $attributes->merge(['class' => $base.' cursor-default text-sage-light/50']) }} aria-disabled="true">
        <span>{{ $slot }}</span>
        <span class="rounded-full border border-sage/30 px-2 py-0.5 text-[11px] font-medium text-sage/70">Soon</span>
    </span>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if ($active) aria-current="page" @endif>
        {{ $slot }}
    </a>
@endif
