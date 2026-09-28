@extends('layout.guest')

@section('title', 'Booking confirmed')
@section('eyebrow', 'Receipt')

@section('page')
    @php
        $date = fn ($value) => \Illuminate\Support\Carbon::parse($value)->format('D, M j, Y');
        $money = fn ($value) => '$' . number_format($value, 2);
        $methods = ['credit_card' => 'Credit card', 'debit_card' => 'Debit card', 'paypal' => 'PayPal', 'cash' => 'Cash'];
    @endphp
    <article class="card max-w-3xl overflow-hidden print:border-0">
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-line bg-lobby-50 px-6 py-5">
            <div>
                <p class="eyebrow">Confirmation number</p>
                <p class="font-display text-3xl font-bold tracking-wide text-lobby-900">{{ $receipt['number'] }}</p>
            </div>
            @include('components.key-tag', ['number' => $receipt['room_number'], 'size' => 'lg'])
        </header>

        <div class="px-6 py-5">
            <p>Thank you, {{ $receipt['guest_name'] }}. Your stay is booked and paid. Keep this receipt and show your confirmation number at the front desk.</p>
        </div>

        <div class="grid gap-6 border-t border-line px-6 py-5 sm:grid-cols-2">
            <section>
                <h2 class="font-semibold">Stay</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-muted">Check in</dt><dd class="font-medium">{{ $date($receipt['check_in']) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Check out</dt><dd class="font-medium">{{ $date($receipt['check_out']) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Nights</dt><dd class="font-medium tabular-nums">{{ $receipt['nights'] }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Room</dt><dd class="font-medium">{{ $receipt['room_number'] }}, {{ ucfirst($receipt['room_type']) }}</dd></div>
                </dl>
            </section>

            <section>
                <h2 class="font-semibold">Guest</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-muted">Name</dt><dd class="font-medium">{{ $receipt['guest_name'] }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Email</dt><dd class="min-w-0 font-medium break-words">{{ $receipt['guest_email'] }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Phone</dt><dd class="font-medium">{{ $receipt['guest_phone'] }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-muted">Address</dt><dd class="font-medium">{{ $receipt['guest_address'] }}</dd></div>
                </dl>
            </section>
        </div>

        <section class="border-t border-line px-6 py-5">
            <h2 class="font-semibold">Payment</h2>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-muted">{{ $receipt['nights'] }} {{ Str::plural('night', $receipt['nights']) }} at {{ $money($receipt['rate']) }}</dt><dd class="font-medium tabular-nums">{{ $money($receipt['total']) }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Method</dt><dd class="font-medium">{{ $methods[$receipt['payment_method']] ?? $receipt['payment_method'] }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Paid on</dt><dd class="font-medium">{{ $date($receipt['payment_date']) }}</dd></div>
            </dl>
            <div class="mt-4 flex items-baseline justify-between gap-4 border-t border-line pt-4">
                <span class="font-semibold">Total paid</span>
                <span class="font-display text-3xl font-bold text-lobby-900 tabular-nums">{{ $money($receipt['total']) }}</span>
            </div>
        </section>
    </article>

    <div class="mt-6 flex max-w-3xl flex-col gap-3 sm:flex-row print:hidden">
        <button type="button" onclick="window.print()" class="btn btn-primary">Print receipt</button>
        <button type="button" onclick="window.print()" class="btn btn-secondary">Save as PDF</button>
        @foreach (['rooms' => 'Book another room', 'home' => 'Done'] as $next => $label)
            <form method="POST" action="{{ url('/guest/receipt/clear') }}" data-busy-label="Loading..." class="{{ $loop->first ? 'sm:ml-auto' : '' }}">
                @csrf
                <input type="hidden" name="next" value="{{ $next }}">
                <button type="submit" class="btn btn-secondary w-full">{{ $label }}</button>
            </form>
        @endforeach
    </div>
    <p class="mt-3 max-w-3xl text-sm text-muted print:hidden">To save a PDF, choose "Save as PDF" as the destination in the print window. For privacy this receipt closes after 15 minutes, or when you press Done.</p>
@endsection
