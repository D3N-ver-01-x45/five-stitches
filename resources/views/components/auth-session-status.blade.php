@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-sage bg-sage-light px-4 py-3 text-sm font-medium text-forest', 'role' => 'status']) }}>
        {{ $status }}
    </div>
@endif
