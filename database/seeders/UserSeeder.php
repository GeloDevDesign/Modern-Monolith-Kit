<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [

            [
                'first_name' => 'Angelo',
                'last_name' => 'Serenuela',
                'email' => 'angelo.sytian@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_SUPER_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Rona Faye',
                'last_name' => 'Agaton',
                'email' => 'rona.agaton@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
        ];



        User::upsert(
            $users,
            ['email'],
            ['first_name', 'last_name', 'password', 'type']
        );
    }
}
