@extends('layout.staff')

@section('title', 'Room ' . $room->rm_number)
@section('eyebrow', 'Rooms')

@section('page-actions')
    @if (strtolower($room->status) === 'available')
        <a href="{{ url('/guest/rooms/reserve') . '?room_id=' . $room->id }}" class="btn btn-secondary">Book this room</a>
    @endif
    <a href="{{ url('/rooms/edit/' . $room->id) }}" class="btn btn-primary">Edit room</a>
@endsection

@section('page')
    @php $count = count($room->images); @endphp
    <div class="grid gap-6 lg:grid-cols-5">
        <section class="card overflow-hidden lg:col-span-3" aria-label="Photos">
            @if ($count)
                <div id="gallery" class="flex aspect-[4/3] snap-x snap-mandatory overflow-x-auto scroll-smooth">
                    @foreach ($room->images as $image)
                        <div class="relative w-full shrink-0 snap-start bg-lobby-50">
                            <span aria-hidden="true" class="absolute inset-0 flex items-center justify-center text-sm text-muted">Photo unavailable</span>
                            <img src="{{ asset('storage/' . $image->photo_path) }}" alt="Room {{ $room->rm_number }}, photo {{ $loop->iteration }} of {{ $count }}"
                                onerror="this.previousElementSibling.removeAttribute('aria-hidden'); this.remove()" class="absolute inset-0 size-full object-cover">
                        </div>
                    @endforeach
                </div>
                @if ($count > 1)
                    <div class="flex items-center justify-between border-t border-line px-4 py-3">
                        <button type="button" data-gallery-step="-1" class="btn btn-secondary">Previous</button>
                        <p id="gallery-counter" class="text-sm text-muted" aria-live="polite">Photo 1 of {{ $count }}</p>
                        <button type="button" data-gallery-step="1" class="btn btn-secondary">Next</button>
                    </div>
                @endif
            @else
                <div class="flex aspect-[4/3] flex-col items-center justify-center gap-2 bg-lobby-50 text-center">
                    <p class="font-semibold">No photos yet</p>
                    <a href="{{ url('/rooms/edit/' . $room->id) }}" class="text-sm font-semibold text-lobby-700 hover:underline">Add photos</a>
                </div>
            @endif
        </section>

        <section class="card p-6 lg:col-span-2">
            @include('components.key-tag', ['number' => $room->rm_number, 'size' => 'lg'])
            <dl class="mt-6 divide-y divide-line">
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-muted">Type</dt>
                    <dd class="font-semibold capitalize">{{ $room->rm_type }}</dd>
                </div>
                <div class="flex justify-between gap-4 py-3">
                    <dt class="text-muted">Price per night</dt>
                    <dd class="font-semibold tabular-nums">${{ number_format($room->rm_price_per_night, 2) }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3">
                    <dt class="text-muted">Status</dt>
                    <dd>@include('components.status-badge', ['status' => $room->status])</dd>
                </div>
            </dl>

            <form action="{{ url('/rooms/delete/' . $room->id) }}" id="room_delete_form" method="POST" class="mt-6 border-t border-line pt-6"
                onsubmit="return handleFormSubmission(event, @js('Room ' . $room->rm_number . ' and its ' . $reservations->count() . ' ' . Str::plural('reservation', $reservations->count()) . ', with their payments, will be deleted. This cannot be undone.'), @js('Delete room ' . $room->rm_number . '?'), 'room_delete_form');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">Delete room</button>
            </form>
        </section>
    </div>

    <section class="card mt-6 overflow-hidden">
        <h2 class="border-b border-line px-5 py-4 font-semibold">Reservations</h2>
        @if ($reservations->isEmpty())
            <p class="px-5 py-8 text-center text-muted">No reservations for this room yet.</p>
        @else
            <ul class="divide-y divide-line">
                @foreach ($reservations as $reservation)
                    <li>
                        <a href="{{ url('/reservations/details/' . $reservation->id) }}"
                            class="flex flex-wrap items-center gap-x-4 gap-y-1 px-5 py-3 hover:bg-lobby-50">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-medium">{{ $reservation->guest_name }}</span>
                                <span class="block truncate text-sm text-muted">{{ $reservation->guest_email }}</span>
                            </span>
                            <span class="text-sm text-muted tabular-nums">
                                {{ \Illuminate\Support\Carbon::parse($reservation->check_in_date)->format('M j, Y') }}
                                to {{ \Illuminate\Support\Carbon::parse($reservation->check_out_date)->format('M j, Y') }}
                            </span>
                            @include('components.status-badge', ['status' => $reservation->status])
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection

@section('scripts')
    <script>
        (() => {
            const gallery = document.getElementById('gallery');
            const counter = document.getElementById('gallery-counter');
            if (!gallery || !counter) return;
            const total = gallery.children.length;
            const current = () => Math.round(gallery.scrollLeft / gallery.clientWidth);
            document.querySelectorAll('[data-gallery-step]').forEach((button) => {
                button.addEventListener('click', () => {
                    const next = (current() + Number(button.dataset.galleryStep) + total) % total;
                    const wraps = Math.abs(next - current()) > 1;
                    gallery.scrollTo({ left: next * gallery.clientWidth, behavior: wraps ? 'instant' : 'smooth' });
                });
            });
            let shown = 0;
            gallery.addEventListener('scroll', () => {
                if (current() === shown) return;
                shown = current();
                counter.textContent = `Photo ${shown + 1} of ${total}`;
            });
        })();
    </script>
@endsection
