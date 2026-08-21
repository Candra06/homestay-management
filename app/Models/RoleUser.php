<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = 'role_user';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function haveAccess()
    {
        return $this->hasMany(RoleUserHasMenu::class, 'id_role');
    }

    public function roles() {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
