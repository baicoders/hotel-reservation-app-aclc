@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Reservations
                </div>

                <div class="bg-white rounded-xl p-4 h-full flex justify-center items-center text-2xl font-bold basis-full">
                    List of Reservations: 00
                </div>

                <div class="bg-white rounded-xl p-4 basis-auto">
                    Pagination
                </div>
            </div>
        </div>
        <!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Reservations page loaded');
    </script>
@endsection
