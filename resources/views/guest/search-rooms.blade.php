@extends('layout.guest')

@section('title', 'Available rooms')
@section('eyebrow', 'Reserve a room')

@section('page')
    <form action="{{ url('/search-room') }}" method="GET" class="card flex flex-col gap-4 p-4 sm:flex-row sm:items-end">
        <div class="sm:w-56">
            <label for="type" class="field-label">Room type</label>
            <select id="type" name="type" class="field-input">
                <option value="">All types</option>
                @foreach ($types as $option)
                    <option value="{{ $option }}" @selected($type === $option)>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:w-56">
            <label for="sort" class="field-label">Sort by</label>
            <select id="sort" name="sort" class="field-input">
                <option value="">Room number</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Price, low to high</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Price, high to low</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Show rooms</button>
            @if ($type || $sort)
                <a href="{{ url('/search-room') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <p class="mt-6 mb-4 text-sm text-muted">{{ $rooms->total() }} {{ Str::plural('room', $rooms->total()) }} available</p>

    @if ($rooms->isEmpty() && $rooms->total() > 0)
        <div class="card px-6 py-12 text-center">
            <p class="font-semibold">This page is past the last page of rooms.</p>
            <a href="{{ $rooms->url(1) }}" class="mt-3 inline-block font-semibold text-lobby-700 hover:underline">Go to page 1</a>
        </div>
    @elseif ($rooms->isEmpty())
        <div class="card px-6 py-12 text-center">
            <p class="font-semibold">No rooms are available right now.</p>
            <p class="mt-1 text-muted">{{ $type ? 'Try another room type.' : 'Please check back later.' }}</p>
            <a href="{{ $type ? url('/search-room') : url('/home') }}" class="mt-3 inline-block font-semibold text-lobby-700 hover:underline">
                {{ $type ? 'Show all types' : 'Back to home' }}
            </a>
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
                    'isGuest' => true,
                    'photo_path' => $room->firstPhoto->photo_path ?? '',
                ])
            @endforeach
        </div>
    @endif

    <div class="mt-8">{{ $rooms->links() }}</div>
@endsection
