<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    
    protected $table = 'employees';
    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'no_telp',
        'email',
        'status',
        'user_id',
    ];

    public function foto()
    {
        return $this->hasMany(Attachment::class, 'id_attachable')->where('attachable_type', Employee::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
