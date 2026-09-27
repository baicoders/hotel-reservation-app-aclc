@php
    $dates = empty($dateQuery) ? '' : '&' . $dateQuery;
    $href = ($isGuest ?? false) ? url('/guest/rooms/details/' . $room_id) . ($dates ? '?' . ltrim($dates, '&') : '') : url('/rooms/details/' . $room_id);
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
        <div class="mt-auto flex items-center justify-between gap-3 text-sm font-semibold">
            <a href="{{ $href }}" class="text-lobby-700 after:absolute after:inset-0 group-hover:underline">
                View details<span class="sr-only"> for room {{ $roomNumber }}</span>
            </a>
            @if ($isGuest ?? false)
                <a href="{{ url('/guest/rooms/reserve') . '?room_id=' . $room_id . $dates }}" class="btn btn-primary relative z-10 px-3 py-1.5">
                    Reserve<span class="sr-only"> room {{ $roomNumber }}</span>
                </a>
            @endif
            @unless ($isGuest ?? false)
                @php $count = $reservationCount ?? 0; @endphp
                <div class="relative z-10 flex items-center gap-4">
                    <a href="{{ url('/rooms/edit/' . $room_id) }}" class="text-lobby-700 hover:underline">
                        Edit<span class="sr-only"> room {{ $roomNumber }}</span>
                    </a>
                    <form action="{{ url('/rooms/delete/' . $room_id) }}" method="POST" id="room_card_delete_{{ $room_id }}" data-busy-label="Deleting..."
                        onsubmit="return handleFormSubmission(event, @js('Room ' . $roomNumber . ' and its ' . $count . ' ' . Str::plural('reservation', $count) . ', with their payments, will be deleted. This cannot be undone.'), @js('Delete room ' . $roomNumber . '?'), 'room_card_delete_{{ $room_id }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-brick-700 hover:underline">
                            Delete<span class="sr-only"> room {{ $roomNumber }}</span>
                        </button>
                    </form>
                </div>
            @endunless
        </div>
    </div>
</article>
