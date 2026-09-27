<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use \App\Models\Room;

class ReservationController extends Controller
{
    private const MAX_NIGHTS = 30;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
        $status = in_array($request->input('status'), $statuses, true) ? $request->input('status') : null;
        $text = fn ($key) => is_string($request->input($key)) ? trim($request->input($key)) : '';
        $filters = ['guest' => $text('guest'), 'room' => $text('room'), 'date' => $text('date')];
        $like = fn ($value) => '%' . addcslashes($value, '\\%_') . '%';
        $date = \DateTime::createFromFormat('!Y-m-d', $filters['date']);
        $date = $date && $date->format('Y-m-d') === $filters['date'] ? $filters['date'] : null;

        $reservations = Reservation::join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->select('reservations.*', 'guests.full_name as guest_name', 'rooms.rm_number as room_number', 'rooms.rm_type as room_type')
            ->when($status, fn ($query) => $query->whereIn('reservations.status', $status === 'cancelled' ? ['cancelled', 'canceled'] : [$status]))
            ->when($filters['guest'] !== '', fn ($query) => $query->where(fn ($match) => $match
                ->where('guests.full_name', 'like', $like($filters['guest']))
                ->orWhere('guests.email', 'like', $like($filters['guest']))))
            ->when($filters['room'] !== '', fn ($query) => $query->where('rooms.rm_number', 'like', $like($filters['room'])))
            ->when($date, fn ($query) => $query->whereDate('reservations.check_in_date', '<=', $date)->whereDate('reservations.check_out_date', '>=', $date))
            ->latest('reservations.created_at')
            ->latest('reservations.id')
            ->paginate(15)
            ->withQueryString();

        return view('staff.reservations.list', compact('reservations', 'statuses', 'status', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // reserving a room for the guest
    public function reserveRoomForm(Request $request)
    {
        $room = Room::find($request->input('room_id'));

        if (! $room) {
            return redirect('/search-room');
        }

        if (strtolower($room->status) !== 'available') {
            return redirect('/guest/rooms/details/' . $room->id)->with('error', 'This room is no longer available.');
        }

        return view('guest.reserved-room-form', compact('room'));
    }

    public function reserveRoom(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required|integer',
            'check_in_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out_date' => 'required|date_format:Y-m-d|after:check_in_date',

            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',

            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,cash',
            'payment_date' => 'required|date|before_or_equal:today',
        ], [
            'check_in_date.after_or_equal' => 'The check in date cannot be in the past.',
            'check_out_date.after' => 'The check out date must be after the check in date.',
        ]);

        $room = Room::findOrFail($validatedData['room_id']);
        $checkIn = Carbon::parse($validatedData['check_in_date']);
        $checkOut = Carbon::parse($validatedData['check_out_date']);
        $nights = (int) $checkIn->diffInDays($checkOut);

        if ($nights > self::MAX_NIGHTS) {
            throw ValidationException::withMessages(['check_out_date' => 'A stay can be at most ' . self::MAX_NIGHTS . ' nights.']);
        }

        $total = round($room->rm_price_per_night * $nights, 2);

        if (round((float) $validatedData['amount'], 2) !== $total) {
            throw ValidationException::withMessages(['amount' => 'The amount must equal the total of $' . number_format($total, 2) . '.']);
        }

        [$reservation, $payment] = DB::transaction(function () use ($validatedData, $room, $checkIn, $checkOut, $total) {
            $room = Room::query()->lockForUpdate()->findOrFail($room->id);

            if (strtolower($room->status) !== 'available') {
                throw ValidationException::withMessages(['room_id' => 'This room is no longer available.']);
            }

            $overlaps = Reservation::where('room_id', $room->id)
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->whereDate('check_in_date', '<', $checkOut)
                ->whereDate('check_out_date', '>', $checkIn)
                ->exists();

            if ($overlaps) {
                throw ValidationException::withMessages(['check_in_date' => 'This room is already booked for some of these dates.']);
            }

            $guest = Guest::firstOrCreate(
                ['email' => $validatedData['email']],
                [
                    'full_name' => $validatedData['name'],
                    'phone_number' => $validatedData['phone'],
                    'address' => $validatedData['address'],
                ]
            );

            $room->update(['status' => 'unavailable']);

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'check_in_date' => $checkIn->toDateString(),
                'check_out_date' => $checkOut->toDateString(),
                'status' => 'confirmed',
            ]);

            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $total,
                'payment_method' => $validatedData['payment_method'],
                'payment_date' => $validatedData['payment_date'],
                'status' => 'paid',
            ]);

            return [$reservation, $payment];
        });

        $guest = (object) [
            'full_name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ];

        return view('guest.receipt-page', compact('room', 'guest', 'reservation', 'payment', 'nights'));
    }

    // public function reserveRoomForm_Get1(Request $request)
    // {
    //     $room = $request->room_id ? \App\Models\Room::find($request->room_id) : null;

    //     return view('guest.room-details', compact('room'));
    // }
    // public function reserveRoomForm_Post1(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'room_id' => 'required',
    //     ]);
    //     $room = \App\Models\Room::find($validatedData['room_id']);

    //     // dd($room);

    //     return view('guest.guest-form', compact('room'));
    // }
    // public function reserveRoomForm_Post2(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'room_id' => 'required',
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //         'address' => 'required',
    //     ]);
    //     $room = \App\Models\Room::find($validatedData['room_id']);
    //     $guest = (object) [
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'phone' => $validatedData['phone'],
    //         'address' => $validatedData['address'],
    //     ];

    //     return view('guest.payment-form', compact('room', 'guest'));
    // }
    // public function reserveRoomForm_3(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'room_id' => 'required',

    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //         'address' => 'required',

    //         'amount' => 'required',
    //         'payment_method' => 'required',
    //         'payment_date' => 'required|date',
    //     ]);
    //     $room = \App\Models\Room::find($validatedData['room_id']);

    //     // check if amount is equal to room price
    //     if ($validatedData['amount'] <= $room->rm_price_per_night) {
    //         return redirect()->back()->withErrors(['amount' => 'Amount must be equal to room price']);
    //     }

    //     $guest = (object) [
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'phone' => $validatedData['phone'],
    //         'address' => $validatedData['address'],
    //     ];
    //     $payment = (object) [
    //         'amount' => $validatedData['amount'],
    //         'payment_method' => $validatedData['payment_method'],
    //         'payment_date' => $validatedData['payment_date'],
    //     ];

    //     return view('guest.receipt-page', compact('room', 'guest', 'payment'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $reservation_id)
    {
        //
        $reservation = Reservation::findOrFail($reservation_id);
        $guest = Guest::findOrFail($reservation->guest_id);
        $room = Room::findOrFail($reservation->room_id);
        $payment = Payment::where('reservation_id', $reservation->id)->first();
        // dd($reservation->toArray(), $guest->toArray(), $room->toArray(), $payment->toArray());
        return view('staff.reservations.info', compact('reservation', 'guest', 'room', 'payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $reservation_id)
    {
        //
        $reservation = Reservation::findOrFail($reservation_id);
        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);
        // Update status and dates if needed
        if ($validatedData['status'] === 'checked_in') {
            $reservation->update([
            'status' => $validatedData['status'],
            'check_in_date' => now(),
            ]);
        } elseif ($validatedData['status'] === 'checked_out') {
            $reservation->update([
            'status' => $validatedData['status'],
            'check_out_date' => now(),
            ]);
        } else {
            $reservation->update($validatedData);
        }

        if (in_array($validatedData['status'], ['checked_out', 'cancelled'], true)) {
            Room::whereKey($reservation->room_id)->update(['status' => 'available']);
        } elseif (in_array($validatedData['status'], ['confirmed', 'checked_in'], true)) {
            Room::whereKey($reservation->room_id)->update(['status' => 'unavailable']);
        }

        return redirect()->back()->with('success', 'Reservation status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
