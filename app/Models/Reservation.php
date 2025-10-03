<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;
    protected $fillable = [
        'room_id',
        'guest_id',
        'check_in_date',
        'check_out_date',
        'status',
    ];
}
