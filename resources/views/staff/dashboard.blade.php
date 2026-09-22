@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="grid grid-cols-2 gap-4 grid-rows-2 h-full basis-full">
                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold">
                    Latest Activities
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex flex-col gap-2 text-2xl font-bold overflow-auto">
                    Reservations: {{ $latestReservations->count() }}
                    <div class="text-gray-500">
                        <ul class="list-disc ml-4 text-base">
                            @foreach($latestReservations as $reservation)
                                <li>
                                    {{ $reservation->guest_name }} - Room {{ $reservation->room_id }} ({{ $reservation->check_in_date }} to {{ $reservation->check_out_date }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex  flex-col gap-2 text-2xl font-bold overflow-auto">
                    Guests: {{ $latestGuests->count() }}
                    <div class="text-gray-500">
                        <ul class="list-disc ml-4 text-base">
                            @foreach($latestGuests as $guest)
                                <li>
                                    {{ $guest->full_name }} ({{ $guest->email }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex flex-col gap-2 text-2xl font-bold overflow-auto">
                    Rooms: {{ $latestRooms->count() }}
                    <div class="text-gray-500">
                        <ul class="list-disc ml-4 text-base">
                            @foreach($latestRooms as $room)
                                <li>
                                    Room {{ $room->rm_number }} - {{ $room->rm_type }} ({{ $room->status }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Dashboard page loaded');
    </script>
@endsection
