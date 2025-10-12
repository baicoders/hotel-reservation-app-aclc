@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Guest Info
                </div>

                <div class="basis-full flex flex-col h-full">
                    <div class="bg-gray-200 rounded-t-xl p-4 basis-auto">
                        Full Name: {{ $guest->full_name }}<br>
                        Email: {{ $guest->email }}<br>
                        Phone Number: {{ $guest->phone_number }}<br>
                    </div>
                    <div class="bg-white rounded-b-xl p-4 basis-full overflow-auto">
                        {{-- list all the booked rooms --}}
                        <div class="font-bold mb-2">Booked Rooms:</div>
                        <div class="grid grid-cols-1 max-h-[200px] gap-2">
                            @forelse ($bookings as $room)
                                <div class="bg-gray-100 rounded-xl p-4">
                                    Room Number: {{ $room->rm_number }}<br>
                                    Room Type: {{ $room->rm_type }}<br>
                                    Room Price: {{ $room->rm_price_per_night }}<br>
                                    Status: {{ $room->status }}<br>
                                    Check In Date: {{ $room->check_in_date }}<br>
                                    Check Out Date: {{ $room->check_out_date }}<br>
                                </div>
                            @empty
                                <div class="text-gray-500">No rooms booked.</div>
                            @endforelse
                        </div>
                        {{-- end of list --}}
                    </div>
                </div>

                <div class="flex flex-row gap-4 basis-auto">
                    <form action="{{ url('/guest/delete/' . $guest->id) }}" id="guest_delete_form" method="POST"
                        onsubmit="return handleFormSubmission(event,'Are you sure you want to delete this Guest?','Alert!','guest_delete_form');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-gray-100 border-red-400 border hover:bg-gray-200 rounded-xl p-4 basis-auto whitespace-nowrap">Delete
                            Guest</button>
                    </form>

                    <a href="{{ url('/bookings/create?guest_id=' . $guest->id) }}"
                        class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full">
                        Reserve Room
                    </a>
                </div>
            </div>
        </div>
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Guest Details page loaded');
    </script>
@endsection

<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
</div>
