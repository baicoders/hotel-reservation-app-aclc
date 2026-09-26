@php
    $key = str_replace('canceled', 'cancelled', strtolower((string) $status));
    $tone = [
        'available' => 'bg-moss-100 text-moss-700',
        'checked_in' => 'bg-moss-100 text-moss-700',
        'confirmed' => 'bg-lobby-100 text-lobby-900',
        'pending' => 'bg-brass-100 text-brass-700',
        'unavailable' => 'bg-brass-100 text-brass-700',
        'booked' => 'bg-brass-100 text-brass-700',
        'cancelled' => 'bg-brick-100 text-brick-700',
    ][$key] ?? 'bg-porcelain text-muted ring-1 ring-line';
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap {{ $tone }}">
    {{ ucfirst(str_replace('_', ' ', $key)) }}
</span>
