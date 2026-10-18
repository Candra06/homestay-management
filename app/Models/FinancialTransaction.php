<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'financial_transactions';
    protected $fillable = [
        'financial_account_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'description',
        'reference_type',
        'reference_id',
        'payment_method',
        'payment_method_id',
        'created_by',
        'updated_by'
    ];

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi polymorphic — otomatis resolve ke model yang sesuai
     * berdasarkan morphMap di AppServiceProvider.
     * Contoh: $transaction->reference  → Booking instance (jika reference_type='booking')
     */
    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    /**
     * Shortcut langsung ke Booking (tanpa polymorphic)
     * Gunakan ketika reference_type sudah pasti 'booking'.
     * Contoh: $transaction->booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reference_id');
    }

}
