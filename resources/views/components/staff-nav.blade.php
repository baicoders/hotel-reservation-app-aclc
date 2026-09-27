@php
    $links = [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'active' => request()->is('dashboard')],
        ['label' => 'Reservations', 'url' => '/reservations', 'active' => request()->is('reservations*')],
        ['label' => 'Guests', 'url' => '/guest', 'active' => request()->is('guest') || request()->is('guest/details*')],
        ['label' => 'Rooms', 'url' => '/rooms', 'active' => request()->is('rooms*')],
    ];
@endphp
<nav id="staff-nav" aria-label="Staff"
    class="hidden flex-col bg-lobby-900 text-lobby-100 lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-64">
    <a href="{{ url('/home') }}" class="hidden px-6 pt-8 pb-6 lg:block">
        <span class="block font-display text-4xl leading-none font-extrabold tracking-wide text-brass-400 uppercase">ACLC</span>
        <span class="eyebrow mt-1 block text-lobby-100/70">Hotel front desk</span>
    </a>

    <ul class="flex flex-col gap-1 px-3 py-3 lg:py-0">
        @foreach ($links as $link)
            <li>
                <a href="{{ url($link['url']) }}"{!! $link['active'] ? ' aria-current="page"' : '' !!}
                    class="block rounded-lg px-3 py-2.5 text-sm font-semibold transition-colors {{ $link['active'] ? 'bg-lobby-700 text-white' : 'hover:bg-lobby-800 hover:text-white' }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-auto border-t border-lobby-800 px-6 py-5">
        <p class="text-xs text-lobby-100/70">Signed in as</p>
        <p class="truncate text-sm font-semibold text-white">{{ auth()->user()?->name }}</p>
        <form method="POST" action="{{ url('/logout') }}" class="mt-3" data-busy-label="Signing out...">
            @csrf
            <button type="submit" class="text-sm font-semibold text-brass-400 underline-offset-2 hover:underline">
                Sign out
            </button>
        </form>
    </div>
</nav>
