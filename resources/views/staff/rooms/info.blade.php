@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Room Info
                </div>

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

                <div class="flex flex-row gap-4 basis-auto">
                    <form action="{{ url('/rooms/delete/' . $room->id) }}" method="POST"
                        onsubmit="return handleFormSubmission(event,'Are you sure you want to delete this Room?','Alert!','book_delete_form');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-gray-100 border-red-400 border hover:bg-gray-200 rounded-xl p-4 basis-auto whitespace-nowrap">Delete
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
        console.log('Reservations Details page loaded');
    </script>
@endsection

{{-- <div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div> --}}
