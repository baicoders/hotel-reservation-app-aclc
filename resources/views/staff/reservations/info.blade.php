@extends('layout.staff')

@section('title', 'Reservation #' . $reservation->id)
@section('eyebrow', 'Reservations')

@section('page')
    @php
        $label = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
        $date = fn ($value) => \Illuminate\Support\Carbon::parse($value)->format('D, M j, Y');
    @endphp
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="grid gap-6 sm:grid-cols-2 lg:col-span-2">
            <section class="card p-5 sm:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="font-semibold">Stay</h2>
                    @include('components.status-badge', ['status' => $reservation->status])
                </div>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div><dt class="eyebrow">Check in</dt><dd class="mt-1 font-semibold">{{ $date($reservation->check_in_date) }}</dd></div>
                    <div><dt class="eyebrow">Check out</dt><dd class="mt-1 font-semibold">{{ $date($reservation->check_out_date) }}</dd></div>
                </dl>
            </section>

            <section class="card p-5">
                <h2 class="font-semibold">Guest</h2>
                <p class="mt-3 font-semibold">{{ $guest->full_name }}</p>
                <p class="text-sm text-muted">{{ $guest->email }}</p>
                <p class="text-sm text-muted">{{ $guest->phone_number ?: 'No phone number' }}</p>
                <a href="{{ url('/guest/details/' . $guest->id) }}" class="mt-3 inline-block text-sm font-semibold text-lobby-700 hover:underline">View guest</a>
            </section>

            <section class="card p-5">
                <h2 class="font-semibold">Room</h2>
                <div class="mt-3">@include('components.key-tag', ['number' => $room->rm_number])</div>
                <p class="mt-2 text-sm text-muted">{{ ucfirst($room->rm_type) }}, ${{ number_format($room->rm_price_per_night, 2) }} per night</p>
                <a href="{{ url('/rooms/details/' . $room->id) }}" class="mt-3 inline-block text-sm font-semibold text-lobby-700 hover:underline">View room</a>
            </section>

            <section class="card p-5 sm:col-span-2">
                <h2 class="font-semibold">Payment</h2>
                @if ($payment)
                    <dl class="mt-4 grid gap-4 sm:grid-cols-4">
                        <div><dt class="eyebrow">Amount</dt><dd class="mt-1 font-semibold tabular-nums">${{ number_format($payment->amount, 2) }}</dd></div>
                        <div><dt class="eyebrow">Method</dt><dd class="mt-1 font-semibold">{{ $label($payment->payment_method) }}</dd></div>
                        <div><dt class="eyebrow">Paid on</dt><dd class="mt-1 font-semibold">{{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('M j, Y') }}</dd></div>
                        <div><dt class="eyebrow">Status</dt><dd class="mt-1 font-semibold">{{ $label($payment->status) }}</dd></div>
                    </dl>
                @else
                    <p class="mt-3 text-muted">No payment recorded.</p>
                @endif
            </section>
        </div>

        <section class="card self-start p-5">
            <h2 class="font-semibold">Update status</h2>
            <p class="mt-1 text-sm text-muted">Checking in marks the room unavailable. Checking out or cancelling makes it available again.</p>
            <form action="{{ url('/reservations/update-status/' . $reservation->id) }}" id="reservation_status_form" method="POST" class="mt-4 flex flex-col gap-3"
                onsubmit="return handleFormSubmission(event, @js('Change reservation #' . $reservation->id . ' for ' . $guest->full_name . ' to ') + this.status.selectedOptions[0].text + '?', 'Update status?', 'reservation_status_form');">
                @csrf
                <label for="status" class="field-label">New status</label>
                <select id="status" name="status" class="field-input">
                    @foreach (['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $value)
                        <option value="{{ $value }}" @selected(str_replace('canceled', 'cancelled', $reservation->status) === $value)>{{ $label($value) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Update status</button>
            </form>
        </section>
    </div>
@endsection
