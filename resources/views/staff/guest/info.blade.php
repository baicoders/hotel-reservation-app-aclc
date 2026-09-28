@extends('layout.staff')

@section('title', $guest->full_name)
@section('eyebrow', 'Guests')

@section('page-actions')
    <a href="{{ url('/search-room') }}" class="btn btn-primary">Book a room</a>
@endsection

@section('page')
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="card self-start p-5">
            <h2 class="font-semibold">Profile</h2>
            <dl class="mt-4 space-y-4">
                <div><dt class="eyebrow">Email</dt><dd class="mt-1 break-words">{{ $guest->email }}</dd></div>
                <div><dt class="eyebrow">Phone</dt><dd class="mt-1">{{ $guest->phone_number ?: 'None' }}</dd></div>
                <div><dt class="eyebrow">Address</dt><dd class="mt-1">{{ $guest->address ?: 'None' }}</dd></div>
                <div><dt class="eyebrow">Added</dt><dd class="mt-1">{{ $guest->created_at?->format('M j, Y') }}</dd></div>
            </dl>

            <form action="{{ url('/guest/delete/' . $guest->id) }}" id="guest_delete_form" method="POST" data-busy-label="Deleting..." class="mt-6 border-t border-line pt-6"
                onsubmit="return handleFormSubmission(event, @js($guest->full_name . ' and their ' . $bookings->count() . ' ' . Str::plural('reservation', $bookings->count()) . ', with their payments, will be deleted. Rooms they are checked in to become available again. This cannot be undone.'), 'Delete guest?', 'guest_delete_form');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">Delete guest</button>
            </form>
        </section>

        <section class="card self-start overflow-hidden lg:col-span-2">
            <h2 class="border-b border-line px-5 py-4 font-semibold">Reservations</h2>
            @if ($bookings->isEmpty())
                <p class="px-5 py-8 text-center text-muted">No reservations yet.</p>
            @else
                <ul class="divide-y divide-line">
                    @foreach ($bookings as $booking)
                        <li>
                            <a href="{{ url('/reservations/details/' . $booking->id) }}"
                                class="flex flex-wrap items-center gap-x-4 gap-y-2 px-5 py-4 hover:bg-lobby-50">
                                @include('components.key-tag', ['number' => $booking->room_number])
                                <span class="min-w-0 flex-1">
                                    <span class="block font-medium">{{ ucfirst($booking->room_type) }}</span>
                                    <span class="block text-sm text-muted tabular-nums">
                                        {{ \Illuminate\Support\Carbon::parse($booking->check_in_date)->format('M j, Y') }}
                                        to {{ \Illuminate\Support\Carbon::parse($booking->check_out_date)->format('M j, Y') }}
                                    </span>
                                </span>
                                <span class="flex flex-col items-end gap-1 text-xs text-muted">
                                    <span>Reservation @include('components.status-badge', ['status' => $booking->status])</span>
                                    <span>Room @include('components.status-badge', ['status' => $booking->room_status])</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
