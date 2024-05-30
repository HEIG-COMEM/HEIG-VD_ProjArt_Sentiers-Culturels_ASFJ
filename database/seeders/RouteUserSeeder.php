<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Route;

class RouteUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $routes = Route::all();

        foreach ($users as $user) {
            foreach ($routes as $route) {
                if (rand(0, 1)) continue;
                $user->routes()->attach($route);
            }
        }
    }
}
