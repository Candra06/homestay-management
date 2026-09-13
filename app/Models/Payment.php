<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';
    protected $fillable = [
        'invoice_id',
        'payment_method',
        'amount',
        'status',
        'reference_number',
        'paid_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $casts = [
        'amount' => 'integer'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
