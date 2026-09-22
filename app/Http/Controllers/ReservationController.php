<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Room;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservations = Reservation::join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->select('reservations.*', 'guests.full_name as guest_name', 'rooms.rm_number as room_number', 'rooms.rm_type as room_type')
            ->latest('reservations.created_at')
            ->take(50)
            ->get();
        // dd('reservations', $reservations->toArray());
        return view('staff.reservations.list', compact('reservations'));
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
        $room = $request->room_id ? Room::findOrFail($request->room_id) : null;

        return view('guest.reserved-room-form', compact('room'));
    }
    public function reserveRoom(Request $request)
    {
        $validatedData = $request->validate([
            'room_id' => 'required',

            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',

            'amount' => 'required',
            'payment_method' => 'required',
            'payment_date' => 'required|date',
        ]);

        $room = Room::findOrFail($validatedData['room_id']);

        if (strtolower($room->status) !== 'available') {
            return redirect()->back()->withErrors(['room_id' => 'This room is no longer available.'])->withInput();
        }

        if ((float) $validatedData['amount'] !== (float) $room->rm_price_per_night) {
            return redirect()->back()->withErrors(['amount' => 'Amount must equal the room price.'])->withInput();
        }
        $guest = (object) [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ];
        $payment = (object) [
            'amount' => $validatedData['amount'],
            'payment_method' => $validatedData['payment_method'],
            'payment_date' => $validatedData['payment_date'],
        ];

        [$existingGuest, $reservation] = DB::transaction(function () use ($guest, $payment, $room) {
            $room = Room::query()->lockForUpdate()->findOrFail($room->id);

            if (strtolower($room->status) !== 'available') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'room_id' => 'This room is no longer available.',
                ]);
            }

            $existingGuest = Guest::firstOrCreate(
                ['email' => $guest->email],
                [
                    'full_name' => $guest->name,
                    'phone_number' => $guest->phone,
                    'address' => $guest->address,
                ]
            );

            $room->update(['status' => 'unavailable']);

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'guest_id' => $existingGuest->id,
                'check_in_date' => now(),
                'check_out_date' => now()->addDay(),
                'status' => 'confirmed',
            ]);

            Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_date' => $payment->payment_date,
                'status' => 'paid',
            ]);

            return [$existingGuest, $reservation];
        });

        return view('guest.receipt-page', compact('room', 'guest', 'payment'));
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
