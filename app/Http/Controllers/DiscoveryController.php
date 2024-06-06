<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoutesNearbyRequest;
use App\Http\Resources\DifficultyResource;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\Difficulty;
use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\InterestPoint;

class DiscoveryController extends Controller
{
    public function index()
    {
        $routes = Route::all();
        $routes->load('difficulty');
        $routes->load('pictures');
        $routes->load('tags');
        $routes->load('rates');

        $routes->map(function ($route) {
            $route->type = 'route';
            return $route;
        });

        $routes->makeHidden('path');

        $routesOrderedRating = $routes->sortByDesc(function ($route) {
            return $route->rates->avg('rate');
        })->take(3);
        $routesOrderedRating->load('pictures');

        $routesOrderedRating->makeHidden('path');

        $interestpoints = InterestPoint::all();
        $interestpoints->load('pictures');
        $interestpoints->load('tags');

        $interestpoints->map(function ($interestpoint) {
            $interestpoint->type = 'interestpoint';
            return $interestpoint;
        });

        $difficulties = Difficulty::all();
        $tags = Tag::all();

        $latestRoutes = Route::latest()->take(3)->get();
        $latestRoutes->load('pictures');

        $latestRoutes->makeHidden('path');

        return Inertia::render('Discovery', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestpoints),
            'difficulties' => DifficultyResource::collection($difficulties),
            'tags' => TagResource::collection($tags),
            'latestRoutes' => RouteResource::collection($latestRoutes),
            'routesOrderedRating' => RouteResource::collection($routesOrderedRating)
        ]);
    }

    private function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) +  cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515 * 1.609344;
        return ($distance);
    }

    public function getNearbyRoutes(RoutesNearbyRequest $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;

        $routes = Route::all();

        foreach ($routes as $route) {
            $distance = $this->distance($latitude, $longitude, $route->start_lat, $route->start_long);
            if ($distance > $radius) {
                $routes->forget($route->id);
            }
        }

        $routes->makeHidden('path');
        $routes = $routes->take(3);
        $routes->load('pictures');

        return RouteResource::collection($routes);
    }
}
