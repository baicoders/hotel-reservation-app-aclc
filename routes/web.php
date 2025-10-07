<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
});

Route::get('/reserve-room', function () {
    return view('guest.search-rooms');
});
Route::get('/guest/rooms/details', function () {
    return view('guest.room-details');
});

Route::get('/manage', function () {
    return view('staff.dashboard');
});

Route::get('/reservations', function () {
    return view('staff.reservations.list');
});
Route::get('/reservations/details', function () {
    return view('staff.reservations.info');
});

Route::get('/guest', function () {
    return view('staff.guest.list');
});

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
