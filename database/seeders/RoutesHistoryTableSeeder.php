<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\RouteHistory;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RoutesHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Some user should have done some routes and other only started them
        $users = User::all();
        $routes = Route::all();

        foreach ($users as $user) {
            if (rand(0, 5) < 3) {
                continue;
            }
            $routes->random(rand(1, count($routes)))->each(function ($route) use ($user) {
                $routeHistory = new RouteHistory();
                $routeHistory->route_id = $route->id;
                $routeHistory->user_id = $user->id;
                $routeHistory->start_timestamp = now()->subDays(rand(0, 30));
                $routeHistory->end_timestamp = $routeHistory->start_timestamp->addMinutes(rand(30, 120));
                $routeHistory->save();
            });
        }
    }
}
