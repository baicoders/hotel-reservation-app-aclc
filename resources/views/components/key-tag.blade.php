@php
    $large = ($size ?? 'sm') === 'lg';
@endphp
<span class="inline-flex items-center rounded-l-full rounded-r-md bg-brass-400 font-display font-extrabold tracking-wide text-lobby-900 shadow-sm ring-1 ring-brass-600/40 {{ $large ? 'gap-3 py-1.5 pr-5 pl-3 text-4xl' : 'gap-2 py-0.5 pr-3 pl-2 text-xl' }}">
    <span aria-hidden="true"
        class="shrink-0 rounded-full bg-porcelain shadow-inner ring-1 ring-brass-600/50 {{ $large ? 'size-3.5' : 'size-2.5' }}"></span>
    <span><span class="sr-only">Room </span>{{ $number }}</span>
</span>
