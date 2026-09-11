<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_items';
    protected $fillable = [
        'invoice_id',
        'item_name',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'created_by',
        'updated_by',
    ];

}
