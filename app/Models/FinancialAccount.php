<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAccount extends Model
{
    use SoftDeletes;

    protected $table = 'financial_accounts';
    protected $fillable = [
        'account_code',
        'account_name',
        'type',
        'balance',
        'is_active'
        'created_by',
        'updated_by',
    ];

}
