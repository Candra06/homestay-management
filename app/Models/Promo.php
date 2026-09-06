<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'status',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
