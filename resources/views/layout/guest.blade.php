@extends('layout.app')

@section('content')
    <div class="flex min-h-screen flex-col">
        <header class="bg-lobby-900 text-white print:hidden">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-8">
                <a href="{{ url('/home') }}" class="font-display text-2xl font-extrabold tracking-wide text-brass-400 uppercase">ACLC Hotel</a>
                <nav aria-label="Main" class="flex items-center gap-5 text-sm font-semibold">
                    <a href="{{ url('/search-room') }}"{!! request()->is('search-room') ? ' aria-current="page"' : '' !!} class="hover:underline">Rooms</a>
                    <a href="{{ url('/dashboard') }}" class="text-lobby-100 hover:text-white hover:underline">Staff sign in</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-8">
            @hasSection('eyebrow')
                <p class="eyebrow mb-1">@yield('eyebrow')</p>
            @endif
            <h1 class="page-title">@yield('title')</h1>
            <div class="mt-8">@yield('page')</div>
        </main>

        <footer class="border-t border-line bg-white print:hidden">
            <p class="mx-auto max-w-6xl px-4 py-6 text-sm text-muted sm:px-8">
                &copy; {{ date('Y') }} ACLC Hotel Reservation System. Developed by Programmers Guild.
            </p>
        </footer>
    </div>
@endsection
