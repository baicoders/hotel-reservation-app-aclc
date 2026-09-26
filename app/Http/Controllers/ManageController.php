<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;

class ManageController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'rooms' => Room::count(),
            'available' => Room::whereRaw('LOWER(status) = ?', ['available'])->count(),
            'active' => Reservation::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'guests' => Guest::count(),
        ];

        $latestReservations = Reservation::query()
            ->join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->select('reservations.*', 'guests.full_name as guest_name', 'rooms.rm_number as room_number')
            ->latest('reservations.created_at')
            ->take(6)
            ->get();
        $latestGuests = Guest::latest()->take(5)->get();
        $unavailableRooms = Room::whereRaw('LOWER(status) != ?', ['available'])->orderBy('rm_number')->take(8)->get();

        return view('staff.dashboard', compact('stats', 'latestReservations', 'latestGuests', 'unavailableRooms'));
    }
}
