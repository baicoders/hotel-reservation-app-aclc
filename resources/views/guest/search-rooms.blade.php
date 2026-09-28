@extends('layout.guest')

@section('title', 'Available rooms')
@section('eyebrow', 'Reserve a room')

@section('page')
    @php
        $dateQuery = http_build_query(['check_in' => $checkIn, 'check_out' => $checkOut]);
        $nights = \Illuminate\Support\Carbon::parse($checkIn)->diffInDays(\Illuminate\Support\Carbon::parse($checkOut));
    @endphp
    @if ($dateError)
        <p role="alert" class="mb-4 rounded-lg bg-brick-100 p-4 text-sm font-medium text-brick-700">{{ $dateError }} Showing tonight instead.</p>
    @endif
    <form action="{{ url('/search-room') }}" method="GET" class="card flex flex-col flex-wrap gap-4 p-4 sm:flex-row sm:items-end">
        <div class="sm:w-44">
            <label for="check_in" class="field-label">Check in</label>
            <input type="date" id="check_in" name="check_in" value="{{ $checkIn }}" min="{{ today()->toDateString() }}" required class="field-input">
        </div>
        <div class="sm:w-44">
            <label for="check_out" class="field-label">Check out</label>
            <input type="date" id="check_out" name="check_out" value="{{ $checkOut }}" min="{{ today()->addDay()->toDateString() }}" required class="field-input">
        </div>
        <div class="sm:w-44">
            <label for="type" class="field-label">Room type</label>
            <select id="type" name="type" class="field-input">
                <option value="">All types</option>
                @foreach ($types as $option)
                    <option value="{{ $option }}" @selected($type === $option)>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:w-48">
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
                <a href="{{ url('/search-room') . '?' . $dateQuery }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <p class="mt-6 mb-4 text-sm text-muted">
        {{ $rooms->total() }} {{ Str::plural('room', $rooms->total()) }} free from
        {{ \Illuminate\Support\Carbon::parse($checkIn)->format('D, M j') }} to {{ \Illuminate\Support\Carbon::parse($checkOut)->format('D, M j') }}
        ({{ $nights }} {{ Str::plural('night', $nights) }})
    </p>

    @if ($rooms->isEmpty() && $rooms->total() > 0)
        <div class="card px-6 py-12 text-center">
            <p class="font-semibold">This page is past the last page of rooms.</p>
            <a href="{{ $rooms->url(1) }}" class="mt-3 inline-block font-semibold text-lobby-700 hover:underline">Go to page 1</a>
        </div>
    @elseif ($rooms->isEmpty())
        <div class="card px-6 py-12 text-center">
            <p class="font-semibold">No rooms are available right now for these dates.</p>
            <p class="mt-1 text-muted">{{ $type ? 'Try another room type.' : 'Try other dates.' }}</p>
            <a href="{{ $type ? url('/search-room') . '?' . $dateQuery : url('/home') }}" class="mt-3 inline-block font-semibold text-lobby-700 hover:underline">
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
                    'status' => 'available',
                    'room_id' => $room->id,
                    'isGuest' => true,
                    'dateQuery' => $dateQuery,
                    'photo_path' => $room->firstPhoto->photo_path ?? '',
                ])
            @endforeach
        </div>
    @endif

    <div class="mt-8">{{ $rooms->links() }}</div>
@endsection
