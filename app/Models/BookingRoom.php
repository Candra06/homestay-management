<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = 'booking_rooms';

    protected $fillable = [
        'booking_id',
        'room_id',
        'checkin_date',
        'checkout_date',
        'price_per_night',
        'total_price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id', 'id');
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
