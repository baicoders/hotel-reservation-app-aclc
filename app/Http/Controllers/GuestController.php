<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = is_string($request->input('query')) ? trim($request->input('query')) : '';

        $guests = Guest::withCount('reservations')
            ->when($query !== '', fn ($guests) => $guests->where(fn ($match) => $match
                ->where('full_name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')))
            ->latest()
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('staff.guest.list', compact('guests', 'query'));
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
    public function destroy(string $guest_id)
    {
        $guest = Guest::findOrFail($guest_id);
        $active = ['confirmed', 'checked_in'];

        DB::transaction(function () use ($guest, $active) {
            $roomIds = Reservation::where('guest_id', $guest->id)->whereIn('status', $active)->pluck('room_id');
            $stillHeld = Reservation::whereIn('room_id', $roomIds)
                ->where('guest_id', '!=', $guest->id)
                ->whereIn('status', $active)
                ->pluck('room_id');

            Room::whereKey($roomIds->diff($stillHeld))->whereRaw('LOWER(status) = ?', ['unavailable'])->update(['status' => 'available']);
            $guest->delete();
        });

        return redirect('/guest')->with('success', 'Guest and related reservations deleted successfully.');
    }
}
