<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rooms extends Model
{
    use SoftDeletes;
    protected $table = 'rooms';
    protected $fillable = [
        "id_room_type",
        "room_number",
        "floor_number",
        "remarks",
        "status",
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomTypes::class, 'id_room_type');
    }

    public function bookings()
    {
        return $this->hasMany(BookingRoom::class, 'room_id');
    }
}
