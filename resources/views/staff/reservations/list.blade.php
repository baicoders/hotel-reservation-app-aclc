@extends('layout.staff')

@section('title', 'Reservations')

@section('page-actions')
    <a href="{{ url('/search-room') }}" class="btn btn-primary">Book a room</a>
@endsection

@section('page')
    <nav aria-label="Filter by status" class="flex flex-wrap gap-2">
        @foreach (array_merge([null], $statuses) as $value)
            <a href="{{ url('/reservations') . ($value ? '?status=' . $value : '') }}"{!! $status === $value ? ' aria-current="page"' : '' !!}
                class="rounded-full border px-3 py-1.5 text-sm font-semibold {{ $status === $value ? 'border-lobby-900 bg-lobby-900 text-white' : 'border-line bg-white hover:bg-lobby-50' }}">
                {{ $value ? ucfirst(str_replace('_', ' ', $value)) : 'All' }}
            </a>
        @endforeach
    </nav>

    <div class="card relative mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-porcelain text-xs tracking-wider text-muted uppercase">
                <tr>
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
                        <td colspan="5" class="px-5 py-10 text-center text-muted">
                            {{ $status ? 'No reservations with this status.' : 'No reservations yet. Bookings from the guest site appear here.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reservations->links() }}</div>
@endsection
