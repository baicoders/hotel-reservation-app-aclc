@extends('layout.staff')

@section('title', 'Reservations')

@section('page-actions')
    <a href="{{ url('/search-room') }}" class="btn btn-primary">Book a room</a>
@endsection

@section('page')
    <form action="{{ url('/reservations') }}" method="GET" role="search" class="card grid gap-4 p-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
        <div>
            <label for="status" class="field-label">Status</label>
            <select id="status" name="status" class="field-input">
                <option value="">All statuses</option>
                @foreach ($statuses as $value)
                    <option value="{{ $value }}" @selected($status === $value)>{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="guest" class="field-label">Guest</label>
            <input type="search" id="guest" name="guest" value="{{ $filters['guest'] }}" placeholder="Name or email" class="field-input">
        </div>
        <div>
            <label for="room" class="field-label">Room</label>
            <input type="search" id="room" name="room" value="{{ $filters['room'] }}" placeholder="Room number" class="field-input">
        </div>
        <div>
            <label for="date" class="field-label">Staying on</label>
            <input type="date" id="date" name="date" value="{{ $filters['date'] }}" class="field-input">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if ($status || array_filter($filters))
                <a href="{{ url('/reservations') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <div class="card relative mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-porcelain text-xs tracking-wider text-muted uppercase">
                <tr>
                    <th scope="col" class="px-5 py-3 font-semibold">ID</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Room</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Guest</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Stay</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                    <th scope="col" class="px-5 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($reservations as $reservation)
                    <tr class="hover:bg-lobby-50">
                        <td class="px-5 py-3 text-muted tabular-nums">#{{ $reservation->id }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="font-display text-lg font-bold text-lobby-900">{{ $reservation->room_number }}</span>
                            <span class="ml-1 text-muted capitalize">{{ $reservation->room_type }}</span>
                        </td>
                        <td class="px-5 py-3 font-medium">{{ $reservation->guest_name }}</td>
                        <td class="px-5 py-3 whitespace-nowrap text-muted tabular-nums">
                            {{ \Illuminate\Support\Carbon::parse($reservation->check_in_date)->format('M j') }}
                            to {{ \Illuminate\Support\Carbon::parse($reservation->check_out_date)->format('M j, Y') }}
                        </td>
                        <td class="px-5 py-3">@include('components.status-badge', ['status' => $reservation->status])</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ url('/reservations/details/' . $reservation->id) }}" class="font-semibold text-lobby-700 hover:underline">
                                View<span class="sr-only"> reservation {{ $reservation->id }}</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-muted">
                            {{ $status || array_filter($filters) ? 'No reservations match these filters.' : 'No reservations yet. Bookings from the guest site appear here.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reservations->links() }}</div>
@endsection
