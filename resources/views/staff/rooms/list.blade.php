@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 flex flex-row gap-3 items-center basis-auto">
                    <h2 class="text-lg font-bold">Rooms</h2>
                    {{-- search --}}
                    <form action="{{ url('/rooms/search') }}" method="GET" class="basis-full flex flex-row w-full gap-3">
                        <input type="text" name="query" placeholder="Search rooms..."
                            class="border border-gray-300 rounded-xl px-4 py-2 w-full">
                        <button type="submit"
                            class="bg-gray-400 text-white rounded-xl px-4 py-2 hover:bg-gray-600">Search</button>
                    </form>
                </div>

                <div class="bg-white rounded-xl p-4 h-full basis-full overflow-auto">
                    <div class="grid grid-cols-2 mg:grid-cols-4 gap-4">
                        <a href="{{ url('/rooms/create') }}"
                            class="bg-gray-200 rounded-xl p-4 flex flex-col gap-1 col-span-2 hover:bg-gray-300 font-bold">
                            Add New Room +
                        </a>

                        {{-- room list --}}
                        @forelse ($rooms as $room)
                            @include('components.room-cards', [
                                'roomNumber' => $room->rm_number,
                                'roomType' => $room->rm_type,
                                'roomPrice' => $room->rm_price_per_night,
                                'status' => $room->status,
                                'room_id' => $room->id,
                                'photo_path' => $room->firstPhoto->photo_path ?? '',
                            ])
                        @empty
                            <div class="flex justify-center items-center col-span-full text-gray-500 text-2xl">
                                No rooms
                            </div>
                        @endforelse
                        
                        {{-- @for ($i = 0; $i <= 10; $i++)
                            @include('components.room-cards', [
                                'roomNumber' => '0' . $i,
                                'roomType' => 'Single',
                                'roomPrice' => '100.00',
                                'status' => 'Available',
                            ])
                        @endfor --}}
                    </div>
                    {{-- List of Rooms: 00 --}}
                </div>

                <div class="bg-white rounded-xl p-4 basis-auto">
                    Pagination
                </div>
            </div>
        </div>
        <!-- We must ship. - Taylor Otwell -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Rooms page loaded');
    </script>
@endsection
