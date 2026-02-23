<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'type' => UserRole::TYPE_SUPER_ADMIN,
                'active' => true,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Standard',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'type' => UserRole::TYPE_ADMIN,
                'active' => true,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Regular',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'type' => UserRole::TYPE_USER,
                'active' => true,
                'created_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
