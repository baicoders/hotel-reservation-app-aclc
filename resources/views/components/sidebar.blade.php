<div class="flex flex-col gap-4 basis-auto w-[260px]">
    <div class="bg-white rounded-xl p-4 basis-full">
        <a href="{{ url('/home') }}">
            <h1 class="text-3xl font-bold">
                @include('components.title')
            </h1>
        </a>
    </div>
    <a href="{{ url('/dashboard') }}" class="bg-white rounded-xl p-4 basis-auto">Dashboard</a>
    <a href="{{ url('/reservations') }}" class="bg-white rounded-xl p-4 basis-auto">Reservations</a>
    <a href="{{ url('/guest') }}" class="bg-white rounded-xl p-4 basis-auto">Guests</a>
    <a href="{{ url('/rooms') }}" class="bg-white rounded-xl p-4 basis-auto">Rooms</a>

    @auth
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="bg-white rounded-xl p-4 basis-auto w-full text-left">Logout</button>
        </form>
    @else
        <a href="{{ url('/login') }}" class="bg-white rounded-xl p-4 basis-auto">Login</a>
    @endauth
    <!-- He who is contented is rich. - Laozi -->
</div>
