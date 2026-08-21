<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Additional extends Model
{
    use SoftDeletes;
    protected $table = "additionals";
    protected $fillable = [
        "name",
        "price",
        "type",
    ];
}
