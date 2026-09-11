<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'booking_code',
        'guest_id',
        'book_reff',
        'ota_name',
        'external_booking_id',
        'payment_status',
        'payment_method',
        'booking_status',
        'subtotal',
        'tax',
        'total_payment',
        'grand_total',
        'amount_paid',
        'amount_refunded',
        'down_payment',
        'discount_amount',
        'paid_at',
        'down_payment_paid_at',
        'note',
        'promo_id',
        'voucher_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'down_payment_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'canceled')->where('checkout_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function userCreate()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userUpdate()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
