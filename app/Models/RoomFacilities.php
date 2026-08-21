<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomFacilities extends Model
{
    protected $table= 'room_facilities';
    protected $fillable = ['id_room', 'id_facility'];

    protected $guarded = ['id'];

    function room_types(){
        return $this->belongsTo(RoomTypes::class, 'id_room');
    }

    function facility(){
        return $this->belongsTo(Facility::class, 'id_facility', 'id');
    }
}
