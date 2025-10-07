@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Rooms
                </div>

                <div class="bg-white rounded-xl p-4 h-full basis-full overflow-auto">
                    <div class="grid grid-cols-2 mg:grid-cols-4 gap-4">
                        <a href="{{ url('/rooms/create') }}"
                            class="bg-gray-200 rounded-xl p-4 flex flex-col gap-1 col-span-2 hover:bg-gray-300 font-bold">
                            Add New Room +
                        </a>

                        {{-- room list --}}
                        @foreach ($rooms as $room)
                            @include('components.room-cards', [
                                'roomNumber' => $room->rm_number,
                                'roomType' => $room->rm_type,
                                'roomPrice' => $room->rm_price_per_night,
                                'status' => $room->status,
                                'room_id' => $room->id
                            ])
                        @endforeach
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
