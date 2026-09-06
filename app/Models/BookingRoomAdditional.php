<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoomAdditional extends Model
{
    protected $table = 'booking_room_additionals';

    protected $fillable = [
        'booking_room_id',
        'additional_id',
        'price_per_additional',
        'total_price',
    ];

    public function bookingRoom()
    {
        return $this->belongsTo(BookingRoom::class);
    }

    public function additional()
    {
        return $this->belongsTo(Additional::class);
    }
}
