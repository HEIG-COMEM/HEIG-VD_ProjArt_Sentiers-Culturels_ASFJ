<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Season;

class RouteSeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::all();
        $seasons = Season::all();

        $countSeasons = $seasons->count();

        foreach ($routes as $route) {
            $route->seasons()->attach($seasons->random(rand(1, $countSeasons))->pluck('id'));
        }
    }
}
