@extends('layout.app')

@section('title', 'Welcome')

@section('content')
    <div class="flex min-h-screen flex-col bg-lobby-950 bg-cover bg-center text-white"
        style="background-image: linear-gradient(rgb(8 36 35 / 0.72), rgb(8 36 35 / 0.9)), url('{{ asset('aclc_building_img.jpg') }}');">
        <header class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-8">
            <a href="{{ url('/home') }}" class="font-display text-2xl font-extrabold tracking-wide text-brass-400 uppercase">ACLC</a>
        </header>

        <main class="mx-auto flex w-full max-w-6xl flex-1 flex-col justify-center px-4 py-16 sm:px-8">
            <h1 class="max-w-3xl text-5xl leading-none sm:text-7xl">@include('components.title')</h1>
            <p class="mt-6 max-w-xl text-lg text-lobby-100">
                Browse the available rooms, book one in a few steps and get your receipt straight away.
                Front desk staff manage rooms, guests, reservations and payments from the same system.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ url('/search-room') }}" class="btn bg-brass-400 px-6 py-3 text-base text-lobby-950 hover:bg-brass-100">Reserve a room</a>
                <a href="{{ url('/dashboard') }}" class="btn border border-lobby-100/40 px-6 py-3 text-base text-white hover:bg-white/10">Manage</a>
            </div>
        </main>

        <footer class="mx-auto w-full max-w-6xl px-4 py-6 text-sm text-lobby-100/80 sm:px-8">
            &copy; {{ date('Y') }} ACLC Hotel Reservation System. Developed by Programmers Guild.
            @if (config('app.version'))
                <span class="ml-2">Version {{ config('app.version') }}</span>
            @endif
        </footer>
    </div>
@endsection
