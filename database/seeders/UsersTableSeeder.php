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
        // TODO TO DEL
        // $DEFAULT_USERS = [
        //     [
        //         'firstname' => 'Admin',
        //         'lastname' => 'Doe',
        //         'email' => 'a@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('password'),
        //         'role_int' => 1,
        //     ],
        //     [
        //         'firstname' => 'User',
        //         'lastname' => 'Doe',
        //         'email' => 'u@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('password'),
        //     ],
        // ];

        // foreach ($DEFAULT_USERS as $user) {
        //     User::create($user);
        // }

        // try {
        //     $data = JsonHelper::readJson('/users.json');

        //     $users = collect($data['users'])
        //         ->values()
        //         ->all();

        //     foreach ($users as $user) {
        //         User::updateOrCreate([
        //             'email' => $user['email']
        //         ], [
        //             'firstname' => $user['firstname'],
        //             'lastname' => $user['lastname'],
        //             'email' => $user['email'],
        //             'email_verified_at' => now(),
        //             'password' => bcrypt($user['password']),
        //             'role_int' => $user['role_int']
        //         ]);
        //     }
        // } catch (\Exception $e) {
        //     $this->command->error($e->getMessage());
        // }

        // User::factory()->count(10)->create();
    }
}
