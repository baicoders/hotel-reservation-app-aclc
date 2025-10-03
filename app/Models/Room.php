<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;
    protected $fillable = [
        'rm_number',
        'rm_type',
        'rm_price_per_night',
        'status',
    ];
    
}
