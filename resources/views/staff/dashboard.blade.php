@extends('layout.staff')

@section('title', 'Dashboard')
@section('eyebrow', now()->format('l, F j'))

@section('page-actions')
    <a href="{{ url('/rooms/create') }}" class="btn btn-secondary">Add room</a>
    <a href="{{ url('/search-room') }}" class="btn btn-primary">Book a room</a>
@endsection

@section('page')
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['Rooms', $stats['rooms'], '/rooms'],
            ['Available now', $stats['available'], '/rooms'],
            ['Open reservations', $stats['active'], '/reservations'],
            ['Guests', $stats['guests'], '/guest'],
        ] as [$label, $value, $link])
            <a href="{{ url($link) }}" class="card block p-5 transition-colors hover:border-lobby-700">
                <span class="eyebrow block">{{ $label }}</span>
                <span class="mt-2 block font-display text-5xl font-bold text-lobby-900 tabular-nums">{{ $value }}</span>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <section class="card self-start overflow-hidden lg:col-span-2">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h2 class="font-semibold">Recent reservations</h2>
                <a href="{{ url('/reservations') }}" class="text-sm font-semibold text-lobby-700 hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-line">
                @forelse ($latestReservations as $reservation)
                    <li>
                        <a href="{{ url('/reservations/details/' . $reservation->id) }}"
                            class="flex flex-wrap items-center gap-x-4 gap-y-1 px-5 py-3 hover:bg-lobby-50">
                            <span class="w-16 font-display text-xl font-bold text-lobby-900">{{ $reservation->room_number }}</span>
                            <span class="min-w-0 flex-1 truncate font-medium">{{ $reservation->guest_name }}</span>
                            <span class="text-sm text-muted tabular-nums">
                                {{ \Illuminate\Support\Carbon::parse($reservation->check_in_date)->format('M j') }}
                                to {{ \Illuminate\Support\Carbon::parse($reservation->check_out_date)->format('M j') }}
                            </span>
                            @include('components.status-badge', ['status' => $reservation->status])
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-muted">
                        No reservations yet. New bookings from the guest site appear here.
                    </li>
                @endforelse
            </ul>
        </section>

        <div class="flex flex-col gap-6">
            <section class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <h2 class="font-semibold">Rooms not available</h2>
                    <a href="{{ url('/rooms') }}" class="text-sm font-semibold text-lobby-700 hover:underline">All rooms</a>
                </div>
                <ul class="divide-y divide-line">
                    @forelse ($unavailableRooms as $room)
                        <li>
                            <a href="{{ url('/rooms/details/' . $room->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-lobby-50">
                                <span class="w-16 font-display text-xl font-bold text-lobby-900">{{ $room->rm_number }}</span>
                                <span class="min-w-0 flex-1 truncate text-sm text-muted">{{ $room->rm_type }}</span>
                                @include('components.status-badge', ['status' => $room->status])
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-muted">Every room is available.</li>
                    @endforelse
                </ul>
            </section>
    
            <section class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <h2 class="font-semibold">New guests</h2>
                    <a href="{{ url('/guest') }}" class="text-sm font-semibold text-lobby-700 hover:underline">View all</a>
                </div>
                <ul class="divide-y divide-line">
                    @forelse ($latestGuests as $guest)
                        <li>
                            <a href="{{ url('/guest/details/' . $guest->id) }}" class="block px-5 py-3 hover:bg-lobby-50">
                                <span class="block truncate font-medium">{{ $guest->full_name }}</span>
                                <span class="block truncate text-sm text-muted">{{ $guest->email }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-muted">No guests yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
@endsection
