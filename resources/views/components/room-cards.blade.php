@props([
    'roomNumber' => '010',
    'roomType' => 'Single',
    'roomPrice' => '0.00',
    'status' => 'Available',
    'isGuest' => false,
    'room_id',
])

<div class="shadow-md rounded-xl">

    @if ($photo_path)
        <img src="{{ asset('storage/' . ($photo_path ?? 'no-image.png')) }}" alt="Room Image"
            class="w-full h-[250px] object-cover rounded-t-xl">
    @else
        <div class="bg-gray-300 rounded-t-xl p-4 flex justify-center items-center h-[250px]">
            No Image
        </div>
    @endif

    <div class="bg-gray-200 rounded-b-xl p-4 flex flex-col gap-1">
        <h1 class="font-bold text-2xl">{{ $roomNumber }}</h1>
        <p class="font-bold">{{ $roomType }}</p>
        <p>Room Price per night: {{ $roomPrice }}</p>
        <p>Status: {{ $status }}</p>

        @if ($isGuest)
            <a href="{{ url('/guest/rooms/details/' . $room_id) }}" class="bg-white rounded-xl p-3">View Details</a>
        @else
            <a href="{{ url('/rooms/details/' . $room_id) }}" class="bg-white rounded-xl p-3">View Info</a>
        @endif
        {{-- @guest
            <a href="{{ url('/guest/rooms/details') }}" class="bg-white rounded-xl p-3">View Details</a>
        @else
            <a href="{{ url('/rooms/details') }}" class="bg-white rounded-xl p-3">View Info</a>
        @endguest --}}
    </div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
