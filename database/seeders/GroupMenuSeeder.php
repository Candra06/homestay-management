<?php

namespace Database\Seeders;
use App\Models\GroupMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            [
                'code' => 'MAIN',
                'name' => 'Main',
            ],
            [
                'code' => 'MASTER',
                'name' => 'Master',
            ],
            [
                'code' => 'ROOMS_MANAGEMENT',
                'name' => 'Manajemen Kamar',
            ],
             [
                'code' => 'RESERVATION',
                'name' => 'Reservasi',
            ],
            [
                'code' => 'INVENTORY',
                'name' => 'Inventaris',
            ],
            [
                'code' => 'FINANCE',
                'name' => 'Keuangan',
            ]
        ];
        foreach ($data as $item) {
            $user =  GroupMenu::create($item);

        }
    }
}
