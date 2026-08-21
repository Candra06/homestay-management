<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateOrderingMenuClass extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'DASHBOARD',
                'name' => 'Dashboard',
                'order_position' => 0,
            ],
            [
                'code' => 'CUSTOMER',
                'name' => 'Customer',
                'order_position' => 1,
            ],
            [
                'code' => 'WINNER',
                'name' => 'Winner',
                'order_position' => 2,
            ],
            [
                'code' => 'SUPPLIER',
                'name' => 'Supplier',
                'order_position' => 3,
            ],
            [
                'code' => 'PRODUCT_IN_OUT',
                'name' => 'Keluar Masuk Barang',
                'order_position' => 4,
            ],
            [
                'code' => 'MASTER_WORDING',
                'name' => 'Master Wording',
                'order_position' => 5,
            ],
            [
                'code' => 'NOTIFICATION',
                'name' => 'Notification',
                'order_position' => 6,
            ],
            [
                'code' => 'DATA_USER',
                'order_position' => 7,
            ],
            [
                'code' => 'ROLE_USER',
                'order_position' => 8,
            ],
            [
                'code' => 'ROLE_AKSES',
                'order_position' => 9,
            ],
            [
                'code' => 'MASTER_MENU',
                'order_position' => 10,
            ],
        ];
        foreach ($data as $item) {
            Menu::where('code', $item['code'])->update(['order_position' => $item['order_position']]);
        }
    }
}
