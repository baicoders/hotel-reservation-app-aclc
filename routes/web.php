<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
});

// Guest Routes
Route::get('/search-room', [RoomController::class, 'listRooms']);
Route::get('/guest/rooms/details/{room_id}', [RoomController::class, 'reserveRoom']);

Route::get('/guest/rooms/reserve', [ReservationController::class, 'reserveRoomForm']);
Route::post('/guest/rooms/booking-room', [ReservationController::class, 'reserveRoom']);

// I dunno about this routes they are kinda messy
// Route::get('/guest/rooms/guest-details', [ReservationController::class, 'reserveRoomForm_Get1']);
// Route::post('/guest/rooms/guest-details', [ReservationController::class, 'reserveRoomForm_Post1']);
// Route::get('/guest/rooms/payment-details', [ReservationController::class, 'reserveRoomForm_Get2']);
// Route::post('/guest/rooms/payment-details', [ReservationController::class, 'reserveRoomForm_Post2']);
// Route::post('/guest/rooms/submit-all-details', [ReservationController::class, 'reserveRoomForm_Post3']);


// Staff Routes
Route::get('/dashboard', function () {
    return view('staff.dashboard');
});

Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/details/{reservation_id}', [ReservationController::class, 'show']);
Route::post('/reservations/update-status/{reservation_id}', [ReservationController::class, 'update']);

Route::get('/guest', [GuestController::class, 'index']);
Route::get('/guest/details/{guest_id}', [GuestController::class, 'show']);

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/details/{room_id}', [RoomController::class, 'show']);
Route::get('/rooms/edit/{room_id}', [RoomController::class, 'edit']);
Route::post('/rooms/update/{room_id}', [RoomController::class, 'update']);
Route::get('/rooms/create', [RoomController::class, 'create']);
Route::post('/rooms/record', [RoomController::class, 'store']);
Route::delete('/rooms/delete/{room_id}', [RoomController::class, 'destroy']);

Route::get('/', function () {
    return view('welcome');
});
