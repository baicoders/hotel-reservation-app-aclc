@extends('layout.app')

@section('content')
    <div class=" bg-cover bg-center bg-blend-multiply bg-gray-500 bg-fixed" style="background-image: url('{{ asset('aclc_building_img.jpg') }}');">
        <div class="container max-w-[800px] mx-auto flex flex-col">
            {{-- hero --}}
            <div class="h-screen p-4 ">
                <div class="flex flex-col gap-4 h-full w-full">
                    <div class="p-4 h-full basis-full flex justify-center items-center relative">
                        <h1 class="text-2xl text-white text-shadow-2xs font-bold text-center bottom-4 right-4 absolute">
                            Welcome to the Hotel Reservation System!
                        </h1>
                    </div>
                    <div class="flex flex-row gap-4 full">
                        <a href="{{ url('/search-room') }}"
                            class="bg-white rounded-xl p-4 h-full basis-full text-2xl font-bold text-center flex justify-center items-center hover:bg-gray-200 transition">
                            Reserve Room
                        </a>
                        <a href="{{ url('/dashboard') }}"
                            class="bg-white rounded-xl p-4 h-full basis-full text-2xl font-bold text-center flex justify-center items-center hover:bg-gray-200 transition">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

            {{-- description --}}
            <div class="p-4">
                <div class="bg-white rounded-xl p-4">
                    <p class="text-gray-700">
                        This is the home page of the Hotel Reservation System. Use the navigation links above to manage
                        rooms,
                        guests, reservations, and payments.
                    </p>
                </div>
            </div>

            {{-- footer --}}
            <div class="p-4 text-center text-gray-300">
                &copy; {{ date('Y') }} Hotel Reservation System. All rights reserved.
                <br>
                Developed by Programmers Guild.
            </div>
        <!-- Well begun is half done. - Aristotle -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Home page loaded');
    </script>
@endsection
