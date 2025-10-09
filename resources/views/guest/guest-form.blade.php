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

            {{-- guests details --}}
            <div class="basis-full flex flex-col h-full">
                <div class="bg-gray-200 rounded-t-xl p-4 h-full basis-full">
                    Room To Reserve
                    <br>
                    {{ $room->rm_number }} - {{ $room->rm_type }} - ${{ $room->rm_price_per_night }} per night
                </div>
                <form action="{{ url('/guest/rooms/payment-details') }}" method="POST" id="guestDetailsForm"
                    class="bg-white rounded-b-xl p-4 grid grid-cols-2 gap-2 basis-auto">
                    @csrf
                    <h2 class="text-xl font-bold col-span-2">Guest Details</h2>

                    {{-- room details --}}
                    <div class="hidden">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                    </div>

                    <div>
                        <label for="name" class="block font-semibold">Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div>
                        <label for="email" class="block font-semibold">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div>
                        <label for="phone" class="block font-semibold">Phone</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div>
                        <label for="address" class="block font-semibold">Address</label>
                        <input type="text" name="address" id="address"
                            class="w-full p-2 border border-gray-300 rounded-xl">
                    </div>
                </form>
            </div>

            {{-- footer --}}
            <div class="flex flex-row gap-4 basis-auto">
                {{-- <a href="{{ url('/guest/rooms/details/1') }}"
                    class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Back
                </a> --}}
                <button type="submit" form="guestDetailsForm" class="w-full bg-gray-100 hover:bg-gray-200 rounded-xl p-4">
                    Next
                </button>
                {{-- <a href="{{ url('/guest/rooms/payment-details') }}"
                    class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Next
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
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
</div> --}}
