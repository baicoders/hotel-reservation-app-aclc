@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 ">
        <div class="container p-4 mx-auto flex flex-col gap-4 max-w-[800px]">
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
                    Room To Reserve
                    <br>
                    {{ $room->rm_number }} - {{ $room->rm_type }} - ${{ number_format($room->rm_price_per_night, 2) }} per night
                </div>
                <form action="{{ url('/guest/rooms/booking-room') }}" method="POST" id="guestDetailsForm"
                    class="bg-white rounded-b-xl p-4 grid grid-cols-2 gap-2 basis-auto">
                    @csrf
                    <h2 class="text-xl font-bold col-span-2">Details</h2>

                    {{-- guest details --}}
                    <div class="hidden">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                    </div>

                    <div>
                        <label for="check_in_date" class="block font-semibold">Check in</label>
                        <input type="date" name="check_in_date" id="check_in_date" min="{{ date('Y-m-d') }}"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ old('check_in_date', date('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="check_out_date" class="block font-semibold">Check out</label>
                        <input type="date" name="check_out_date" id="check_out_date" min="{{ now()->addDay()->format('Y-m-d') }}"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ old('check_out_date', now()->addDay()->format('Y-m-d')) }}" required>
                    </div>

                    <div>
                        <label for="name" class="block font-semibold">Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="email" class="block font-semibold">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="phone" class="block font-semibold">Phone</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ old('phone') }}" required>
                    </div>

                    <div>
                        <label for="address" class="block font-semibold">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                            class="w-full p-2 border border-gray-300 rounded-xl">
                    </div>

                    {{-- payment details --}}
                    <div>
                        <label for="amount" class="block font-semibold">Amount (nightly rate times nights)</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $room->rm_price_per_night) }}"
                            class="w-full p-2 border border-gray-300 rounded-xl" min="0" step="0.01" required>
                    </div>

                    <div>
                        <label for="payment_date" class="block font-semibold">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" max="{{ date('Y-m-d') }}"
                            class="w-full p-2 border border-gray-300 rounded-xl" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-span-2">
                        <label for="payment_method" class="block font-semibold">Payment Method</label>
                        <select name="payment_method" id="payment_method" value="{{ old('payment_method') }}"
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
                <a href="{{ url('/guest/rooms/details/' . $room->id) }}"
                    class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                    Back
                </a>
                <button type="submit" form="guestDetailsForm" class="w-full bg-gray-100 hover:bg-gray-200 rounded-xl p-4">
                    Submit
                </button>

            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Reserve page loaded');
    </script>
@endsection
{{-- <div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
</div> --}}
