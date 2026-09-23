<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAdditional extends Model
{
    protected $table = 'booking_additionals';

    protected $fillable = [
        'booking_id',
        'additional_id',
        'price_per_additional',
        'total_price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function item()
    {
        return $this->belongsTo(Additional::class, 'additional_id');
    }
}
