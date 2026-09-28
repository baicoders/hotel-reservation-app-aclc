<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/home', function () {
    return view('home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'loginUser']);
});
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

// Guest Routes
Route::get('/search-room', [RoomController::class, 'listRooms']);
Route::get('/guest/rooms/details/{room_id}', [RoomController::class, 'reserveRoom']);

Route::get('/guest/rooms/reserve', [ReservationController::class, 'reserveRoomForm']);
Route::post('/guest/rooms/booking-room', [ReservationController::class, 'reserveRoom']);
Route::get('/guest/receipt', [ReservationController::class, 'receipt']);
Route::post('/guest/receipt/clear', [ReservationController::class, 'clearReceipt']);

// Staff Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ManageController::class, 'dashboard']);

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/details/{reservation_id}', [ReservationController::class, 'show']);
    Route::post('/reservations/update-status/{reservation_id}', [ReservationController::class, 'update']);

    Route::get('/guest', [GuestController::class, 'index']);
    Route::get('/guest/details/{guest_id}', [GuestController::class, 'show']);
    Route::delete('/guest/delete/{guest_id}', [GuestController::class, 'destroy']);

    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/search', [RoomController::class, 'search']);
    Route::get('/rooms/details/{room_id}', [RoomController::class, 'show']);
    Route::get('/rooms/edit/{room_id}', [RoomController::class, 'edit']);
    Route::post('/rooms/update/{room_id}', [RoomController::class, 'update']);
    Route::get('/rooms/create', [RoomController::class, 'create']);
    Route::post('/rooms/record', [RoomController::class, 'store']);
    Route::delete('/rooms/delete/{room_id}', [RoomController::class, 'destroy']);
});

// Route::get('/', function () {
//     return view('welcome');
// });
