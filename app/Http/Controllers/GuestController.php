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
                ->where('full_name', 'like', '%' . addcslashes($query, '\\%_') . '%')
                ->orWhere('email', 'like', '%' . addcslashes($query, '\\%_') . '%')))
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
        $guest = Guest::findOrFail($guest_id);

        $bookings = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->where('reservations.guest_id', $guest->id)
            ->select('reservations.*', 'rooms.rm_number as room_number', 'rooms.rm_type as room_type', 'rooms.status as room_status')
            ->latest('reservations.created_at')
            ->latest('reservations.id')
            ->get();

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
