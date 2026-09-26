@extends('layout.staff')

@section('title', 'Rooms')

@section('page-actions')
    <a href="{{ url('/rooms/create') }}" class="btn btn-primary">Add room</a>
@endsection

@section('page')
    <form action="{{ url('/rooms') }}" method="GET" role="search" class="card flex flex-col gap-4 p-4 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="query" class="field-label">Search rooms</label>
            <input type="search" id="query" name="query" value="{{ $query }}" placeholder="Room number or type"
                class="field-input">
        </div>
        <div class="sm:w-48">
            <label for="status" class="field-label">Status</label>
            <select id="status" name="status" class="field-input">
                <option value="">All statuses</option>
                @foreach (['available' => 'Available', 'unavailable' => 'Unavailable', 'maintenance' => 'Maintenance'] as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Search</button>
            @if ($query !== '' || $status)
                <a href="{{ url('/rooms') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <p class="mt-6 mb-4 text-sm text-muted">
        {{ $rooms->total() }} {{ Str::plural('room', $rooms->total()) }}
        @if ($query !== '') matching "{{ $query }}" @endif
    </p>

    @if ($rooms->isEmpty())
        <div class="card px-6 py-12 text-center">
            @if ($rooms->total() > 0)
                <p class="font-semibold">This page is past the last page of results.</p>
                <a href="{{ $rooms->url(1) }}" class="mt-2 inline-block font-semibold text-lobby-700 hover:underline">Go to page 1</a>
            @elseif ($query !== '' || $status)
                <p class="font-semibold">No rooms match these filters.</p>
                <a href="{{ url('/rooms') }}" class="mt-2 inline-block font-semibold text-lobby-700 hover:underline">Clear filters</a>
            @else
                <p class="font-semibold">No rooms yet.</p>
                <a href="{{ url('/rooms/create') }}" class="mt-2 inline-block font-semibold text-lobby-700 hover:underline">Add the first room</a>
            @endif
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($rooms as $room)
                @include('components.room-cards', [
                    'roomNumber' => $room->rm_number,
                    'roomType' => $room->rm_type,
                    'roomPrice' => $room->rm_price_per_night,
                    'status' => $room->status,
                    'room_id' => $room->id,
                    'photo_path' => $room->firstPhoto->photo_path ?? '',
                ])
            @endforeach
        </div>
    @endif

    <div class="mt-8">{{ $rooms->links() }}</div>
@endsection
