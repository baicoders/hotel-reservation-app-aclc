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
                    Room Number: 00<br>
                    Room Type: Test<br>
                    Room Price: 000.00<br>
                    Status: Status<br>
                </div>
            </div>

            {{-- footer --}}
            <div class="flex flex-row gap-4 basis-auto">
                <a href="{{ url('reserve-room') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-auto whitespace-nowrap">
                    Back
                </a>
                <a href="{{ url('/book-room') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Book Now
                </a>
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
