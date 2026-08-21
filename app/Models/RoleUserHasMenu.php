<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUserHasMenu extends Model
{
    use HasFactory;
    protected $table = 'role_user_has_menu';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'id_menu');
    }

    public function groupMenu()
    {
        return $this->hasOne(GroupMenu::class, 'id', 'group_menu_id');
    }

    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'id', 'id_role');
    }
}
