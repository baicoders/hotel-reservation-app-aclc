{{-- alert --}}
@if (session('success'))
    <div class="bg-white rounded-xl shadow-lg fixed top-4 right-4 z-50">
        <p class="bg-green-300 p-2 px-3 rounded-t-xl flex justify-between"><strong>Success!</strong></p>
        <ul class="p-2 px-3 rounded-b-xl">
            <li>{{ session('success') }}</li>
        </ul>
    </div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
@endif
