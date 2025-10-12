@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 ">
        <div class="container p-4 mx-auto flex flex-col gap-4 h-auto">
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
                <div class="bg-gray-200 rounded-t-xl p-4 min-h-[200px] basis-full">
                    Room To Reserve IMG
                </div>
                <div class="bg-white rounded-b-xl p-4 basis-auto">
                    <h2 class="text-xl font-bold col-span-2">Receipt</h2>
                    <p>Thank you, {{ $guest->name }}, for your reservation!</p>
                    <p>A confirmation email has been sent to {{ $guest->email }}.</p>
                    <p class="text-gray-500">Note! the email confirmation is in development it will not send a confirmation email.</p>
                    <br>
                    <h3 class="text-lg font-semibold">Reservation Details:</h3>
                    <p>Room Number: {{ $room->rm_number }}</p>
                    <p>Room Type: {{ $room->rm_type }}</p>
                    <p>Price per Night: ${{ $room->rm_price_per_night }}</p>
                    <br>
                    <h3 class="text-lg font-semibold">Guest Details:</h3>
                    <p>Name: {{ $guest->name }}</p>
                    <p>Email: {{ $guest->email }}</p>
                    <p>Phone: {{ $guest->phone }}</p>
                    <p>Address: {{ $guest->address }}</p>
                    <br>
                    <h3 class="text-lg font-semibold">Payment Details:</h3>
                    <p>Amount: ${{ $payment->amount }}</p>
                    <p>Change: ${{ $payment->amount - $room->rm_price_per_night }}</p>
                    <p>Payment Method: {{ $payment->payment_method }}</p>
                    <p>Payment Date: {{ $payment->payment_date }}</p>
                </div>
            </div>
            {{-- footer --}}
            <div class="flex flex-row gap-4 basis-auto">
                <a href="{{ url('/search-room') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Procceed to Search
                </a>
            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Receipt page loaded');
    </script>
@endsection

{{-- <div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div> --}}
