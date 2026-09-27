@extends('layout.guest')

@section('title', 'Room ' . $room->rm_number)
@section('eyebrow', 'Room details')

@section('page')
    @php $bookable = strtolower($room->status) !== 'maintenance'; @endphp
    <div class="grid gap-6 lg:grid-cols-5">
        <section class="card overflow-hidden lg:col-span-3" aria-label="Photos">
            @include('components.room-gallery', ['images' => $photos, 'roomNumber' => $room->rm_number])
        </section>

        <section class="card self-start p-6 lg:col-span-2">
            @include('components.key-tag', ['number' => $room->rm_number, 'size' => 'lg'])
            <dl class="mt-6 divide-y divide-line">
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-muted">Type</dt>
                    <dd class="font-semibold">{{ ucfirst($room->rm_type) }}</dd>
                </div>
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-muted">Price per night</dt>
                    <dd class="font-semibold tabular-nums">${{ number_format($room->rm_price_per_night, 2) }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3">
                    <dt class="text-muted">Status</dt>
                    <dd>@include('components.status-badge', ['status' => $room->status])</dd>
                </div>
            </dl>

            @if ($bookable)
                <a href="{{ url('/guest/rooms/reserve') . '?room_id=' . $room->id }}" class="btn btn-primary mt-6 w-full py-3 text-base">Reserve this room</a>
            @else
                <div role="note" class="mt-6 rounded-lg bg-brass-100 p-4 text-sm text-brass-700">
                    <p class="font-semibold">This room is not available to book right now.</p>
                    <p class="mt-1">Choose another room from the available rooms.</p>
                </div>
            @endif
            <a href="{{ url('/search-room') }}" class="btn btn-secondary mt-3 w-full">{{ $bookable ? 'Back to rooms' : 'See available rooms' }}</a>
        </section>
    </div>
@endsection
