@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 ">
        <div class="container p-4 mx-auto flex flex-col gap-4">
            {{-- top bar --}}
            <div class="bg-white rounded-xl p-4 h-full basis-full">
                <a href="{{ url('/home') }}" class="flex justify-center items-center gap-4">
                    <h1 class="text-2xl font-bold">
                        Hotel Reservation System!
                    </h1>
                </a>
            </div>

            <div class="bg-white rounded-xl p-4 h-full basis-full grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- room list --}}
                @for ($i = 0; $i <= 10; $i++)
                    @include('components.room-cards', [
                        'roomNumber' => '0' . $i,
                        'roomType' => 'Single',
                        'roomPrice' => '100.00',
                        'status' => 'Available',
                    ])
                @endfor
            </div>

            {{-- footer --}}
            <div class="bg-white rounded-xl p-4 h-full basis-full">
                Hotel Reservation System!
            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Search Rooms page loaded');
    </script>
@endsection
