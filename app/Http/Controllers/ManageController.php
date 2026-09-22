<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;

class ManageController extends Controller
{
    //
    public function dashboard()
    {
        // get latest 5 data of reservations, guests, rooms
        $latestReservations = Reservation::query()
            ->join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->select('reservations.*', 'guests.full_name as guest_name')
            ->latest('reservations.created_at')
            ->take(5)
            ->get();
        $latestGuests = Guest::latest()->take(5)->get();
        $latestRooms = Room::latest()->take(5)->get();

        return view('staff.dashboard', compact('latestReservations', 'latestGuests', 'latestRooms'));
    }
}
