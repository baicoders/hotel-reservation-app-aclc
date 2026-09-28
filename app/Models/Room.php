<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomModel;
use \Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function firstPhoto() : HasOne
    {
        // Assuming 'id' is the column that determines the desired order
        // (e.g., the lowest ID is the first photo)
        // return $this->hasOne(DB::table('rooms_photos')->where('room_id', $this->id)->get())->oldest('id');
        // return $this->hasOne(RoomPhoto::class, 'room_id')->oldest('id');
        return $this->hasOne(RoomModel::class, 'room_id')->oldest('id');
        // Alternatively, if you want the photo ordered by its own creation date:
        // return $this->hasOne(RoomPhoto::class, 'room_id')->oldest('created_at');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
