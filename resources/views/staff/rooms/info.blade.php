@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Room Info
                </div>

                <div class="basis-full flex flex-row gap-4 h-full">
                    <div class="basis-full flex flex-col h-full">
                        {{-- Carousel --}}
                        <div class="relative w-full overflow-hidden basis-auto rounded-t-xl h-64">
                            <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth rounded-t-xl h-full">
                                @if ($room->images && count($room->images))
                                    @foreach ($room->images as $image)
                                        <!-- Carousel Item 1 -->
                                        <div class="shrink-0 w-full h-full snap-start">
                                            <div class="bg-gray-200 h-full flex items-center justify-center text-xl">
                                                <img src="{{ asset('storage/' . $image->photo_path) }}"
                                                    class="block w-full h-full object-cover" alt="..." />
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="bg-gray-200 h-full flex items-center justify-center text-xl text-gray-500 w-full">
                                        No images uploaded for this room.
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- Room Details --}}
                        <div class="bg-white rounded-b-xl p-4 basis-auto">
                            Room Number: {{ $room->rm_number }}<br>
                            Room Type: {{ $room->rm_type }}<br>
                            Room Price: {{ $room->rm_price_per_night }}<br>
                            Status: {{ $room->status }}<br>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-4 basis-full h-full">
                        Reservations List
                        <div class="overflow-auto h-full mt-4">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="py-2 px-4 bg-gray-300 rounded-s-xl">ID</th>
                                        <th class="py-2 px-4 bg-gray-300">Guest Name</th>
                                        <th class="py-2 px-4 bg-gray-300">In Date</th>
                                        <th class="py-2 px-4 bg-gray-300">Out Date</th>
                                        <th class="py-2 px-4 bg-gray-300 rounded-e-xl">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservations as $reservation)
                                        <tr class="hover:bg-gray-100">
                                            <td class="py-2 px-4 border-b border-gray-300">{{ $reservation->id }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300">
                                                {{ $reservation->guest_name }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300">
                                                {{ $reservation->check_in_date }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300">
                                                {{ $reservation->check_out_date }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300">{{ $reservation->status }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row gap-4 basis-auto">
                    <form action="{{ url('/rooms/delete/' . $room->id) }}" id="room_delete_form" method="POST"
                        onsubmit="return handleFormSubmission(event,'Are you sure you want to delete this Room?','Alert!','room_delete_form');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-gray-100 border-red-400 border hover:bg-gray-200 rounded-xl p-4 basis-auto whitespace-nowrap">Delete
                            Room</button>
                    </form>

                    <a href="{{ url('/rooms/edit/' . $room->id) }}"
                        class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                        Edit Room
                    </a>
                </div>
            </div>
        </div>
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Room Info page loaded');
    </script>
@endsection

{{-- <div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div> --}}
