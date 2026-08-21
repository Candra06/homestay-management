<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomTypes extends Model
{
    use SoftDeletes;
    protected $table = 'room_types';
    protected $fillable = [
        "type_name",
        "bed_type",
        "kapasitas",
        "base_price",
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'type_id');
    }

    public function facilities()
    {
        return $this->hasMany(RoomFacilities::class, 'id_room', 'id')->whereNull('deleted_at');
    }
}
