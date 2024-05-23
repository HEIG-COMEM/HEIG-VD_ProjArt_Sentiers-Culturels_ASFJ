<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\User;

class BadgeUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $badges = Badge::all();

        foreach ($users as $user) {
            $NB_BADGES = rand(0, 3);
            // get first $NB_BADGES badges in order to avoid duplicates
            $userBadges = $badges->take($NB_BADGES);
            $user->badges()->attach($userBadges);
        }
    }
}
