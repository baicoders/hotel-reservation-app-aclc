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
                    Guest To Reserve
                    <br>
                    {{ $guest->name }} - {{ $guest->email }} - {{ $guest->phone }}
                </div>
                <form action="{{ url('/guest/rooms/submit-all-details') }}" method="POST" id="paymentDetailsForm"
                    class="bg-white rounded-b-xl p-4 grid grid-cols-2 gap-2 basis-auto">
                    @csrf
                    <h2 class="text-xl font-bold col-span-2">Payment Details</h2>

                    {{-- room & guest details --}}
                    <div class="hidden">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="name" value="{{ $guest->name }}">
                        <input type="hidden" name="email" value="{{ $guest->email }}">
                        <input type="hidden" name="phone" value="{{ $guest->phone }}">
                        <input type="hidden" name="address" value="{{ $guest->address }}">
                    </div>

                    {{-- <div>
                        <label for="card_number" class="block font-semibold">Card Number</label>
                        <input type="text" name="card_number" id="card_number"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div>
                        <label for="card_name" class="block font-semibold">Name on Card</label>
                        <input type="text" name="card_name" id="card_name"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div>
                        <label for="expiry" class="block font-semibold">Expiry Date</label>
                        <input type="text" name="expiry" id="expiry"
                            class="w-full p-2 border border-gray-300 rounded-xl" placeholder="MM/YY" required>
                    </div>

                    <div>
                        <label for="cvv" class="block font-semibold">CVV</label>
                        <input type="text" name="cvv" id="cvv"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div> --}}

                    <div>
                        <label for="amount" class="block font-semibold">Amount</label>
                        <input type="number" name="amount" id="amount"
                            class="w-full p-2 border border-gray-300 rounded-xl" min="0" step="0.01" required>
                    </div>

                    <div>
                        <label for="payment_date" class="block font-semibold">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                    </div>

                    <div class="col-span-2">
                        <label for="payment_method" class="block font-semibold">Payment Method</label>
                        <select name="payment_method" id="payment_method"
                            class="w-full p-2 border border-gray-300 rounded-xl" required>
                            <option value="">Select Method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- footer --}}
            <div class="flex flex-row gap-4 basis-auto">
                {{-- <a href="{{ url('/guest/rooms/guest-details/') }}"
                    class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Back
                </a> --}}
                <button type="submit" form="paymentDetailsForm" class="w-full bg-gray-100 hover:bg-gray-200 rounded-xl p-4">
                    Submit
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
        console.log('Guest Payment Details page loaded');
    </script>
@endsection

{{-- <div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
</div> --}}
