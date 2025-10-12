@extends('layout.app')

@section('content')
    <div class=" bg-gray-300 h-screen p-4">
        <div class="flex flex-row gap-4 h-full">
            {{-- sidebar --}}
            @include('components.sidebar')

            {{-- content --}}
            <div class="flex flex-col gap-4 h-full basis-full">
                <div class="bg-white rounded-xl p-4 basis-auto">
                    Create New Room
                </div>

                <div class="basis-full flex flex-col h-full">
                    <div class="bg-gray-200 rounded-t-xl p-4 h-full basis-full">
                        Img
                    </div>

                    <form action="{{ url('/rooms/record') }}" id="createRoomForm" method="post" class="bg-white rounded-b-xl p-4 basis-auto" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            {{-- input for adding room photos --}}
                            <div class="flex flex-col gap-1 col-span-full">
                                <label for="roomPhotos" class="font-bold">Room Photos</label>
                                <input type="file" id="roomPhotos" name="roomPhotos[]" multiple accept="image/*"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="roomNumber" class="font-bold">Room Number</label>
                                <input type="number" id="roomNumber" name="roomNumber" value="00"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="roomType" class="font-bold">Room Type</label>
                                <input type="text" id="roomType" name="roomType" value="Test"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="roomPrice" class="font-bold">Room Price</label>
                                <input type="number" id="roomPrice" name="roomPrice" value="000.00"
                                    class="border border-gray-300 rounded-xl p-2 w-full" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="status" class="font-bold">Status</label>
                                <select id="status" name="status" class="border border-gray-300 rounded-xl p-2 w-full">
                                    <option value="Available" selected>Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="flex flex-row gap-4 basis-auto">
                    <button type="submit" form="createRoomForm" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full text-center">
                        Add New Room
                    </button>
                    {{-- <a href="{{ url('/rooms/store') }}" class="bg-gray-100 hover:bg-gray-200 rounded-xl p-4 basis-full text-center">
                        Add New Room
                    </a> --}}
                </div>
            </div>
        </div>
        <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
    </div>
@endsection

@section('scripts')
    <script>
        // document.querySelector('a[href="{{ url('/rooms/store') }}"]').addEventListener('click', function(event) {
        //     event.preventDefault(); // Prevent the default anchor behavior
        //     document.getElementById('createRoomForm').submit(); // Submit the form
        // });
    </script>
@endsection

{{-- <div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
</div> --}}
