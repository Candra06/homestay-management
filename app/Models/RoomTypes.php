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
        "slug",
    ];

    public function facilities()
    {
        return $this->hasMany(RoomFacilities::class, 'id_room', 'id')->whereNull('deleted_at');
    }
    
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'reff_id', 'id')->where('reff_feature', 'room-types');
    }
}
