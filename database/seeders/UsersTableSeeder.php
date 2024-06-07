<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Helpers\JsonHelper;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $data = JsonHelper::readJson('/users.json');

            $users = collect($data['users'])
                ->values()
                ->all();

            foreach ($users as $user) {
                User::updateOrCreate([
                    'email' => $user['email']
                ], [
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email'],
                    'email_verified_at' => now(),
                    'password' => bcrypt($user['password']),
                    'role_int' => $user['role_int']
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }

        // User::factory()->count(10)->create();
    }
}
