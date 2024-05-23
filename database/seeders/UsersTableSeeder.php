<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $DEFAULT_USERS = [
            [
                'firstname' => 'Admin',
                'lastname' => 'Doe',
                'email' => 'a@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role_int' => 1,
            ],
            [
                'firstname' => 'User',
                'lastname' => 'Doe',
                'email' => 'u@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($DEFAULT_USERS as $user) {
            User::create($user);
        }

        User::factory()->count(10)->create();
    }
}
