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

                <div class="bg-white rounded-xl p-4 h-full basis-full">
                    Reservation ID: 00<br>
                    Guest: Test<br>
                    Check in: 99/00/000<br>
                    Check out: 99/00/000<br>
                    Status: Status<br>
                    Room Number: 00<br>
                    Room Type: Test<br>
                    Room Price: 000.00<br>
                </div>

                <div class="bg-white rounded-xl p-4 basis-auto">
                    Buttons
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
