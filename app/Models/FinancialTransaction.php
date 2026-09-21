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
    public function reference()
    {
        return $this->morphTo();
    }
    
    public function getReferenceAttribute($value)
    {
        return $this->morphTo()->first();
    }

}
