<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;
    protected $table = "facilities";
    protected $fillable = [
        "nama_fasilitas",
        "deskripsi",
        "icon",
        "type",
    ];

    public function images()
    {
        return $this->hasMany(FacilityImage::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'facility_room', 'facility_id', 'room_id');
    }
    
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'reff_id', 'id')->where('reff_feature', 'facility');
    }
}
