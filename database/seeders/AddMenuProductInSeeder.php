<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\RoleUserHasMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddMenuProductInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item = [
            'code' => 'PRODUCT_IN_OUT',
            'name' => 'Keluar Masuk Barang',
            'icon' => 'fe fe-grid',
            'url' => '/product_in_out',
            'have_list' => 'Y',
            'have_create' => 'Y',
            'have_edit' => 'Y',
            'have_delete' => 'Y',
        ];

        $menu = Menu::create($item);
        RoleUserHasMenu::create([
            'id_role' => 1,
            'id_menu' => $menu->id,
            'access_list' => $item['have_list'],
            'access_create' => $item['have_create'],
            'access_edit' => $item['have_edit'],
            'access_delete' => $item['have_delete'],
        ]);
        RoleUserHasMenu::create([
            'id_role' => 2,
            'id_menu' => $menu->id,
            'access_list' => $item['have_list'],
            'access_create' => $item['have_create'],
            'access_edit' => $item['have_edit'],
            'access_delete' => $item['have_delete'],
        ]);
    }
}
