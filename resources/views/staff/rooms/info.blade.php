@extends('layout.staff')

@section('title', 'Room ' . $room->rm_number)
@section('eyebrow', 'Rooms')

@section('page-actions')
    @if (strtolower($room->status) !== 'maintenance')
        <a href="{{ url('/guest/rooms/reserve') . '?room_id=' . $room->id }}" class="btn btn-secondary">Book this room</a>
    @endif
    <a href="{{ url('/rooms/edit/' . $room->id) }}" class="btn btn-primary">Edit room</a>
@endsection

@section('page')
    <div class="grid gap-6 lg:grid-cols-5">
        <section class="card overflow-hidden lg:col-span-3" aria-label="Photos">
            @include('components.room-gallery', ['images' => $room->images, 'roomNumber' => $room->rm_number, 'emptyLink' => url('/rooms/edit/' . $room->id)])
        </section>

        <section class="card p-6 lg:col-span-2">
            @include('components.key-tag', ['number' => $room->rm_number, 'size' => 'lg'])
            <dl class="mt-6 divide-y divide-line">
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-muted">Type</dt>
                    <dd class="font-semibold capitalize">{{ $room->rm_type }}</dd>
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

            <form action="{{ url('/rooms/delete/' . $room->id) }}" id="room_delete_form" method="POST" data-busy-label="Deleting..." class="mt-6 border-t border-line pt-6"
                onsubmit="return handleFormSubmission(event, @js('Room ' . $room->rm_number . ' and its ' . $reservations->count() . ' ' . Str::plural('reservation', $reservations->count()) . ', with their payments, will be deleted. This cannot be undone.'), @js('Delete room ' . $room->rm_number . '?'), 'room_delete_form');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">Delete room</button>
            </form>
        </section>
    </div>

    <section class="card mt-6 overflow-hidden">
        <h2 class="border-b border-line px-5 py-4 font-semibold">Reservations</h2>
        @if ($reservations->isEmpty())
            <p class="px-5 py-8 text-center text-muted">No reservations for this room yet.</p>
        @else
            <ul class="divide-y divide-line">
                @foreach ($reservations as $reservation)
                    <li>
                        <a href="{{ url('/reservations/details/' . $reservation->id) }}"
                            class="flex flex-wrap items-center gap-x-4 gap-y-1 px-5 py-3 hover:bg-lobby-50">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-medium">{{ $reservation->guest_name }}</span>
                                <span class="block truncate text-sm text-muted">{{ $reservation->guest_email }}</span>
                            </span>
                            <span class="text-sm text-muted tabular-nums">
                                {{ \Illuminate\Support\Carbon::parse($reservation->check_in_date)->format('M j, Y') }}
                                to {{ \Illuminate\Support\Carbon::parse($reservation->check_out_date)->format('M j, Y') }}
                            </span>
                            @include('components.status-badge', ['status' => $reservation->status])
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
