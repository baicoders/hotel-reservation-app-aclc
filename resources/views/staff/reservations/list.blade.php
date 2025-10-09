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

                <div class="bg-white rounded-xl p-4 h-full basis-full overflow-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-300 rounded-s-xl">ID</th>
                                <th class="px-4 py-2 bg-gray-300">Guest</th>
                                <th class="px-4 py-2 bg-gray-300">Room</th>
                                {{-- <th class="px-4 py-2 bg-gray-300">Check in</th>
                                <th class="px-4 py-2 bg-gray-300">Check out</th> --}}
                                <th class="px-4 py-2 bg-gray-300">Status</th>
                                <th class="px-4 py-2 bg-gray-300 rounded-e-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservations as $reservation)
                                <tr class="hover:bg-gray-300 border-b border-gray-200">
                                    <td class="px-4 py-2">{{ $reservation->id }}</td>
                                    <td class="px-4 py-2 truncate">{{ $reservation->guest_name }}</td>
                                    <td class="px-4 py-2 text-center">{{ $reservation->room_number }} -
                                        {{ $reservation->room_type }}</td>
                                    {{-- <td class="px-4 py-2 text-center">{{ $reservation->check_in_date }}</td>
                                    <td class="px-4 py-2 text-center">{{ $reservation->check_out_date }}</td> --}}
                                    <td class="px-4 py-2 text-center">{{ $reservation->status }}</td>
                                    <td class="px-4 py-2 w-1/6 text-center">
                                        <a href="{{ url('/reservations/details/' . $reservation->id) }}"
                                            class="text-gray-500 hover:underline">Show Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center">No reservations found.</td>
                                </tr>
                            @endforelse
                            
                            {{-- @for ($i = 0; $i < 20; $i++)
                                <tr class="hover:bg-gray-300 border-b border-gray-200">
                                    <td class="px-4 py-2">{{ $i }}</td>
                                    <td class="px-4 py-2 text-center truncate">Test</td>
                                    <td class="px-4 py-2 text-center">99/00/000</td>
                                    <td class="px-4 py-2 text-center">99/00/000</td>
                                    <td class="px-4 py-2 text-center">Status</td>
                                    <td class="px-4 py-2 w-1/6 text-center">
                                        <a href="{{ url('/reservations/details/') }}"
                                            class="text-gray-500 hover:underline">Show Details</a>
                                    </td>
                                </tr>
                            @endfor --}}
                        </tbody>
                    </table>
                    {{-- List of Reservations: 00 --}}
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
