<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    private const ATTRIBUTES = [
        'roomPrice' => 'price per night',
        'roomPhotos.*' => 'photo',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = is_string($request->input('query')) ? trim($request->input('query')) : '';
        $status = in_array($request->input('status'), ['available', 'unavailable', 'maintenance'], true)
            ? $request->input('status')
            : null;

        $rooms = Room::with('firstPhoto')
            ->when($query !== '', fn ($rooms) => $rooms->where(fn ($match) => $match
                ->where('rm_number', 'like', '%' . $query . '%')
                ->orWhere('rm_type', 'like', '%' . $query . '%')))
            ->when($status, fn ($rooms) => $rooms->whereRaw('LOWER(status) = ?', [$status]))
            ->orderByRaw('LENGTH(rm_number), rm_number')
            ->paginate(12)
            ->withQueryString();

        return view('staff.rooms.list', compact('rooms', 'query', 'status'));
    }

    // list of room to reserve for the guest
    public function listRooms()
    {
        //
        $rooms = Room::whereRaw('LOWER(status) = ?', ['available'])
            ->limit(50)
            ->with('firstPhoto')
            ->latest('created_at')
            ->get();
        // $rooms = Room::where('status', 'available')->with('firstPhoto')->latest('created_at')->get();
        // dd($rooms);
        return view('guest.search-rooms', compact('rooms'));
    }
    public function search(Request $request)
    {
        return $this->index($request);
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
            'roomPrice' => 'required|numeric|min:0|max:99999.99|decimal:0,2',
            'status' => 'nullable|in:available,unavailable,maintenance',
            'roomPhotos' => 'nullable|array',
            'roomPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [], self::ATTRIBUTES);

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
            if (!$uploadResult) {
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
            $path = $photo->store('room-photos', 'public');

            DB::table('rooms_photos')->insert([
                'room_id' => $room->id,
                'photo_path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return true;
    }
    public function uploadSinglePhoto(Request $request, string $room_id)
    {
        $room = Room::where('id', $room_id)->firstOrFail();

        $request->validate([
            'roomPhotos' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $photo = $request->file('roomPhotos');
        $path = $photo->store('room-photos', 'public');

        DB::table('rooms_photos')->insert([
            'room_id' => $room->id,
            'photo_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
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
            'roomPrice' => 'required|numeric|min:0|max:99999.99|decimal:0,2',
            'status' => 'nullable|in:available,unavailable,maintenance',
            'roomPhotos' => 'nullable|array',
            'roomPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [], self::ATTRIBUTES);

        $room->update([
            // 'rm_number' => $validatedData['roomNumber'],
            'rm_type' => $validatedData['roomType'],
            'rm_price_per_night' => $validatedData['roomPrice'],
            'status' => $validatedData['status'] ?? 'available',
        ]);

        if ($request->hasFile('roomPhotos')) {
            $this->uploadPhoto($request, $room->id);
        }

        return redirect('/rooms/details/' . $room->id)->with('success', 'Room is updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $room_id)
    {
        //
        $room = Room::find($room_id);

        if (!$room) {
            return redirect('/rooms')->with('error', 'Room not found.');
        }

        $room->delete();

        // deletets the uploaded files
        // if (Storage::disk('public')->exists('room-covers/' . $room->thumbnail)) {
        //     Storage::disk('public')->delete('room-covers/' . $room->thumbnail);
        // }

        return redirect('/rooms')->with('success', 'Room is removed!');
    }
}
