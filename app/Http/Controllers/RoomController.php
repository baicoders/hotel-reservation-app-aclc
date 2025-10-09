<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rooms = Room::limit(10)->latest()->get();
        return view('staff.rooms.list', compact('rooms'));
    }

    // list of room to reserve for the guest
    public function listRooms()
    {
        //
        $rooms = Room::where('status', 'available')->latest()->get();
        return view('guest.search-rooms', compact('rooms'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('staff.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'roomNumber' => 'required|string|max:13|unique:rooms,rm_number',
            'roomType' => 'required|string|max:255',
            'roomPrice' => 'required|numeric|min:0|max:99999.99|decimal:2',
            'status' => 'nullable|string|max:128',
        ]);

        Room::create([
            'rm_number' => $validatedData['roomNumber'],
            'rm_type' => $validatedData['roomType'],
            'rm_price_per_night' => $validatedData['roomPrice'],
            'status' => $validatedData['status'] ?? 'available',
        ]);

        return redirect('/rooms')->with('success', 'New Room is added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();
        return view('staff.rooms.info', compact('room'));
    }

    // show the details of the room to reserve for the guest
    public function reserveRoom(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();
        return view('guest.room-details', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();
        return view('staff.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();

        if (!$room) {
            return redirect('/rooms')->with('message', 'Room Not Found!');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            // 'roomNumber' => 'required|string|max:13|unique:rooms,rm_number,' . $room->rm_number,
            'roomType' => 'required|string|max:255',
            'roomPrice' => 'required|numeric|min:0|max:99999.99|decimal:2',
            'status' => 'nullable|string|max:128',
        ]);

        $room->update([
            // 'rm_number' => $validatedData['roomNumber'],
            'rm_type' => $validatedData['roomType'],
            'rm_price_per_night' => $validatedData['roomPrice'],
            'status' => $validatedData['status'] ?? 'available',
        ]);

        return redirect('/rooms')->with('success', 'Room is updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->first();

        if ($room->exists()) {
            // The record exists
            $room->delete();
        } else {
            // The record does not exist
            return redirect('/rooms')->with('message', 'Room Not Found!');
        }

        // deletets the uploaded files
        // if (Storage::disk('public')->exists('room-covers/' . $room->thumbnail)) {
        //     Storage::disk('public')->delete('room-covers/' . $room->thumbnail);
        // }

        return redirect('/rooms')->with('success', 'Room is removed!');
    }
}
