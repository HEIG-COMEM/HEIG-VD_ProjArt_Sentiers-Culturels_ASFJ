<?php

namespace Database\Seeders;

use App\Models\InterestPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Difficulty;

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $NB_ROUTES = 1;
        $MIN_TOUR_POINTS = 2;
        $interestPoints = InterestPoint::all();
        $difficulty = Difficulty::all();

        for ($i = 0; $i < $NB_ROUTES; $i++) {
            $NB_TOUR_POINTS = rand($MIN_TOUR_POINTS, count($interestPoints));
            $tourPoints = $interestPoints->random($NB_TOUR_POINTS);

            $route = new Route();
            $route->name = 'Route ' . $i;
            $route->description = 'Description de la route ' . $i;
            $route->time = rand(1, 120);
            $route->length = rand(1, 10);

            $route->start_lat = rand(0, 100);
            $route->start_lng = rand(0, 100);
            $route->end_lat = rand(0, 100);
            $route->end_lng = rand(0, 100);

            $route->difficulty()->associate($difficulty->random());
            $route->save();

            $route->interestPoints()->attach($tourPoints);
        }
    }
}
