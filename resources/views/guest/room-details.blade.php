@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 ">
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
            <div class="basis-full flex flex-col h-full">
                <div class="bg-gray-200 rounded-t-xl p-4 h-full basis-full">
                    Img
                </div>
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
