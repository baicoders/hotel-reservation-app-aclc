<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomModel extends Model
{
    //
    protected $table = 'rooms_photos'; // Specify the table name if it's not the plural of the model name
    protected $fillable = ['room_id', 'photo_path']; // Specify fillable fields
}
