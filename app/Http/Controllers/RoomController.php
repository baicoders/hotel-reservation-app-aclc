<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $rooms = Room::limit(10)->leftJoin('rooms_photos', 'rooms.id', '=', 'rooms_photos.room_id')->select('rooms.*', 'rooms_photos.room_id','rooms_photos.photo_path')->latest('rooms.created_at')->distinct('rooms_photos.room_id')->get();
        $rooms = Room::limit(10)->with('firstPhoto')->latest('created_at')->get();
        // dd($rooms);
        return view('staff.rooms.list', compact('rooms'));
    }

    // list of room to reserve for the guest
    public function listRooms()
    {
        //
        $rooms = Room::limit(50)->where('status', 'available')->with('firstPhoto')->latest('created_at')->get();
        // $rooms = Room::where('status', 'available')->with('firstPhoto')->latest('created_at')->get();
        // dd($rooms);
        return view('guest.search-rooms', compact('rooms'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $rooms = Room::where('rm_number', 'like', '%' . $query . '%')
            ->with('firstPhoto')
            ->orWhere('rm_type', 'like', '%' . $query . '%')
            ->latest()
            ->get();

        return view('staff.rooms.list', compact('rooms'));
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

        // Remove dd($validatedData);

        $room = Room::create([
            'rm_number' => $validatedData['roomNumber'],
            'rm_type' => $validatedData['roomType'],
            'rm_price_per_night' => $validatedData['roomPrice'],
            'status' => $validatedData['status'] ?? 'available',
        ]);

        // Save room photos
        if ($request->hasFile('roomPhotos')) {
            $photos = $request->file('roomPhotos');
            $uploadResult = false;
            if (is_array($photos)) {
                $uploadResult = $this->uploadPhoto($request, $room->id);
            } else {
                $uploadResult = $this->uploadSinglePhoto($request, $room->id);
            }
            if (!$uploadResult || (method_exists($uploadResult, 'getStatusCode') && $uploadResult->getStatusCode() !== 200)) {
                return redirect('/rooms')->with('error', 'Photo upload failed!');
            }
        }

        return redirect('/rooms')->with('success', 'New Room is added!');
    }

    public function uploadPhoto(Request $request, string $room_id)
    {
        $room = Room::where('id', $room_id)->firstOrFail();

        $request->validate([
            'roomPhotos' => 'required|array',
            'roomPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        foreach ($request->file('roomPhotos') as $photo) {
            $filename = uniqid('room_') . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('room-photos', $filename, 'public');

            DB::table('rooms_photos')->insert([
                'room_id' => $room->id,
                'photo_path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Photos uploaded successfully!');
    }
    public function uploadSinglePhoto(Request $request, string $room_id)
    {
        $room = Room::where('id', $room_id)->firstOrFail();

        $request->validate([
            'roomPhotos' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $photo = $request->file('photo');
        $filename = uniqid('room_') . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs('room-photos', $filename, 'public');

        DB::table('rooms_photos')->insert([
            'room_id' => $room->id,
            'photo_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Photo uploaded successfully!')->with('photo', $path);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();

        $photos = DB::table('rooms_photos')->where('room_id', $room->id)->get();
        $room->images = $photos;

        $reservations = Reservation::where('room_id', $room_id)
            ->where('status', 'confirmed')
            ->join('guests', 'reservations.guest_id', '=', 'guests.id')
            ->select('reservations.*', 'guests.full_name as guest_name', 'guests.email as guest_email')
            ->latest('reservations.created_at')
            ->get();
        return view('staff.rooms.info', compact('room', 'reservations'));
    }

    // show the details of the room to reserve for the guest
    public function reserveRoom(string $room_id)
    {
        //
        $room = Room::where('id', $room_id)->firstOrFail();
        $photos = DB::table('rooms_photos')->where('room_id', $room->id)->get();
        $room->images = $photos;
        return view('guest.room-details', compact('room', 'photos'));
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
