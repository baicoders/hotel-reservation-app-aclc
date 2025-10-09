@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Reservations Info
                </div>

                <div class="basis-full flex flex-col h-full">
                    <div class="bg-gray-200 rounded-t-xl p-4 basis-full">
                        Reservation ID: {{ $reservation->id }}<br>
                        Guest: {{ $guest->full_name }}<br>
                        Check in: {{ $reservation->check_in_date }}<br>
                        Check out: {{ $reservation->check_out_date }}<br>
                        Status: {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}<br>
                        
                    </div>
                    <div class="bg-white rounded-b-xl p-4 basis-auto">
                        Room Number: {{ $room->rm_number }}<br>
                        Room Type: {{ $room->rm_type }}<br>
                        Room Price: ${{ number_format($room->rm_price_per_night, 2) }}<br>
                    </div>
                </div>

                <div class="flex flex-row gap-4 basis-auto">
                    <form action="{{ url('/reservations/update-status/' . $reservation->id) }}" id="reservation_status_form" method="POST" class="flex flex-row gap-4 w-full"
                        onsubmit="return handleFormSubmission(event,'Are you sure you want to update the status of this reservation?','Alert!','reservation_status_form');">
                        @csrf
                        <select name="status" class="bg-gray-100 rounded-xl px-4 basis-full">
                            <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="checked_in" {{ $reservation->status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                            <option value="checked_out" {{ $reservation->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                            <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit"
                            class="bg-gray-100 border-blue-400 border hover:bg-gray-200 rounded-xl p-4 basis-full whitespace-nowrap">Update Status</button>
                    </form>

                    {{-- <a href="{{ url('/bookings/create?guest_id=' . $guest->id) }}"
                        class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                        Checkout
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
