<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'guests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telp',
        'identity_type',
        'identity_number',
        'address',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
