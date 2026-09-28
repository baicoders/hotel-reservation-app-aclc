@extends('layout.guest')

@section('title', 'Book room ' . $room->rm_number)
@section('eyebrow', 'Reserve a room')
@section('inline-errors', true)

@section('page')
    @php
        $invalid = fn ($field) => $errors->has($field) ? 'aria-invalid=true aria-describedby=' . $field . '-error' : '';
        $money = fn ($value) => '$' . number_format($value, 2);
        $keep = fn ($field, $default = '') => is_string(old($field)) ? old($field) : $default;
        $method = $keep('payment_method');
        $cancel = url('/guest/rooms/details/' . $room->id) . '?' . http_build_query(['check_in' => $checkIn, 'check_out' => $checkOut]);
    @endphp

    @error('room_id')
        <p role="alert" class="mb-6 rounded-lg bg-brick-100 p-4 text-sm font-medium text-brick-700">{{ $message }}</p>
    @enderror

    <form action="{{ url('/guest/rooms/booking-room') }}" method="POST" data-busy-label="Booking..." class="grid gap-6 lg:grid-cols-3">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <div class="flex flex-col gap-6 lg:col-span-2">
            <fieldset class="card p-6">
                <legend class="float-left w-full font-semibold">Your stay</legend>
                <div class="clear-both grid gap-5 pt-4 sm:grid-cols-2">
                    <div>
                        <label for="check_in_date" class="field-label">Check in</label>
                        <input type="date" id="check_in_date" name="check_in_date" value="{{ $checkIn }}" min="{{ today()->toDateString() }}" required class="field-input" {{ $invalid('check_in_date') }}>
                        @error('check_in_date')<p id="check_in_date-error" class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="check_out_date" class="field-label">Check out</label>
                        <input type="date" id="check_out_date" name="check_out_date" value="{{ $checkOut }}" min="{{ today()->addDay()->toDateString() }}" required class="field-input" {{ $invalid('check_out_date') }}>
                        @error('check_out_date')<p id="check_out_date-error" class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="card p-6">
                <legend class="float-left w-full font-semibold">Guest details</legend>
                <div class="clear-both grid gap-5 pt-4 sm:grid-cols-2">
                    @foreach ([
                        ['name', 'Full name', 'text', 'name', 255],
                        ['email', 'Email', 'email', 'email', 255],
                        ['phone', 'Phone', 'tel', 'tel', 30],
                        ['address', 'Address', 'text', 'street-address', 255],
                    ] as [$field, $label, $type, $autocomplete, $max])
                        <div>
                            <label for="{{ $field }}" class="field-label">{{ $label }}</label>
                            <input type="{{ $type }}" id="{{ $field }}" name="{{ $field }}" value="{{ $keep($field) }}" required maxlength="{{ $max }}"
                                autocomplete="{{ $autocomplete }}" class="field-input" {{ $invalid($field) }}>
                            @error($field)<p id="{{ $field }}-error" class="field-error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <fieldset class="card p-6">
                <legend class="float-left w-full font-semibold">Payment</legend>
                <div class="clear-both grid gap-5 pt-4 sm:grid-cols-3">
                    <div>
                        <label for="amount" class="field-label">Amount ($)</label>
                        <input type="text" id="amount" name="amount" value="{{ number_format($total, 2, '.', '') }}" readonly
                            class="field-input bg-porcelain" aria-describedby="amount-hint{{ $errors->has('amount') ? ' amount-error' : '' }}">
                        <p id="amount-hint" class="mt-1 text-sm text-muted">Nightly rate times nights.</p>
                        @error('amount')<p id="amount-error" class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="payment_method" class="field-label">Payment method</label>
                        <select id="payment_method" name="payment_method" required class="field-input" {{ $invalid('payment_method') }}>
                            <option value="">Choose a method</option>
                            @foreach (['credit_card' => 'Credit card', 'debit_card' => 'Debit card', 'paypal' => 'PayPal', 'cash' => 'Cash'] as $value => $label)
                                <option value="{{ $value }}" @selected($method === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_method')<p id="payment_method-error" class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="payment_date" class="field-label">Payment date</label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ $keep('payment_date', today()->toDateString()) }}" max="{{ today()->toDateString() }}" required class="field-input" {{ $invalid('payment_date') }}>
                        @error('payment_date')<p id="payment_date-error" class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>
        </div>

        <aside class="card self-start p-6" aria-label="Booking summary">
            @include('components.key-tag', ['number' => $room->rm_number, 'size' => 'lg'])
            <dl id="booking-summary" data-rate="{{ $room->rm_price_per_night }}" class="mt-6 divide-y divide-line">
                <div class="flex justify-between gap-4 py-3"><dt class="text-muted">Room type</dt><dd class="font-semibold">{{ ucfirst($room->rm_type) }}</dd></div>
                <div class="flex justify-between gap-4 py-3"><dt class="text-muted">Nightly rate</dt><dd class="font-semibold tabular-nums">{{ $money($room->rm_price_per_night) }}</dd></div>
                <div class="flex justify-between gap-4 py-3"><dt class="text-muted">Nights</dt><dd id="summary-nights" class="font-semibold tabular-nums">{{ $nights }}</dd></div>
                <div class="flex justify-between gap-4 py-3"><dt class="font-semibold">Total</dt><dd id="summary-total" class="font-display text-2xl font-bold text-lobby-900 tabular-nums" aria-live="polite">{{ $money($total) }}</dd></div>
            </dl>
            <button type="submit" class="btn btn-primary mt-6 w-full py-3 text-base">Book and pay</button>
            <a href="{{ $cancel }}" class="btn btn-secondary mt-3 w-full">Cancel</a>
        </aside>
    </form>
@endsection

@section('scripts')
    <script>
        (() => {
            const summary = document.getElementById('booking-summary');
            const rate = Number(summary.dataset.rate);
            const checkIn = document.getElementById('check_in_date');
            const checkOut = document.getElementById('check_out_date');
            const update = () => {
                const nights = Math.round((Date.parse(checkOut.value) - Date.parse(checkIn.value)) / 86400000);
                if (!(nights > 0)) return;
                const total = (Math.round(rate * nights * 100) / 100).toFixed(2);
                document.getElementById('summary-nights').textContent = nights;
                document.getElementById('summary-total').textContent = '$' + Number(total).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('amount').value = total;
            };
            checkIn.addEventListener('change', update);
            checkOut.addEventListener('change', update);
            update();
        })();
    </script>
@endsection
