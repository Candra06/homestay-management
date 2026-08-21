<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'SUPER_ADMIN',
                'name' => 'Super Admin',
            ],
        ];
        foreach ($data as $item) {
            $user =  RoleUser::create($item);

        }
    }
}
