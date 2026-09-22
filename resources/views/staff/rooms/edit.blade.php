@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Edit Room Info
                </div>

                <div class="basis-full flex flex-col h-full">
                    <div class="bg-gray-200 rounded-t-xl p-4 h-full basis-full">
                        Img
                    </div>

                    <form action="{{ url('/rooms/update/' . $room->id) }}" id="editRoomForm" method="post" class="bg-white rounded-b-xl p-4 basis-auto">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col gap-1">
                                <label for="roomNumber" class="font-bold">Room Number</label>
                                <input type="text" id="roomNumber" value="{{ $room->rm_number }}"
                                    readonly
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="roomType" class="font-bold">Room Type</label>
                                <input type="text" id="roomType" name="roomType" value="{{ $room->rm_type }}"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="roomPrice" class="font-bold">Room Price</label>
                                <input type="text" id="roomPrice" name="roomPrice" value="{{ $room->rm_price_per_night }}"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="status" class="font-bold">Status</label>
                                <select id="status" name="status" class="border border-gray-300 rounded-xl p-2 w-full">
                                    <option value="available" {{ strtolower($room->status) === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="unavailable" {{ strtolower($room->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    <option value="maintenance" {{ strtolower($room->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="flex flex-row gap-4 basis-auto">
                    <button type="submit" form="editRoomForm" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full text-center">
                        Update Room
                    </button>
                    {{-- <a href="{{ url('/rooms/update') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                        Update Room
                    </a> --}}
                </div>
            </div>
        </div>
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Reservations Details page loaded');
    </script>
@endsection

{{-- <div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
</div> --}}
