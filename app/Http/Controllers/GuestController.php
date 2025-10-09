<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $guests = Guest::latest()->take(50)->get();
        return view('staff.guest.list', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
    public function show(string $guest_id)
    {
        //
        $guest = Guest::findOrFail($guest_id);
        // show the list of reservations made by the guest
        $bookings = Reservation::join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->where('reservations.guest_id', $guest->id)
            ->select('reservations.*', 'guests.*', 'rooms.*')
            ->get();
        //
        // dd($bookings->toArray());
        return view('staff.guest.info', compact('guest', 'bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        //
    }
}
