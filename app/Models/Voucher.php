<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

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
        'max_discount',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
