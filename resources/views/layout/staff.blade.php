@extends('layout.app')

@section('content')
    <div class="min-h-screen lg:pl-64">
        <div class="flex items-center justify-between bg-lobby-900 px-4 py-3 lg:hidden">
            <a href="{{ url('/home') }}" class="font-display text-2xl font-extrabold tracking-wide text-brass-400 uppercase">ACLC</a>
            <button type="button" id="staff-nav-toggle" aria-controls="staff-nav" aria-expanded="false"
                class="rounded-lg border border-lobby-700 px-3 py-1.5 text-sm font-semibold text-white">
                Menu
            </button>
        </div>

        @include('components.staff-nav')

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-8">
            <header class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    @hasSection('eyebrow')
                        <p class="eyebrow mb-1">@yield('eyebrow')</p>
                    @endif
                    <h1 class="page-title">@yield('title')</h1>
                </div>
                <div class="flex flex-wrap gap-3">@yield('page-actions')</div>
            </header>

            @yield('page')
        </main>
    </div>

    <script>
        document.getElementById('staff-nav-toggle').addEventListener('click', (event) => {
            const nav = document.getElementById('staff-nav');
            const open = nav.classList.toggle('hidden') === false;
            nav.classList.toggle('flex', open);
            event.currentTarget.setAttribute('aria-expanded', String(open));
        });
    </script>
@endsection
