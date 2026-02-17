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
                'first_name' => 'Ryan Vergel',
                'last_name' => 'Hojilla',
                'email' => 'yanz.sytian@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'King Bon',
                'last_name' => 'Racimo',
                'email' => 'pay.sytian@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Kits',
                'last_name' => 'Perez',
                'email' => 'kits.perez@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Barlaw Kenneth',
                'last_name' => 'Sytian',
                'email' => 'kenneth@sytian-productions.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
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
            [
                'first_name' => 'Angelo',
                'last_name' => 'Serenuela',
                'email' => 'angelo.sytian@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Regular',
                'last_name' => 'Admin',
                'email' => 'regular.admin@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_ADMIN,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test.user@gmail.com',
                'password' => bcrypt('admin123'),
                'type' => UserRole::TYPE_USER,
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
