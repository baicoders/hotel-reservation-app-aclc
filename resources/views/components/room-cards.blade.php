@props(['roomNumber' => '010', 'roomType' => 'Single', 'roomPrice' => '0.00', 'status' => 'Available'])

<div class="shadow-md rounded-xl">
    <div class="bg-gray-300 rounded-t-xl p-4 min-h-[250px]">Img</div>
    <div class="bg-gray-200 rounded-b-xl p-4">
        <h1 class="font-bold text-2xl">{{ $roomNumber }}</h1>
        <p class="font-bold">{{ $roomType }}</p>
        <p>Room Price per night: {{ $roomPrice }}</p>
        <p>Status: {{ $status }}</p>
    </div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
