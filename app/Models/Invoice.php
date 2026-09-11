<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'invoices';
    protected $fillable = [
        'booking_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'service_charge',
        'tax_amount',
        'grand_total',
        'amount_paid',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
