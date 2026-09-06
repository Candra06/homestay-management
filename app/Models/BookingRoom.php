<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = 'booking_rooms';

    protected $fillable = [
        'booking_id',
        'room_type_id',
        'room_id',
        'checkin_date',
        'checkout_date',
        'price_per_night',
        'total_days',
        'subtotal',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function additionals()
    {
        return $this->hasMany(BookingRoomAdditional::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->price_per_night * $this->total_days;
    }
}
