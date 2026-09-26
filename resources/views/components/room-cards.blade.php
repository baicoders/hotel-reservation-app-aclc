@php
    $href = ($isGuest ?? false) ? url('/guest/rooms/details/' . $room_id) : url('/rooms/details/' . $room_id);
@endphp
<article class="card group relative flex flex-col overflow-hidden transition-shadow hover:shadow-lg">
    <div class="relative aspect-[4/3] bg-lobby-50">
        <span aria-hidden="true" class="absolute inset-0 flex items-center justify-center text-sm text-muted">No photo yet</span>
        @if (!empty($photo_path))
            <img src="{{ asset('storage/' . $photo_path) }}" alt="Room {{ $roomNumber }}" loading="lazy"
                onerror="this.remove()" class="absolute inset-0 size-full object-cover">
        @endif
        <div class="absolute top-3 left-3">
            @include('components.key-tag', ['number' => $roomNumber])
        </div>
    </div>

    <div class="flex flex-1 flex-col gap-3 p-4">
        <div class="flex items-start justify-between gap-3">
            <h2 class="font-semibold capitalize">{{ $roomType }}</h2>
            @include('components.status-badge', ['status' => $status])
        </div>
        <p class="text-sm text-muted">
            <span class="text-lg font-semibold text-ink tabular-nums">${{ number_format((float) $roomPrice, 2) }}</span> per night
        </p>
        <a href="{{ $href }}" class="mt-auto text-sm font-semibold text-lobby-700 after:absolute after:inset-0 group-hover:underline">
            View details<span class="sr-only"> for room {{ $roomNumber }}</span>
        </a>
    </div>
</article>
