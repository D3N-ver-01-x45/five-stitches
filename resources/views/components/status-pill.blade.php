@props(['status'])

{{-- Figma component "Status Pill": New / Being crocheted / Shipped --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-[13px] font-semibold leading-none '.$status->pillClasses()]) }}>
    {{ $status->label() }}
</span>
