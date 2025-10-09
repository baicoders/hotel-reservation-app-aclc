@extends('layout.app')

@section('content')
    <div class="flex flex-col bg-gray-300 ">

        <div class="h-screen p-4">
            <div class="flex flex-row gap-4 h-full w-full">
                <div class="bg-white rounded-xl p-4 h-full basis-full flex justify-center items-center">
                    <h1 class="text-2xl font-bold text-center">
                        Welcome to the Hotel Reservation System!
                    </h1>
                </div>
                <div class="flex flex-col gap-4 full">
                    <a href="{{ url('/search-room') }}" class="bg-white rounded-xl p-4 h-full basis-full text-2xl font-bold text-center flex justify-center items-center">
                        Reserve Room
                    </a>
                    <a href="{{ url('/dashboard') }}" class="bg-white rounded-xl p-4 h-full basis-full text-2xl font-bold text-center flex justify-center items-center">
                        Manage
                    </a>
                </div>
            </div>

        </div>
        <div class="p-4">
            <div class="bg-white rounded-xl p-4">
                <p class="text-gray-700">
                    This is the home page of the Hotel Reservation System. Use the navigation links above to manage rooms,
                    guests, reservations, and payments.
                </p>
            </div>
        </div>
        <!-- Well begun is half done. - Aristotle -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Home page loaded');
    </script>
@endsection
