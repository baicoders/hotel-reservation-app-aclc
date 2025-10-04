<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
});

Route::get('/reserve-room', function () {
    return view('guest.search-rooms');
});

Route::get('/manage', function () {
    return view('staff.dashboard');
});

Route::get('/reservations', function () {
    return view('staff.reservations.list');
});

Route::get('/', function () {
    return view('welcome');
});
