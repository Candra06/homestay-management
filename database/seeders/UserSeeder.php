<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => bcrypt('123456'),
                'role' => 'Superadmin',
                'role_id' => 1,
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('123456'),
                'role' => 'Admin',
                'role_id' => 1,
            ],
        ];
        foreach ($data as $item) {
            $user =  User::create($item);

        }
    }
}
