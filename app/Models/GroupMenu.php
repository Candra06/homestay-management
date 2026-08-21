<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupMenu extends Model
{
    use HasFactory;
    protected $table = 'groups_menu';

    protected $fillable = [
        'code',
        'name',
    ];
}
