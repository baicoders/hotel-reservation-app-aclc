@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="grid grid-cols-2 gap-4 grid-rows-2 h-full basis-full">
                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold">
                    Payments: 00
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold">
                    Reservations: 00
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold">
                    Guests: 00
                </div>
                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold">
                    Rooms: 00
                </div>
            </div>
        </div>
        <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Dashboard page loaded');
    </script>
@endsection
