@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen">
        <div class="container p-4 mx-auto flex flex-col gap-4 h-full">
            {{-- top bar --}}
            <div class="bg-white rounded-xl p-4 basis-auto">
                <a href="{{ url('/home') }}" class="flex justify-center items-center gap-4">
                    <h1 class="text-2xl font-bold">
                        Hotel Reservation System!
                    </h1>
                </a>
            </div>

            {{-- room details --}}
            <div class="basis-full">
                <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth rounded-t-xl h-64">
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
                {{-- <div class="bg-gray-200 rounded-t-xl p-4 h-full basis-full">
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ($photos as $photo)
                            <div class="bg-white rounded-xl overflow-hidden">
                                <img src="{{ asset('storage/room_photos/' . $photo->photo_path) }}" alt="Room Photo"
                                    class="w-full h-48 object-cover">
                            </div>
                        @endforeach
                    </div>
                </div> --}}
                <div class="bg-white rounded-b-xl p-4 basis-auto">
                    Room Number: {{ $room->rm_number }}<br>
                    Room Type: {{ $room->rm_type }}<br>
                    Room Price: {{ $room->rm_price_per_night }}<br>
                    Status: {{ $room->status }}<br>
                </div>
            </div>

            {{-- footer --}}
            <div class="flex flex-row gap-4 basis-auto">
                <a href="{{ url('search-room') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Back
                </a>
                <form action="{{ url('/guest/rooms/reserve') }}" method="GET" class="basis-full">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 rounded-xl p-4">
                        Book Now
                    </button>
                </form>
                {{-- <a href="{{ url('guest/rooms/guest-details/') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Book Now
                </a> --}}
            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Guest Room Details page loaded');
    </script>
@endsection


{{-- <div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
</div> --}}
